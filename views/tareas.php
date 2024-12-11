<?php
session_start();
require_once '../controllers/TareaController.php';
require_once '../controllers/CategoriaController.php';

$tareaController = new TareaController($pdo);
$categoriaController = new CategoriaController($pdo);

$tareas = $tareaController->listar();
$categorias = $categoriaController->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Gestión de Tareas</h2>
    <form action="../controllers/TareaController.php" method="POST" class="mb-3">
        <input type="hidden" name="action" value="agregar">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
            <input type="date" name="fecha_vencimiento" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select name="id_categoria" class="form-select" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Tarea</button>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Vencimiento</th>
                <th>Categoría</th>
                <?php if ($_SESSION['usuario']['tipo'] === 'administrador'): ?>
                    <th>Usuario</th>
                <?php endif; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tareas as $tarea): ?>
                <tr>
                    <td><?= htmlspecialchars($tarea['titulo']) ?></td>
                    <td><?= htmlspecialchars($tarea['descripcion']) ?></td>
                    <td><?= htmlspecialchars($tarea['fecha_vencimiento']) ?></td>
                    <td><?= htmlspecialchars($tarea['categoria']) ?></td>
                    <?php if ($_SESSION['usuario']['tipo'] === 'administrador'): ?>
                        <td><?= htmlspecialchars($tarea['usuario']) ?></td>
                    <?php endif; ?>
                    <td>
                        <a href="../controllers/TareaController.php?action=eliminar&id=<?= $tarea['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
