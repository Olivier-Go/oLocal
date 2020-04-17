<?php

namespace App\Controller\Api;

use App\Entity\Region;
use App\Entity\Catalog;
use App\Entity\Product;
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LocalSupplierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ApiCatalogsController extends AbstractController
{
    /**
     * @Route("/api/catalogs", name="api_catalogs")
     */
    public function index()
    {
        return $this->render('api/api_catalog/index.html.twig', [
            'controller_name' => 'ApiCatalogController',
        ]);
    }

    /**
     * @Route("/api/catalogs/add", name="api_catalogs_add", methods="POST")
     */
    public function add (Request $request, DenormalizerInterface $denormalizer, ValidatorInterface $validator, ProductRepository $productRepository, RegionRepository $regionRepository, LocalSupplierRepository $localSupplierRepository, UserRepository $userRepository, CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        // 1. On récupère le contenu JSON
        $dataRequest = json_decode($request->getContent());
      
        $catalog = $denormalizer->denormalize($dataRequest, Catalog::class);
        
        //on valide l'entité 
        $errors = $validator->validate($catalog);
        if (count($errors) !== 0) {
            $jsonErrors = [];
            foreach ($errors as $error) {
                $jsonErrors[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }

            return $this->json($jsonErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $localSupplierId = $dataRequest->localSupplier;
        $localSupplier = $localSupplierRepository->find($localSupplierId);

        $userId = $dataRequest->user;
        $user = $userRepository->find($userId);

        $productName = $dataRequest->product;
        // if product already in DB
        if ($productRepository->findOneBy(['name'=>$productName])){
            // take its id with getId()
            $product = $productRepository->findOneBy(['name'=>$productName]);            
        }
        else{
            // else -> call add product function before adding it to Catalog
            $categoryId = $dataRequest->category;
            $category = $categoryRepository->find($categoryId);
            $product = new Product;
            $product->setName($productName);
            $product->setCategory($category);
            $em=$this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $product = $productRepository->findOneBy(['name'=>$productName]);

        }

        $catalog->setProduct($product);
        $catalog->setUser($user);
        $catalog->setLocalSupplier($localSupplier);
        $em=$this->getDoctrine()->getManager();
        $em->persist($catalog);
        $em->flush();

        return $this->json('catalogue ajouté',201);

    }
}
