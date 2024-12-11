<?php
require_once 'Model.php';

class Usuario extends Model {
    public function login($email, $password) {
        $hashedPassword = hash('sha256', $password); // Cifra la contraseña ingresada con SHA-256
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
        $stmt->execute([$email, $hashedPassword]);
        return $stmt->fetch();
    }

    public function registrar($nombre, $email, $password, $tipo) {
        $hashedPassword = hash('sha256', $password); // Cifra la contraseña con SHA-256 al registrar
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password, tipo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $email, $hashedPassword, $tipo]);
    }
}
?>
