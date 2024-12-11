<?php
class Model {
    protected $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }
}
?>
