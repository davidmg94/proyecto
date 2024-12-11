<?php
session_start();
require_once '../controllers/CategoriaController.php';
if ($_SESSION['usuario']['tipo'] !== 'administrador') {
    header('Location: home.php');
    exit;
}
$categoriaController = new CategoriaController($pdo);
$categorias = $categoriaController->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Gestión de Categorías</h2>
    <form action="../controllers/CategoriaController.php" method="POST" class="mb-3">
        <input type="hidden" name="action" value="agregar">
        <div class="input-group">
            <input type="text" name="nombre" class="form-control" placeholder="Nueva categoría" required>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </form>
    <ul class="list-group">
        <?php foreach ($categorias as $categoria): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($categoria['nombre']) ?>
                <a href="../controllers/CategoriaController.php?action=eliminar&id=<?= $categoria['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
