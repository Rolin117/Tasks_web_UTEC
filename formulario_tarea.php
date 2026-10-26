<?php 
session_start(); 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'helpers/conexion.php';

// Obtener las materias pertenecientes al usuario actual
$materias = [];
try {
    $sql_materias = "SELECT id_materia, nombre FROM materias WHERE id_usuario = :id_usuario";
    $stmt_materias = $conexion->prepare($sql_materias);
    $stmt_materias->execute([':id_usuario' => $_SESSION['usuario_id']]);
    $materias = $stmt_materias->fetchAll();
} catch (PDOException $e) {
    $error_materias = "Error al cargar las materias: " . $e->getMessage();
}

// Obtener las categorías de la base de datos
$categorias = [];
try {
    $sql_categorias = "SELECT id_categoria, nombre FROM categorias";
    $stmt_categorias = $conexion->query($sql_categorias);
    $categorias = $stmt_categorias->fetchAll();
} catch (PDOException $e) {
    $error_categorias = "Error al cargar las categorías: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea - TaskManager</title>
    <link rel="stylesheet" href="css/formularioTarea.css">
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="header">
            <h1>Nueva Tarea</h1>
            <p>Agrega una nueva tarea a tu lista. Sesión iniciada como: <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></p>
        </div>

        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="alert <?php echo $_SESSION['tipo_mensaje']; ?>">
                <?php echo $_SESSION['mensaje']; ?>
            </div>
            <?php 
                unset($_SESSION['mensaje']); 
                unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>

        <?php if(isset($error_materias)): ?>
            <div class="alert error"><?php echo $error_materias; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_categorias)): ?>
            <div class="alert error"><?php echo $error_categorias; ?></div>
        <?php endif; ?>

        <div class="card">
            <form action="insertar_tarea.php" method="POST">
                
                <div class="form-group">
                    <label for="titulo">Título de la tarea *</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Ej. Entregar ensayo de filosofía" required>
                </div>

                <div class="form-group">
                    <label for="fecha_asignada">Fecha de entrega *</label>
                    <input type="date" id="fecha_asignada" name="fecha_asignada" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="id_materia">Materia (Opcional)</label>
                    <select id="id_materia" name="id_materia" class="form-control">
                        <option value="">-- Selecciona una materia --</option>
                        <?php foreach($materias as $materia): ?>
                            <option value="<?php echo $materia['id_materia']; ?>">
                                <?php echo htmlspecialchars($materia['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_categoria">Categoría (Opcional)</label>
                    <select id="id_categoria" name="id_categoria" class="form-control">
                        <option value="">-- Selecciona una categoría --</option>
                        <?php foreach($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Guardar Tarea</button>
            </form>
        </div>
    </div>

</body>
</html>