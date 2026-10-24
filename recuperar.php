<?php
session_start();
require_once 'helpers/conexion.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $nuevaPassword = trim($_POST['password'] ?? '');

    if (empty($correo) || empty($nuevaPassword)) {
        $error = "Por favor, completa todos los campos.";
    } elseif (strlen($nuevaPassword) < 6) {
        $error = "La nueva contraseña debe tener al menos 6 caracteres.";
    } else {
        try {
            $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo LIMIT 1");
            $stmt->execute([':correo' => $correo]);

            if ($stmt->fetch()) {
                $hash = password_hash($nuevaPassword, PASSWORD_BCRYPT);
                $update = $conexion->prepare("UPDATE usuarios SET password = :password WHERE correo = :correo");
                $update->execute([':password' => $hash, ':correo' => $correo]);

                $exito = "¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.";
            } else {
                $error = "No existe ninguna cuenta vinculada a este correo.";
            }
        } catch (PDOException $e) {
            $error = "Error al procesar la solicitud.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Tasks Manager</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Restablecer Clave</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($exito)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($exito); ?>
            </div>
        <?php endif; ?>

        <form action="recuperar.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Registrado</label>
                <input type="email" name="correo" id="correo" class="form-control" required value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required minlength="6">
            </div>

            <button type="submit" class="btn-primary">Actualizar Contraseña</button>
        </form>

        <hr class="divider">

        <div class="text-center">
            <a href="login.php" class="text-link">Volver al Inicio de Sesión</a>
        </div>
    </div>
</div>

</body>
</html>