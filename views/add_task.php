<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once '../models/Tarea.php';
require_once '../models/Categoria.php';
require_once '../config/db.php';

$tareaModel = new Tarea($pdo);
$categoriaModel = new Categoria($pdo);

// Obtener las categorías del usuario
$categorias = $categoriaModel->getCategoriasPorUsuario($_SESSION['usuario']['id']); // Filtrar por ID del usuario

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $prioridad = $_POST['prioridad'];

    // Crear la nueva tarea en la base de datos
    $tareaModel->agregarTarea($titulo, $descripcion, $categoria_id, $fecha_vencimiento, $prioridad, $_SESSION['usuario']['id']);

    // Redirigir al usuario a la página principal después de agregar la tarea
    header('Location: ../public/home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<?php include('../views/header.php'); ?>

    <h2>Añadir Nueva Tarea</h2>
    <form action="add_task.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Título</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-control" required>
                <option value="">Seleccionar categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select name="prioridad" id="prioridad" class="form-control" required>
                <option value="1">Baja</option>
                <option value="2">Media</option>
                <option value="3">Alta</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Añadir Tarea</button>
    </form>        <?php include('../views/footer.php'); ?>

</div>
</body>
</html>
