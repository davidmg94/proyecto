<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once '../models/Categoria.php';
require_once '../config/db.php';
$idUsuario = $_SESSION['usuario']['id'];
// Verificar que se haya enviado el ID de la categoría
if (isset($_POST['categoria_id'])) {
    $categoriaId = $_POST['categoria_id'];

    // Crear una instancia del modelo de Categoría
    $categoriaModel = new Categoria($pdo);

    // Eliminar la categoría
    $categoriaModel->eliminarCategoria($categoriaId, $idUsuario);

    // Redirigir de vuelta a la página principal
    header('Location: ../public/home.php');
    exit;
} else {
    echo "No se proporcionó un ID de categoría.";
    exit;
}
?>
