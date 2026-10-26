<?php

require_once('../../helpers/database.php');

class UsuariosHandler
{
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $password = null;

    // Verificar la contraseña del usuario en sesión.
    public function checkPassword($password)
    {
        $sql = 'SELECT password
                FROM usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['usuario_id']);
        $data = Database::getRow($sql, $params);
        if ($data && password_verify($password, $data['password'])) {
            return true;
        } else {
            return false;
        }
    }

    // Métodos SCRUD
    public function searchRows()
    {
        $value = '%' . Validaciones::getSearchValue() . '%';
        $sql = 'SELECT id_usuario, nombre, apellido, correo, fecha_registro
                FROM usuarios
                WHERE nombre LIKE ? OR apellido LIKE ? OR correo LIKE ?
                ORDER BY correo';
        $params = array($value, $value, $value);
        return Database::getRows($sql, $params);
    }

    // fecha_registro se llena sola con DEFAULT CURRENT_TIMESTAMP.
    public function createRow()
    {
        $sql = 'INSERT INTO usuarios(nombre, apellido, correo, password)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->password);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre, apellido, correo, fecha_registro
                FROM usuarios
                ORDER BY correo';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre, apellido, correo, fecha_registro
                FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE usuarios
                SET nombre = ?, apellido = ?, correo = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->id);
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