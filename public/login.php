<?php
session_start();
require_once '../models/Usuario.php';
require_once '../config/db.php';

$usuarioModel = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuario = $usuarioModel->login($email, $password);
    if ($usuario) {
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'tipo' => $usuario['tipo']
        ];
        header('Location: home.php');
        exit;
    } else {
        echo "Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Iniciar Sesión</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
    <p class="mt-3">
        ¿No tienes cuenta? <a href="../views/register.php">Regístrate aquí</a>.
    </p>
</div>
<?php include('../views/footer.php'); ?>

</body>
</html>
