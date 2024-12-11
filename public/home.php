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
// Obtener las categorías
$categorias = $categoriaModel->getCategoriasPorUsuario($idUsuario);

// Obtener los parámetros de la URL
$categoriaFiltrada = isset($_GET['id_categoria']) ? $_GET['id_categoria'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : '';  // Obtener el término de búsqueda
$prioridad = isset($_GET['prioridad']) ? $_GET['prioridad'] : '';  // Obtener el filtro de prioridad
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';  // Obtener el filtro de estado

// Obtener las tareas según la categoría seleccionada y los filtros
if ($categoriaFiltrada) {
    $tareas = $tareaModel->getTareasConFiltros($categoriaFiltrada, $idUsuario, $search, $prioridad, $estado);
} else {
    $tareas = $tareaModel->buscarTareasConFiltros($idUsuario, $search, $prioridad, $estado);
}

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
    <title>Inicio - Gestión de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Font Awesome -->

    <link href="../public/css/style.css" rel="stylesheet">

</head>

<body>
    <div class="container">
    <?php include('../views/header.php'); ?>

       <!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../views/add_task.php">Añadir Tarea</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../views/add_category.php">Añadir Categoría</a>
                </li>
                <!-- Nuevo enlace para eliminar categorías -->
                <li class="nav-item">
                    <a class="nav-link" href="../views/delete_categories.php">Eliminar Categorías</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
                
            </ul>
        </div>
    </div>
</nav>


        <!-- Pestañas para categorías -->
        <ul class="nav nav-tabs mb-4">
            <!-- Pestaña para todas las tareas -->
            <li class="nav-item">
                <a class="nav-link <?php echo is_null($categoriaFiltrada) ? 'active' : ''; ?>" href="home.php">Todas</a>
            </li>

            <!-- Pestañas para cada categoría -->
            <?php foreach ($categorias as $categoria): ?>
                <li class="nav-item position-relative">
                    <a class="nav-link <?php echo ($categoriaFiltrada == $categoria['id']) ? 'active' : ''; ?>"
                        href="home.php?id_categoria=<?php echo $categoria['id']; ?>">
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </a>
                   
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Formulario de búsqueda -->
        <form action="home.php" method="GET" class="d-flex mb-4">
            <input type="text" name="search" class="form-control" placeholder="Buscar tareas"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

            <input type="hidden" name="id_categoria"
                value="<?php echo isset($_GET['id_categoria']) ? $_GET['id_categoria'] : ''; ?>">
            <!-- Mantener categoría seleccionada -->

            <!-- Filtro por Prioridad -->
            <select name="prioridad" class="form-select ms-2">
                <option value="">Prioridad</option>
                <option value="1" <?php echo isset($_GET['prioridad']) && $_GET['prioridad'] == 1 ? 'selected' : ''; ?>>
                    Baja</option>
                <option value="2" <?php echo isset($_GET['prioridad']) && $_GET['prioridad'] == 2 ? 'selected' : ''; ?>>
                    Media</option>
                <option value="3" <?php echo isset($_GET['prioridad']) && $_GET['prioridad'] == 3 ? 'selected' : ''; ?>>
                    Alta</option>
            </select>

            <!-- Filtro por Estado -->
            <select name="estado" class="form-select ms-2">
                <option value="">Estado</option>
                <option value="1" <?php echo isset($_GET['estado']) && $_GET['estado'] == 1 ? 'selected' : ''; ?>>
                    Pendiente</option>
                <option value="0" <?php echo isset($_GET['estado']) && $_GET['estado'] == 0 ? 'selected' : ''; ?>>
                    Completada</option>
            </select>

            <button type="submit" class="btn btn-primary ms-2">Buscar</button>
        </form>


        <!-- Tabla de tareas -->
        <h2>Tareas de <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></h2>
        <table id="tabla-tareas" class="table table-striped">
            <thead>
                <tr>
                    <th data-tipo="numero">ID</th>
                    <th data-tipo="texto">NOMBRE</th>
                    <th data-tipo="fecha">FECHA DE VENCIMIENTO</th>
                    <th data-tipo="texto">PRIORIDAD</th>
                    <th data-tipo="texto">ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tareas)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay tareas para mostrar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tareas as $tarea): ?>
                        <tr>
                            <td><?php echo $tarea['id']; ?></td>
                            <td><?php echo htmlspecialchars($tarea['nombre']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($tarea['fecha_vencimiento'])); ?></td>
                            <td>
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
                            </td>

                            <td>
                                <?php
                                if ($tarea['status'] == 1) {
                                    echo "<span style='color: orange;'>Pendiente</span>"; // Naranja para pendiente
                                } else {
                                    echo "<span style='color: green;'>Completada</span>"; // Verde para completada
                                }
                                ?>
                            </td>

                            <td>
                                <!-- Detalles -->
                                <a href="../views/task_detail.php?id=<?php echo $tarea['id']; ?>" class="mx-1"
                                    title="Ver Detalles">
                                    <i class="fas fa-file-alt"></i> <!-- Ícono de hoja escrita -->
                                </a>

                                <!-- Editar -->
                                <a href="../views/edit_task.php?id=<?php echo $tarea['id']; ?>" class="mx-1" title="Editar">
                                    <i class="fas fa-edit"></i> <!-- Ícono de editar -->
                                </a>

                                <!-- Marcar como realizada (si está pendiente) -->
                                <?php if ($tarea['status'] == 1): ?>
                                    <a href="../views/mark_task_done.php?id=<?php echo $tarea['id']; ?>" class="mx-1"
                                        title="Marcar como realizada">
                                        <i class="fas fa-check"></i> <!-- Ícono de check -->
                                    </a>
                                <?php else: ?>
                                    <a href="../views/mark_task_pending.php?id=<?php echo $tarea['id']; ?>" class="mx-1"
                                        title="Marcar como pendiente">
                                        <i class="fas fa-times"></i> <!-- Ícono de X -->
                                    </a>
                                <?php endif; ?>

                                <!-- Eliminar -->
                                <a href="../views/delete_task.php?id=<?php echo $tarea['id']; ?>" class="mx-2" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i> <!-- Ícono de cubo de basura -->
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php include('../views/footer.php'); ?>

    </div>

    <!-- Enlace al archivo de JavaScript -->
    <script src="../public/js/scripts.js"></script>

</body>

</html>