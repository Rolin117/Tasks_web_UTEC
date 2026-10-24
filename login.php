<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit();
}

require_once 'helpers/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($correo) || empty($password)) {
        $error = "Por favor, ingresa tu correo y contraseña.";
    } else {
        try {
            $sql = "SELECT id_usuario, nombre, correo, password FROM usuarios WHERE correo = :correo LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':correo' => $correo]);
            $user = $stmt->fetch();

            if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $user['id_usuario'];
                $_SESSION['usuario_nombre'] = $user['nombre'];

                header("Location: index.html");
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error = "Ocurrió un error en el servidor.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Tasks Manager</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>


<div class="auth-container">
    
    <div class="auth-card">
        <h2 class="auth-title">Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <div class="text-right">
                    <a href="recuperar.php" class="text-link">¿Olvidaste tu contraseña?</a>
                </div>
            </div>

            <button type="submit" class="btn-primary">Ingresar</button>
        </form>

        <hr class="divider">

        <div class="text-center">
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">¿No tienes una cuenta?</p>
            <a href="registro.php" class="btn-outline">Crear nueva cuenta</a>
        </div>
    </div>
</div>

</body>
</html>