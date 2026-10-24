<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit();
}

require_once 'helpers/conexion.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($nombre) || empty($apellido) || empty($correo) || empty($password)) {
        $error = "Por favor, completa todos los campos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingresa un correo electrónico válido.";
    } else {
        try {
            $sqlCheck = "SELECT id_usuario FROM usuarios WHERE correo = :correo LIMIT 1";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->execute([':correo' => $correo]);

            if ($stmtCheck->fetch()) {
                $error = "Este correo ya está registrado. Intenta iniciar sesión.";
            } else {
                $nombreCompleto = $nombre . ' ' . $apellido;

                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                $sqlInsert = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':nombre'   => $nombreCompleto,
                    ':correo'   => $correo,
                    ':password' => $passwordHash
                ]);

                $exito = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión.";
            }
        } catch (PDOException $e) {
            $error = "Ocurrió un error al registrar el usuario.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Tasks Manager</title>

    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Crear Cuenta</h2>

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

        <form action="registro.php" method="POST">
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" required value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required minlength="6">
            </div>

            <button type="submit" class="btn-primary">Registrarse</button>
        </form>

        <hr class="divider">

        <div class="text-center">
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 6px;">¿Ya tienes cuenta?</p>
            <a href="login.php" class="text-link">Iniciar Sesión</a>
        </div>
    </div>
</div>

</body>
</html>