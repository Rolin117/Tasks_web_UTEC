<?php

require_once('../../helpers/conexion.php');

class TareasHandler
{
    protected $id = null;
    protected $materia = null;
    protected $categoria = null;
    protected $titulo = null;
    protected $completada = null;
    protected $fecha_asignada = null;

    // Métodos SCRUD

    public function searchRows()
    {
        $value = '%' . Validaciones::getSearchValue() . '%';
        $sql = 'SELECT t.id_tarea, t.titulo, t.completada, t.fecha_asignada,
                       m.nombre AS materia, m.color_hex, c.nombre AS categoria
                FROM tareas t
                LEFT JOIN materias m ON t.id_materia = m.id_materia
                LEFT JOIN categorias c ON t.id_categoria = c.id_categoria
                WHERE t.id_usuario = ? AND t.titulo LIKE ?
                ORDER BY t.fecha_asignada, t.titulo';
        $params = array($_SESSION['id_usuario'], $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tareas(id_usuario, id_materia, id_categoria, titulo, fecha_asignada)
                VALUES(?, ?, ?, ?, ?)';
        $params = array(
            $_SESSION['id_usuario'],
            $this->materia,
            $this->categoria,
            $this->titulo,
            $this->fecha_asignada
        );
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT t.id_tarea, t.titulo, t.completada, t.fecha_asignada,
                       m.nombre AS materia, m.color_hex, c.nombre AS categoria
                FROM tareas t
                LEFT JOIN materias m ON t.id_materia = m.id_materia
                LEFT JOIN categorias c ON t.id_categoria = c.id_categoria
                WHERE t.id_usuario = ?
                ORDER BY t.fecha_asignada, t.titulo';
        $params = array($_SESSION['id_usuario']);
        return Database::getRows($sql, $params);
    }

    // Tareas de un día específico (útil para la vista de calendario/agenda).
    public function readByDate()
    {
        $sql = 'SELECT t.id_tarea, t.titulo, t.completada, t.fecha_asignada,
                       m.nombre AS materia, m.color_hex, c.nombre AS categoria
                FROM tareas t
                LEFT JOIN materias m ON t.id_materia = m.id_materia
                LEFT JOIN categorias c ON t.id_categoria = c.id_categoria
                WHERE t.id_usuario = ? AND t.fecha_asignada = ?
                ORDER BY t.completada, t.titulo';
        $params = array($_SESSION['id_usuario'], $this->fecha_asignada);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_tarea, id_materia, id_categoria, titulo, completada, fecha_asignada
                FROM tareas
                WHERE id_tarea = ? AND id_usuario = ?';
        $params = array($this->id, $_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tareas
                SET id_materia = ?, id_categoria = ?, titulo = ?, fecha_asignada = ?
                WHERE id_tarea = ? AND id_usuario = ?';
        $params = array(
            $this->materia,
            $this->categoria,
            $this->titulo,
            $this->fecha_asignada,
            $this->id,
            $_SESSION['id_usuario']
        );
        return Database::executeRow($sql, $params);
    }

    // Marcar o desmarcar una tarea como completada.
    public function changeStatus()
    {
        $sql = 'UPDATE tareas
                SET completada = ?
                WHERE id_tarea = ? AND id_usuario = ?';
        $params = array($this->completada, $this->id, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tareas
                WHERE id_tarea = ? AND id_usuario = ?';
        $params = array($this->id, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }
}