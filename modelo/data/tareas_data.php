<?php

require_once('../../helpers/validaciones.php');
require_once('../handler/usuarios_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla TAREAS.
 */
class TareasData extends TareasHandler
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
            $this->data_error = 'El identificador de la tarea es incorrecto';
            return false;
        }
    }

    // La materia es opcional: un valor vacío deja la tarea sin materia (NULL).
    // Se comprueba que la materia pertenezca al usuario en sesión.
    public function setMateria($value)
    {
        if ($value === null || $value === '') {
            $this->materia = null;
            return true;
        } elseif (!Validaciones::validateNaturalNumber($value)) {
            $this->data_error = 'El identificador de la materia es incorrecto';
            return false;
        }
        $sql = 'SELECT id_materia
                FROM materias
                WHERE id_materia = ? AND id_usuario = ?';
        if (Database::getRow($sql, array($value, $_SESSION['usuario_id']))) {
            $this->materia = $value;
            return true;
        } else {
            $this->data_error = 'Materia inexistente';
            return false;
        }
    }

    // La categoría es opcional: un valor vacío deja la tarea sin categoría (NULL).
    // Se comprueba que la categoría pertenezca al usuario en sesión.
    public function setCategoria($value)
    {
        if ($value === null || $value === '') {
            $this->categoria = null;
            return true;
        } elseif (!Validaciones::validateNaturalNumber($value)) {
            $this->data_error = 'El identificador de la categoría es incorrecto';
            return false;
        }
        $sql = 'SELECT id_categoria
                FROM categorias
                WHERE id_categoria = ? AND id_usuario = ?';
        if (Database::getRow($sql, array($value, $_SESSION['usuario_id']))) {
            $this->categoria = $value;
            return true;
        } else {
            $this->data_error = 'Categoría inexistente';
            return false;
        }
    }

    public function setTitulo($value, $min = 2, $max = 255)
    {
        if (!Validaciones::validateString($value)) {
            $this->data_error = 'El título contiene caracteres prohibidos';
            return false;
        } elseif (Validaciones::validateLength($value, $min, $max)) {
            $this->titulo = $value;
            return true;
        } else {
            $this->data_error = 'El título debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Estado de la tarea: 1 (completada) o 0 (pendiente).
    public function setCompletada($value)
    {
        if (Validaciones::validateBoolean($value)) {
            $this->completada = (int) $value;
            return true;
        } else {
            $this->data_error = 'El estado de la tarea es incorrecto';
            return false;
        }
    }

    // Fecha en formato AAAA-MM-DD (el que envía <input type="date">).
    public function setFechaAsignada($value)
    {
        // Se verifica el formato antes de usar el validador, que espera tres partes separadas por guion.
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $this->data_error = 'La fecha debe tener el formato AAAA-MM-DD';
            return false;
        } elseif (Validaciones::validateDate($value)) {
            $this->fecha_asignada = $value;
            return true;
        } else {
            $this->data_error = 'La fecha asignada es incorrecta';
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