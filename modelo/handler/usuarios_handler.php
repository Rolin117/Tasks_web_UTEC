<?php

require_once('../../helpers/conexion.php');

class UsuariosHandler 
{

protected $id = null;
protected $nombre = null;
protected $correo = null;
protected $password = null;
protected $fecha = null;



    public function checkPassword($password)
    {
         $sql = 'SELECT password
                 FROM usuarios
                 WHERE id_usuario = ?';
         $params = array($_SESSION['id_usuario']);
         $data = Database::getRow($sql, $params);
         if (password_verify($password, $data['password'])) {
             return true;
         } else {
             return false;
         }
    }

    // Metodos SCRUD
       public function searchRows()
    {
        $value = '%' . Validaciones::getSearchValue() . '%';
        $sql = 'SELECT id_usuario, nombre, correo, fecha_registro
                FROM usuarios
                WHERE nombre LIKE ?
                ORDER BY correo';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO usuarios(nombre, correo, password, fecha_registro)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->correo, $this->password, $this->fecha);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre, correo, fecha_registro
                FROM usuarios
                ORDER BY correo';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre, corre, fecha_registro
                FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE usuarios
                SET nombre = ?, correo = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}