<?php
session_start();
require_once '../models/Categoria.php';
require_once '../config/db.php';

class CategoriaController {
    private $categoriaModel;

    public function __construct($pdo) {
        $this->categoriaModel = new Categoria($pdo);
    }

    public function listar() {
        return $this->categoriaModel->obtenerTodas();
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $this->categoriaModel->agregar($nombre);
            header('Location: ../public/categorias.php');
            exit;
        }
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->categoriaModel->eliminar($_GET['id']);
            header('Location: ../public/categorias.php');
            exit;
        }
    }
}

$categoriaController = new CategoriaController($pdo);

if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
    $categoriaController->agregar();
} elseif (isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    $categoriaController->eliminar();
}
?>
