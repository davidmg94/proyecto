<?php
session_start();
require_once '../models/Usuario.php';
require_once '../config/db.php';

class LoginController {
    private $usuarioModel;

    public function __construct($pdo) {
        $this->usuarioModel = new Usuario($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $usuario = $this->usuarioModel->login($email, $password);

            if ($usuario) {
                $_SESSION['usuario'] = $usuario;
                header('Location: ../public/home.php');
                exit;
            } else {
                echo "Credenciales incorrectas.";
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ../public/index.php');
        exit;
    }
}

$controller = new LoginController($pdo);
$controller->login();
?>
