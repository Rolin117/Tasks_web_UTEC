<?php

require_once('../../helpers/validaciones.php');
require_once('../handler/usuarios_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla CATEGORIAS.
 */
class CategoriasData extends CategoriasHandler
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
            $this->data_error = 'El identificador de la categoría es incorrecto';
            return false;
        }
    }

    public function setNombre($value, $min = 2, $max = 50)
    {
        if (!Validaciones::validateAlphanumeric($value)) {
            $this->data_error = 'El nombre debe ser un valor alfanumérico';
            return false;
        } elseif (Validaciones::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
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