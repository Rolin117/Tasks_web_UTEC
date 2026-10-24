<?php

require_once('../../helpers/conexion.php');

class CategoriasHandler
{
    protected $id = null;
    protected $nombre = null;

    // Métodos SCRUD
    // Todas las consultas se filtran por el usuario en sesión.

    public function searchRows()
    {
        $value = '%' . Validaciones::getSearchValue() . '%';
        $sql = 'SELECT id_categoria, nombre
                FROM categorias
                WHERE id_usuario = ? AND nombre LIKE ?
                ORDER BY nombre';
        $params = array($_SESSION['id_usuario'], $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO categorias(id_usuario, nombre)
                VALUES(?, ?)';
        $params = array($_SESSION['id_usuario'], $this->nombre);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_categoria, nombre
                FROM categorias
                WHERE id_usuario = ?
                ORDER BY nombre';
        $params = array($_SESSION['id_usuario']);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_categoria, nombre
                FROM categorias
                WHERE id_categoria = ? AND id_usuario = ?';
        $params = array($this->id, $_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE categorias
                SET nombre = ?
                WHERE id_categoria = ? AND id_usuario = ?';
        $params = array($this->nombre, $this->id, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM categorias
                WHERE id_categoria = ? AND id_usuario = ?';
        $params = array($this->id, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }
}