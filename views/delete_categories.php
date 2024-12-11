<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once '../models/Categoria.php';
require_once '../config/db.php';

$categoriaModel = new Categoria($pdo);
$idUsuario = $_SESSION['usuario']['id'];

// Obtener las categorías del usuario
$categorias = $categoriaModel->getCategoriasPorUsuario($idUsuario);

// Filtrar las categorías según el término de búsqueda
$searchTerm = '';
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $searchTerm = trim($_GET['buscar']);
    $categorias = array_filter($categorias, function ($categoria) use ($searchTerm) {
        return stripos($categoria['nombre'], $searchTerm) !== false;
    });
}

// Verificar si se ha enviado un formulario para eliminar categorías
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_categorias'])) {
    if (isset($_POST['categorias'])) {
        foreach ($_POST['categorias'] as $categoriaId) {
            // Eliminar la categoría seleccionada
            $categoriaModel->eliminarCategoria($categoriaId, $idUsuario);
        }
        header('Location: delete_categories.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
    <?php include('../views/header.php'); ?>

        <h2>Eliminar Categorías</h2>

        <!-- Formulario de búsqueda -->
        <form action="delete_categories.php" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar categorías..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <form action="delete_categories.php" method="POST">
            <div class="list-group">
                <?php if (!empty($categorias)): ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="list-group-item">
                            <input type="checkbox" name="categorias[]" value="<?php echo $categoria['id']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No se encontraron categorías.</p>
                <?php endif; ?>
            </div>

            <div class="mt-3">
                <button type="submit" name="eliminar_categorias" class="btn btn-danger">Eliminar Seleccionadas</button>
                <a href="../public/home.php" class="btn btn-secondary">Volver a Inicio</a>
            </div>
        </form>
        
    </div>
    <?php include('../views/footer.php'); ?>

    <script src="../public/js/scripts.js"></script>
</body>

</html>
