<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'helpers/conexion.php';

$id_usuario = $_SESSION['usuario_id'];
$mensaje = '';
$tipo_mensaje = '';

// Procesar el CRUD si se envía un formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];
    $nombre = trim($_POST['nombre'] ?? '');
    $id_materia = $_POST['id_materia'] ?? null;
    $color_hex = '#CCCCCC'; // Color por defecto según estructura

    try {
        if ($accion == 'crear' && !empty($nombre)) {
            $sql = "INSERT INTO materias (id_usuario, nombre, color_hex) VALUES (:id_usuario, :nombre, :color_hex)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':nombre' => $nombre,
                ':color_hex' => $color_hex
            ]);
            $mensaje = "Materia agregada exitosamente.";
            $tipo_mensaje = "success";
            
        } elseif ($accion == 'editar' && !empty($nombre) && !empty($id_materia)) {
            $sql = "UPDATE materias SET nombre = :nombre WHERE id_materia = :id_materia AND id_usuario = :id_usuario";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':id_materia' => $id_materia,
                ':id_usuario' => $id_usuario
            ]);
            $mensaje = "Materia actualizada exitosamente.";
            $tipo_mensaje = "success";
            
        } elseif ($accion == 'eliminar' && !empty($id_materia)) {
            $sql = "DELETE FROM materias WHERE id_materia = :id_materia AND id_usuario = :id_usuario";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':id_materia' => $id_materia,
                ':id_usuario' => $id_usuario
            ]);
            $mensaje = "Materia eliminada exitosamente.";
            $tipo_mensaje = "success";
        }
    } catch (PDOException $e) {
        $mensaje = "Error en la base de datos: " . $e->getMessage();
        $tipo_mensaje = "error";
    }
}

// Obtener todas las materias del usuario logueado para llenar la tabla
$materias = [];
try {
    $sql_select = "SELECT id_materia, nombre FROM materias WHERE id_usuario = :id_usuario ORDER BY id_materia DESC";
    $stmt_select = $conexion->prepare($sql_select);
    $stmt_select->execute([':id_usuario' => $id_usuario]);
    $materias = $stmt_select->fetchAll();
} catch (PDOException $e) {
    $mensaje = "Error al cargar las materias: " . $e->getMessage();
    $tipo_mensaje = "error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias - TaskManager</title>
    <link rel="stylesheet" href="css/formularioTarea.css">
    <link rel="stylesheet" href="css/materias.css">
</head>
<body>

    <!-- Menú lateral -->
    <?php include './includes/sidebar.php'; ?>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="header">
            <h1>Materias</h1>
            <p>Gestiona tus materias. Sesión iniciada como: <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></p>
        </div>

        <?php if($mensaje): ?>
            <div class="alert <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Tarjeta del Formulario (Crear/Editar) -->
        <div class="card" style="max-width: 100%;">
            <form action="materias.php" method="POST" id="formMateria" class="form-inline">
                <!-- Inputs ocultos para controlar la acción del CRUD -->
                <input type="hidden" name="accion" id="accion" value="crear">
                <input type="hidden" name="id_materia" id="id_materia" value="">
                
                <div class="form-group">
                    <label for="nombre">Nombre de la materia</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej. Matemáticas" required>
                </div>

                <div class="form-group" style="flex-grow: 0; align-self: flex-end;">
                    <button type="submit" id="btnSubmit" class="btn-submit">Insertar</button>
                    <button type="button" id="btnCancelar" class="btn-action btn-edit" style="display:none; padding: 12px 15px; margin-left: 5px;">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Tarjeta de la Tabla -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre de materia</th>
                        <th style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($materias) > 0): ?>
                        <?php foreach($materias as $materia): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($materia['nombre']); ?></td>
                                <td>
                                    <!-- Botón Editar que llama a función JS -->
                                    <button onclick="editarMateria(<?php echo $materia['id_materia']; ?>, '<?php echo htmlspecialchars(addslashes($materia['nombre'])); ?>')" class="btn-action btn-edit">Editar</button>
                                    
                                    <!-- Formulario en línea para eliminar -->
                                    <form action="materias.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta materia?');">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_materia" value="<?php echo $materia['id_materia']; ?>">
                                        <button type="submit" class="btn-action btn-delete">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" style="text-align: center; color: #6b7280;">No hay materias registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script para manejar la edición en el mismo formulario -->
    <script>
        function editarMateria(id, nombre) {
            // Cambiar los valores del formulario superior
            document.getElementById('accion').value = 'editar';
            document.getElementById('id_materia').value = id;
            document.getElementById('nombre').value = nombre;
            
            // Cambiar el estilo del botón
            document.getElementById('btnSubmit').innerText = 'Actualizar';
            document.getElementById('btnCancelar').style.display = 'inline-block';
            
            // Hacer scroll hacia arriba
            window.scrollTo({ top: 0, behavior: 'smooth' });
            document.getElementById('nombre').focus();
        }

        // Función para cancelar la edición y volver a modo "Crear"
        document.getElementById('btnCancelar').addEventListener('click', function() {
            document.getElementById('accion').value = 'crear';
            document.getElementById('id_materia').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('btnSubmit').innerText = 'Insertar';
            this.style.display = 'none';
        });
    </script>
</body>
</html>