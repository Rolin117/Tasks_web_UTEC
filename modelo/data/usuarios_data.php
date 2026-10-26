<?php

require_once('../../helpers/validaciones.php');
require_once('../handler/usuarios_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla USUARIOS.
 */
class UsuariosData extends UsuariosHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;

    /*
     *  Métodos para validar y establecer los datos.
     */
    public function setId($value)
    {
        if (Validaciones::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del usuario es incorrecto';
            return false;
        }
    }

    public function setNombre($value, $min = 2, $max = 100)
    {
        if (!Validaciones::validateAlphabetic($value)) {
            $this->data_error = 'El nombre debe contener solo letras y espacios';
            return false;
        } elseif (Validaciones::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setApellido($value, $min = 2, $max = 100)
    {
        if (!Validaciones::validateAlphabetic($value)) {
            $this->data_error = 'El apellido debe contener solo letras y espacios';
            return false;
        } elseif (Validaciones::validateLength($value, $min, $max)) {
            $this->apellido = $value;
            return true;
        } else {
            $this->data_error = 'El apellido debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setCorreo($value, $min = 6, $max = 150)
    {
        if (!Validaciones::validateEmail($value)) {
            $this->data_error = 'El correo electrónico no es válido';
            return false;
        } elseif (Validaciones::validateLength($value, $min, $max)) {
            $this->correo = $value;
            return true;
        } else {
            $this->data_error = 'El correo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Valida la contraseña y la guarda ya cifrada.
    public function setPassword($value)
    {
        if (Validaciones::validatePassword($value)) {
            $this->password = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            $this->data_error = Validaciones::getPasswordError();
            return false;
        }
    }

    /*
     *  Método para obtener los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }
}