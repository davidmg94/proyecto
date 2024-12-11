<?php
require_once 'Model.php';

class Categoria extends Model {
    public function getCategorias() {
        $stmt = $this->db->query("SELECT * FROM categorias");
        return $stmt->fetchAll();
    }
    public function getCategoriasPorUsuario($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id_usuario = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
    public function agregar($nombre) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }


    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function agregarCategoria($usuarioId, $nombreCategoria) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre, id_usuario) VALUES (?, ?)");
        $stmt->execute([$nombreCategoria, $usuarioId]);
    }
    // En el modelo Categoria.php
public function eliminarCategoria($categoriaId,$id_usuario)
{
    $query = "DELETE FROM categorias WHERE id = :id AND id_usuario = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $categoriaId);
    $stmt->bindParam(':id_usuario', $id_usuario); // Usuario autenticado
    return $stmt->execute();
}

    
}
?>
