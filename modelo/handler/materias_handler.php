<?php

require_once('../../helpers/conexion.php');

class MateriasHandler
{
    protected $id = null;
    protected $id_usuario = null;
    protected $nombre = null;
    protected $color = null;

    // Métodos SCRUD

    public function searchRows()
    {
        $value = '%' . Validaciones::getSearchValue() . '%';
        $sql = 'SELECT id_materia, nombre, color_hex
                FROM materias
                WHERE id_usuario = ? AND nombre LIKE ?
                ORDER BY nombre';
        $params = array($_SESSION['id_usuario'], $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO materias(id_usuario, nombre, color_hex)
                VALUES(?, ?, ?)';
        $params = array($_SESSION['id_usuario'], $this->nombre, $this->color);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_materia, nombre, color_hex
                FROM materias
                WHERE id_usuario = ?
                ORDER BY nombre';
        $params = array($_SESSION['id_usuario']);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_materia, nombre, color_hex
                FROM materias
                WHERE id_materia = ? AND id_usuario = ?';
        $params = array($this->id, $_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE materias
                SET nombre = ?, color_hex = ?
                WHERE id_materia = ? AND id_usuario = ?';
        $params = array($this->nombre, $this->color, $this->id, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM materias
                WHERE id_materia = ? AND id_usuario = ?';
        $params = array($this->id, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }
}