<?php
session_start();
require 'helpers/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo']);
    $fecha_asignada = $_POST['fecha_asignada'];
    
    // Extraemos el usuario directamente de la sesión iniciada
    $id_usuario = $_SESSION['usuario_id'];
    
    // Manejar campos que aceptan NULL
    $id_materia = !empty($_POST['id_materia']) ? $_POST['id_materia'] : null;
    $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null;

    if (!empty($titulo) && !empty($fecha_asignada) && !empty($id_usuario)) {
        try {
            $sql = "INSERT INTO tareas (id_usuario, id_materia, id_categoria, titulo, fecha_asignada) 
                    VALUES (:id_usuario, :id_materia, :id_categoria, :titulo, :fecha_asignada)";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_materia' => $id_materia,
                ':id_categoria' => $id_categoria,
                ':titulo' => $titulo,
                ':fecha_asignada' => $fecha_asignada
            ]);
            
            $_SESSION['mensaje'] = "¡Tarea agregada exitosamente!";
            $_SESSION['tipo_mensaje'] = "success";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "Error al guardar: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "Por favor, completa los campos obligatorios.";
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    // Redirigir de vuelta al formulario
    header("Location: formulario_tarea.php");
    exit();
} else {
    header("Location: formulario_tarea.php");
    exit();
}
?>