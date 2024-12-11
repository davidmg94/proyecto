<?php
session_start();
require_once '../models/Usuario.php';
require_once '../config/db.php';

$usuarioModel = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tipo = 'usuario'; // Por defecto, los nuevos usuarios serán "usuario"

    if ($usuarioModel->registrar($nombre, $email, $password, $tipo)) {
        echo "Registro exitoso. <a href='../public/login.php'>Inicia sesión</a>";
    } else {
        echo "Error al registrar usuario.";
    }
    $usuarioId = $pdo->lastInsertId();

    agregarCategoriasPredeterminadas($usuarioId, $pdo);


}
// Función para agregar categorías predeterminadas
function agregarCategoriasPredeterminadas($usuarioId, $pdo) {
    $categorias = ['Trabajo', 'Hogar', 'Estudios', 'Personal'];
    
    $stmt = $pdo->prepare("INSERT INTO categorias (nombre, id_usuario) VALUES (?, ?)");
    
    foreach ($categorias as $categoria) {
        $stmt->execute([$categoria, $usuarioId]);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registro de Usuario</h2>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>        <?php include('../views/footer.php'); ?>

</div>
</body>
</html>
