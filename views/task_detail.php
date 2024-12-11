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
$idUsuario = $_SESSION['usuario']['id'];

// Verificar si se recibe un id de tarea
if (isset($_GET['id'])) {
    $idTarea = $_GET['id'];
    // Obtener la tarea por su ID
    $tarea = $tareaModel->getTareaPorId($idTarea, $idUsuario);

    // Si no existe la tarea o no pertenece al usuario, redirigir
    if (!$tarea) {
        header('Location: home.php');
        exit;
    }
} else {
    header('Location: home.php');
    exit;
}

// Función para obtener el texto de la prioridad
function obtenerPrioridadTexto($prioridad) {
    switch ($prioridad) {
        case 1:
            return 'Baja';
        case 2:
            return 'Media';
        case 3:
            return 'Alta';
        default:
            return 'Desconocida';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
    <?php include('../views/header.php'); ?>

        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Gestión de Tareas</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../public/home.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../public/logout.php">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h2>Detalles de la Tarea</h2>

        <!-- Mostrar la información de la tarea -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><?php echo htmlspecialchars($tarea['nombre']); ?></h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo $tarea['id']; ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                <p><strong>Fecha de Vencimiento:</strong>
                    <?php echo date('d-m-Y', strtotime($tarea['fecha_vencimiento'])); ?></p>
                <p><strong>Prioridad:</strong>
                    <?php
                    $prioridad = $tarea['prioridad'];
                    switch ($prioridad) {
                        case 3:
                            echo "<span style='color: white; background-color: red;'>Alta</span>"; // Rojo para alta
                            break;
                        case 2:
                            echo "<span style='color: white; background-color: orange;'>Media</span>"; // Amarillo para media
                            break;
                        case 1:
                            echo "<span style='color: white; background-color: green;'>Baja</span>"; // Verde para baja
                            break;
                        default:
                            echo "<span>Desconocida</span>";
                            break;
                    }
                    ?>
                </p>
                <p><strong>Estado:</strong>
                    <?php
                    if ($tarea['status'] == 1) {
                        echo "<span style='color: orange;'>Pendiente</span>"; // Naranja para pendiente
                    } else {
                        echo "<span style='color: green;'>Completada</span>"; // Verde para completada
                    }
                    ?>
                </p>
            </div>
        </div>

        <!-- Opciones -->
        <div class="d-flex justify-content-start gap-2">

            <a href="../views/edit_task.php?id=<?php echo $tarea['id']; ?>" class="btn btn-warning">Editar</a>

            <!-- Marcar tarea como realizada o pendiente -->
            <?php if ($tarea['status'] == 1): ?>
                <a href="../views/mark_task_done.php?id=<?php echo $tarea['id']; ?>" class="btn btn-success">Marcar como
                    Realizada</a>
            <?php else: ?>
                <a href="../views/mark_task_pending.php?id=<?php echo $tarea['id']; ?>" class="btn btn-secondary">Marcar
                    como Pendiente</a>
            <?php endif; ?>

            <!-- Eliminar tarea -->
            <a href="../views/delete_task.php?id=<?php echo $tarea['id']; ?>" class="btn btn-danger">Eliminar</a>
        </div>
    </div>
    <?php include('../views/footer.php'); ?>

    <script src="../public/js/scripts.js"></script>
</body>

</html>