<?php
session_start();
require_once '../models/Tarea.php';
require_once '../config/db.php';

class TareaController {
    private $tareaModel;

    public function __construct($pdo) {
        $this->tareaModel = new Tarea($pdo);
    }

    public function listar() {
        if ($_SESSION['usuario']['tipo'] === 'administrador') {
            return $this->tareaModel->obtenerTodas();
        } else {
            return $this->tareaModel->obtenerPorUsuario($_SESSION['usuario']['id']);
        }
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fechaVencimiento = $_POST['fecha_vencimiento'];
            $idUsuario = $_SESSION['usuario']['id'];
            $idCategoria = $_POST['id_categoria'];

            $this->tareaModel->agregar($titulo, $descripcion, $fechaVencimiento, $idUsuario, $idCategoria);
            header('Location: ../public/tareas.php');
            exit;
        }
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->tareaModel->eliminar($_GET['id']);
            header('Location: ../public/tareas.php');
            exit;
        }
    }
}

$tareaController = new TareaController($pdo);

if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
    $tareaController->agregar();
} elseif (isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    $tareaController->eliminar();
}
?>
