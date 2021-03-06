// == Import validators
import { validatePassword } from 'src/utils/validators';

// == Import npm
import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

// == Import components
import {
  TextField,
  IconButton,
  InputAdornment,
  useMediaQuery,
  // Button,
} from '@material-ui/core';
import { useTheme } from '@material-ui/core/styles';
import Visibility from '@material-ui/icons/Visibility';
import VisibilityOff from '@material-ui/icons/VisibilityOff';

// == Import styles
import signInFormStyles from './signInFormStyles';

// == Composant
const SignInPassword = ({
  password,
  setFieldValue,
  setError,
}) => {
  const classes = signInFormStyles();
  const [showPassword, setShowPassword] = useState(false);

  // Responsive mobile
  const theme = useTheme();
  const fullScreen = useMediaQuery(theme.breakpoints.down('xs'));

  // ============= Password Check Display ==========
  const [focus, setFocus] = useState(false);
  const [pwdError, setPwdError] = useState(false);
  const [errorPwdMsg, setPwdErrorMsg] = useState('');

  // eslint-disable-next-line consistent-return
  const handlePwdErrors = () => {
    if (focus) {
      if (validatePassword(password)) {
        return [setPwdError(false), setPwdErrorMsg(''), setError(false)];
      }
      setPwdError(true);
      setError(true);
      setPwdErrorMsg('Minimum requis : 8 caractères / 1 Majuscule / 1 chiffre ');
    }
  };

  useEffect(() => {
    handlePwdErrors();
  });
  // ========================================

  const handleChange = (event) => {
    setFieldValue(event.target.name, event.target.value);
  };

  const handleClickShowPassword = () => {
    setShowPassword(!showPassword);
  };

  // eslint-disable-next-line no-nested-ternary
  const InputLabelProps = fullScreen
    ? {
      className: classes.inputLabelField,
      shrink: fullScreen,
    } : {
      className: classes.inputLabelField,
    };

  return (
    <>
      <TextField
        id="password"
        label="Mot de passe"
        className={classes.textField}
        name="password"
        type={showPassword ? 'text' : 'password'}
        required
        variant="outlined"
        autoComplete="current-password"
        margin="dense"
        InputLabelProps={InputLabelProps}
        value={password}
        onChange={handleChange}
        onFocus={() => setFocus(true)}
        error={pwdError}
        helperText={errorPwdMsg}
        InputProps={{
          endAdornment:
          // eslint-disable-next-line react/jsx-indent
            <InputAdornment position="end">
              <IconButton
                aria-label="toggle password visibility"
                onClick={handleClickShowPassword}
              >
                {showPassword ? <Visibility /> : <VisibilityOff />}
              </IconButton>
            </InputAdornment>,
        }}
      />
      {/* <Button href="" disabled className={classes.lostLink}>
        Mot de Passe oublié ?
      </Button> */}
    </>
  );
};

SignInPassword.propTypes = {
  password: PropTypes.string.isRequired,
  setFieldValue: PropTypes.func.isRequired,
  setError: PropTypes.func.isRequired,
};

// == Export
export default SignInPassword;
