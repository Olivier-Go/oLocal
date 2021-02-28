<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Region;
use App\Entity\Catalog;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\LocalSupplier;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OlocalProvider;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {   
        $faker= Factory::create('fr_FR');
        $faker->addProvider(new OlocalProvider($faker));
        $faker->addProvider(new Restaurant($faker));

        // we create all regions
        $regionsList = [];

        $regionA= new Region();
        $regionA->setName("Auvergne-Rhône-Alpes");
        $regionA->setCreatedAt(new \DateTime());
        $regionsList[] = $regionA;
        $manager->persist($regionA);

        $region= new Region();
        $region->setName("Bourgogne-Franche-Comté");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Bretagne");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Centre-Val de Loire");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Corse");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Grand Est");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Guadeloupe");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Guyane");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Hauts-de-France");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Île-de-France");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Mayotte");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("Martinique");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Normandie");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Nouvelle-Aquitaine");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Occitanie");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Pays de la Loire");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);
        
        $region= new Region();
        $region->setName("Provence-Alpes-Côte d'Azur");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        $region= new Region();
        $region->setName("La Réunion");
        $region->setCreatedAt(new \DateTime());
        $regionsList[] = $region;
        $manager->persist($region);

        // we create all categories
        $categoriesList = [];

        $category = new Category();
        $category->setName("Fruits");
        $category->setCreatedAt(new \DateTime());
        $categoriesList[] = $category;
        $manager->persist($category);
        
        $category = new Category;
        $category->setName("Légumes");
        $category->setCreatedAt(new \DateTime());
        $categoriesList[] = $category;
        $manager->persist($category);

        $category = new Category;
        $category->setName("Viandes, Oeufs et Poissons");
        $category->setCreatedAt(new \DateTime());
        $categoriesList[] = $category;
        $manager->persist($category);

        $category = new Category;
        $category->setName("Lait et Produits Laitiers");
        $category->setCreatedAt(new \DateTime());
        $categoriesList[] = $category;
        $manager->persist($category);

        $category = new Category;
        $category->setName("Boissons");
        $category->setCreatedAt(new \DateTime());
        //$categoriesList[] = $category;
        $manager->persist($category);

        $category = new Category;
        $category->setName("Hygiène et beauté");
        $category->setCreatedAt(new \DateTime());
        //$categoriesList[] = $category;
        $manager->persist($category);

        $category = new Category;
        $category->setName("Entretien et Nettoyage");
        $category->setCreatedAt(new \DateTime());
        //$categoriesList[] = $category;
        $manager->persist($category);   

        // we create 30 local suppliers
        $localList = [];
        for ($i = 0; $i < 30; $i++) {
            $localSupplier = new LocalSupplier();
            $localSupplier->setName($faker->unique()->company());
            $localSupplier->setSiret($faker->numberBetween(10000000000000, 99999999999999));
            $localSupplier->setPostalCode($faker->numberBetween(10000, 99999));
            $localSupplier->setCity($faker->city());
            $localSupplier->setCreatedAt(new \DateTime());

            $randomRegion = $regionsList[array_rand($regionsList)];
            $localSupplier->setRegion($randomRegion);

            $localList[] = $localSupplier;
            $manager->persist($localSupplier);
        }

        // we create 55 products
        $productsList = [];
        for ($p = 0; $p < 55; $p++) {
            $randomCategory = $categoriesList[array_rand($categoriesList)];
            $product = null;
            switch ($randomCategory->getName()) {
                case 'Légumes':
                    $vegetable = $faker->vegetableName();
                    if (!in_array($vegetable, $productsList)) {
                        $product = new Product();
                        $product->setCreatedAt(new \DateTime());
                        $product->setName($vegetable);
                    }
                    break;
                case 'Fruits':
                    $fruit = $faker->fruitName();
                    if (!in_array($fruit, $productsList)) {
                        $product = new Product();
                        $product->setCreatedAt(new \DateTime());
                        $product->setName($fruit);
                    }
                    break;
                case 'Boissons':
                    $beverage = $faker->beverageName();
                    if (!in_array($beverage, $productsList)) {
                        $product = new Product();
                        $product->setCreatedAt(new \DateTime());
                        $product->setName($beverage);
                    }
                    break;
                case 'Viandes, Oeufs et Poissons':
                    $meat = $faker->meatName();
                    if (!in_array($meat, $productsList)) {
                        $product = new Product();
                        $product->setCreatedAt(new \DateTime());
                        $product->setName($meat);
                    }
                    break;
                case 'Lait et Produits Laitiers':
                    $dairy = $faker->dairyName();
                    if (!in_array($dairy, $productsList)) {
                        $product = new Product();
                        $product->setCreatedAt(new \DateTime());
                        $product->setName($dairy);
                    }
                    break;
            }

            if ($product) {
                $product->setCategory($randomCategory);
                $productsList[] = $product;
            }
            else {
                $p--;
            }
        }

        // we create 50 users (shopkeepers)    
        $usersList = [];           
        for ($i = 0; $i < 50; $i++) {
            $user= new User();
            $user->setEmail($faker->unique()->email());
            $user->setFirstname($faker->firstname());
            $user->setLastname($faker->lastname());
            $user->setPassword($this->encoder->encodePassword($user, 'Password0'));
            $user->setUserRole(['ROLE_SHOPKEEPER']);
            $user->setIsEmailChecked(true);
            $user->setIsActive(true);
            $user->setAdditionalAddress($faker->optional()->secondaryAddress());
            $user->setRepeatIndex($faker->optional()->randomElement(["Bis","Ter"]));
            $user->setWayNumber($faker->buildingNumber());
            $user->setWayType($faker->randomElement(["rue","avenue","allée","boulevard", "route","place"]));
            $user->setWayName($faker->streetName());
            $user->setPostalCode($faker->numberBetween(10000, 99999));
            $user->setCity($faker->city());
            $user->setSiret($faker->numberBetween(10000000000000, 99999999999999));
            $user->setCompanyName($faker->unique()->company());
            $user->setCompanyDescription($faker->realText($maxNbChars = 400, $indexSize = 2));
            $user->setLogoPicture('/uploads/frontoffice'.mt_rand(1, 30).'.jpg');
            $user->setPhone($faker->serviceNumber());
            $user->setWebsite('http://www.monsitewebtropbeau.com');
            $user->setCreatedAt(new \DateTime());
            
            $randomRegion = $regionsList[array_rand($regionsList)];
            $user->setRegion($randomRegion);

            // we give between 3 and 10 products
            for ($p = 0; $p < mt_rand(3, 10); $p++) {
                $product = $productsList[array_rand($productsList)];
                $product->setCreatedAt(new \DateTime());
                $catalogProduct = new Catalog;
                $catalogProduct->setUser($user);
                $catalogProduct->setProduct($product);
                $randomLocal = $localList[array_rand($localList)];
                $catalogProduct->setLocalSupplier($randomLocal);
                $product->addCatalog($catalogProduct); 
                $manager->persist($catalogProduct);
            }

            $usersList[] = $user;
            $manager->persist($user);
        }

        // custom user for tests
        $custom = new User();
        $custom->setEmail('user@user.com');
        $custom->setFirstname($faker->firstname());
        $custom->setLastname($faker->lastname());
        $custom->setPassword($this->encoder->encodePassword($custom, 'Password0'));
        $custom->setUserRole(['ROLE_SHOPKEEPER']);
        $custom->setIsEmailChecked(true);
        $custom->setIsActive(true);
        $custom->setAdditionalAddress($faker->optional()->secondaryAddress());
        $custom->setRepeatIndex($faker->optional()->randomElement(["Bis","Ter"]));
        $custom->setWayNumber($faker->buildingNumber());
        $custom->setWayType($faker->randomElement(["rue","avenue","allée","boulevard", "route","place"]));
        $custom->setWayName($faker->streetName());
        $custom->setPostalCode($faker->numberBetween(10000, 99999));
        $custom->setCity($faker->city());
        $custom->setSiret($faker->numberBetween(10000000000000, 99999999999999));
        $custom->setCompanyName('La boutique des tests');
        $custom->setCompanyDescription($faker->realText(400, 2));
        $custom->setLogoPicture('/uploads/frontoffice'.mt_rand(1, 30).'.jpg');
        $custom->setPhone($faker->serviceNumber());
        $custom->setWebsite('http://www.monsitewebtropbeau.com');
        $custom->setCreatedAt(new \DateTime());    
        $custom->setRegion($regionA);

        // we give between 3 and 10 products
        for ($p = 0; $p < mt_rand(3, 10); $p++) {
            $product = $productsList[array_rand($productsList)];
            $product->setCreatedAt(new \DateTime());
            $catalogProduct = new Catalog;
            $catalogProduct->setUser($user);
            $catalogProduct->setProduct($product);
            $randomLocal = $localList[array_rand($localList)];
            $catalogProduct->setLocalSupplier($randomLocal);
            $product->addCatalog($catalogProduct); 
            $manager->persist($catalogProduct);
        }

        $usersList[] = $custom;
        $manager->persist($custom);

        // create admin user
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setFirstname('Micheline');
        $admin->setLastname('Michu');
        $admin->setPassword($this->encoder->encodePassword($admin, 'Password0'));
        $admin->setUserRole(['ROLE_ADMIN']);
        $admin->setIsEmailChecked(true);
        $admin->setIsActive(true);
        $admin->setCreatedAt(new \DateTime());    
        $admin->setRegion($regionA);
        $manager->persist($admin);



        $manager->flush();
    }
}