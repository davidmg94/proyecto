<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once '../models/Tarea.php';
require_once '../config/db.php';

// Verificar que el ID de la tarea esté presente en la URL
if (isset($_GET['id'])) {
    $tareaId = $_GET['id'];

    // Crear una instancia del modelo Tarea
    $tareaModel = new Tarea($pdo);

    // Eliminar la tarea de la base de datos
    $tareaModel->eliminarTarea($tareaId);

    // Redirigir a la página principal después de eliminar la tarea
    header('Location: ../public/home.php');
    exit;
} else {
    echo "No se ha especificado un ID de tarea.";
    exit;
}
?>
