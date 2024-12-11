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

    $tareaModel = new Tarea($pdo);

    // Marcar la tarea como pendiente (status = 1 para pendiente)
    $tareaModel->marcarTareaComoPendiente($tareaId);

    // Redirigir a la página principal después de actualizar el estado de la tarea
    header('Location: ../public/home.php');
    exit;
} else {
    echo "No se ha especificado un ID de tarea.";
    exit;
}
?>
