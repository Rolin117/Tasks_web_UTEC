<?php

require_once('../../helpers/conexion.php');

class UsuariosHandler 
{

protected $id = null;
protected $nombre = null;
protected $correo = null;
protected $password = null;
protected $fecha = null;



    //   public function checkPassword($password)
    // {
    //     $sql = 'SELECT password
    //             FROM usuarios
    //             WHERE id_usuario = ?';
    //     $params = array($_SESSION['id_usuario']);
    //     $data = Database::getRow($sql, $params);
    //     if (password_verify($password, $data['password'])) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}