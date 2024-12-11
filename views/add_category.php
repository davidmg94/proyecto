<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once '../models/Categoria.php';
require_once '../config/db.php';

$categoriaModel = new Categoria($pdo);

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el nombre de la categoría
    $nombreCategoria = $_POST['nombre_categoria'];

    // Insertar la nueva categoría en la base de datos
    $categoriaModel->agregarCategoria($_SESSION['usuario']['id'], $nombreCategoria);

    // Redirigir al usuario a la página de inicio después de agregar la categoría
    header('Location: ../public/home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<?php include('../views/header.php'); ?>

    <h2>Agregar Nueva Categoría</h2>
    <form action="add_category.php" method="POST">
        <div class="mb-3">
            <label for="nombre_categoria" class="form-label">Nombre de la Categoría</label>
            <input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Categoría</button>
    </form>
    <br>
    <a href="home.php" class="btn btn-secondary">Volver a la Página Principal</a>
</div>        <?php include('../views/footer.php'); ?>

</body>
</html>
