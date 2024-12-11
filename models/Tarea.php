<?php
// Modelo Tarea
require_once 'Model.php';

class Tarea extends Model {

    public function getTareasPorUsuario($usuarioId) {
        $stmt = $this->db->prepare(
            "SELECT t.id, t.nombre, t.descripcion, t.fecha_vencimiento, t.prioridad, t.status, c.nombre AS categoria
            FROM tareas t
            JOIN categorias c ON t.id_categoria = c.id
            WHERE t.id_usuario = ?"
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTareasPorCategoria($categoriaId, $usuarioId) {
        $stmt = $this->db->prepare(
            "SELECT t.id, t.nombre, t.descripcion, t.fecha_vencimiento, t.prioridad, t.status, c.nombre AS categoria
            FROM tareas t
            JOIN categorias c ON t.id_categoria = c.id
            WHERE t.id_usuario = ? AND t.id_categoria = ?"
        );
        $stmt->execute([$usuarioId, $categoriaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Método para obtener una tarea por su ID
    public function getTareaPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM tareas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar una tarea
    public function actualizarTarea($id, $nombre, $descripcion, $categoriaId, $fechaVencimiento, $prioridad) {
        $stmt = $this->db->prepare(
            "UPDATE tareas SET nombre = ?, descripcion = ?, id_categoria = ?, fecha_vencimiento = ?, prioridad = ? WHERE id = ?"
        );
        $stmt->execute([$nombre, $descripcion, $categoriaId, $fechaVencimiento, $prioridad, $id]);
    }

    public function marcarTareaComoCompletada($id) {
        $stmt = $this->db->prepare(
            "UPDATE tareas SET status = 0 WHERE id = ?"
        );
        $stmt->execute([$id]);
    }
    public function eliminarTarea($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM tareas WHERE id = ?"
        );
        $stmt->execute([$id]);
    }



    public function agregarTarea($nombre, $descripcion, $categoriaId, $fechaVencimiento, $prioridad, $usuarioId) {
        $stmt = $this->db->prepare(
            "INSERT INTO tareas (nombre, descripcion, id_categoria, fecha_vencimiento, prioridad, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$nombre, $descripcion, $categoriaId, $fechaVencimiento, $prioridad, $usuarioId]);
    }


    public function marcarTareaComoPendiente($id) {
        $sql = "UPDATE tareas SET status = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    // En el archivo Tarea.php (Modelo)
// Método en Tarea.php para buscar tareas por categoría y término de búsqueda
public function getTareasPorCategoriaYBusqueda($categoriaId, $usuarioId, $search) {
    $sql = "SELECT * FROM tareas WHERE id_usuario = :id_usuario AND id_categoria = :id_categoria AND (nombre LIKE :search OR id LIKE :search)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_usuario', $usuarioId);
    $stmt->bindParam(':id_categoria', $categoriaId);
    $searchTerm = "$search%";
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Método en Tarea.php para buscar tareas por usuario y término de búsqueda
public function buscarTareasPorUsuarioYBusqueda($usuarioId, $search) {
    $sql = "SELECT * FROM tareas WHERE id_usuario = :id_usuario AND (nombre LIKE :search OR id LIKE :search)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_usuario', $usuarioId);
    $searchTerm = "$search%";
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Método en Tarea.php para obtener tareas con filtros de búsqueda, prioridad y estado
public function getTareasConFiltros($categoriaId, $usuarioId, $search, $prioridad, $estado) {
    // Base de la consulta SQL
    $sql = "SELECT * FROM tareas WHERE id_usuario = :id_usuario AND id_categoria = :id_categoria";

    // Añadir búsqueda de texto
    if (!empty($search)) {
        $sql .= " AND (nombre LIKE :search OR id LIKE :search)";
    }

    // Filtrar por prioridad si está definido
    if (!empty($prioridad)) {
        $sql .= " AND prioridad = :prioridad";
    }

    // Filtrar por estado si está definido
    if (!empty($estado)) {
        $sql .= " AND status = :estado";
    }

    // Preparar la consulta
    $stmt = $this->db->prepare($sql);

    // Vincular los parámetros comunes
    $stmt->bindParam(':id_usuario', $usuarioId);
    $stmt->bindParam(':id_categoria', $categoriaId);
    
    // Vincular el parámetro de búsqueda si se proporciona
    if (!empty($search)) {
        $searchTerm = "%" . $search . "%";
        $stmt->bindParam(':search', $searchTerm);
    }

    // Vincular los filtros de prioridad y estado si están definidos
    if (!empty($prioridad)) {
        $stmt->bindParam(':prioridad', $prioridad);
    }
    if (!empty($estado)) {
        $stmt->bindParam(':estado', $estado);
    }

    // Ejecutar la consulta
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Método en Tarea.php para buscar tareas con filtros de búsqueda, prioridad y estado
public function buscarTareasConFiltros($usuarioId, $search, $prioridad, $estado) {
    // Base de la consulta SQL
    $sql = "SELECT * FROM tareas WHERE id_usuario = :id_usuario";

    // Añadir búsqueda de texto
    if (!empty($search)) {
        $sql .= " AND (nombre LIKE :search OR id LIKE :search)";
    }

    // Filtrar por prioridad si está definido
    if (!empty($prioridad)) {
        $sql .= " AND prioridad = :prioridad";
    }

    // Filtrar por estado si está definido
    if (!empty($estado)) {
        $sql .= " AND status = :estado";
    }

    // Preparar la consulta
    $stmt = $this->db->prepare($sql);

    // Vincular los parámetros comunes
    $stmt->bindParam(':id_usuario', $usuarioId);
    
    // Vincular el parámetro de búsqueda si se proporciona
    if (!empty($search)) {
        $searchTerm = "%" . $search . "%";
        $stmt->bindParam(':search', $searchTerm);
    }

    // Vincular los filtros de prioridad y estado si están definidos
    if (!empty($prioridad)) {
        $stmt->bindParam(':prioridad', $prioridad);
    }
    if (!empty($estado)) {
        $stmt->bindParam(':estado', $estado);
    }

    // Ejecutar la consulta
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
?>