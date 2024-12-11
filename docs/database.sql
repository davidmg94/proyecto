-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('usuario', 'administrador') NOT NULL
);

-- Tabla de categorías con restricción ON DELETE CASCADE
CREATE TABLE IF NOT EXISTS `categorias` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de tareas con restricción ON DELETE CASCADE
CREATE TABLE IF NOT EXISTS `tareas` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_vencimiento DATE,
    prioridad INT,
    id_usuario INT,
    id_categoria INT,
    status TINYINT(1) NOT NULL DEFAULT 1,  -- Nueva columna status
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE CASCADE
);

-- Insertar datos de ejemplo en la tabla usuarios
INSERT INTO usuarios (nombre, email, password, tipo) VALUES
('Administrador 1', 'admin1@example.com', SHA2('admin123', 256), 'administrador'),
('Usuario 1', 'user1@example.com', SHA2('user123', 256), 'usuario'),
('Usuario 2', 'user2@example.com', SHA2('user456', 256), 'usuario');

-- Insertar datos de ejemplo en la tabla categorias
INSERT INTO categorias (nombre, id_usuario) VALUES
('Trabajo', 1),
('Personal', 1),
('Estudio', 1),
('Hogar', 1),
('Trabajo', 2),
('Personal', 2),
('Estudio', 2),
('Hogar', 2),
('Trabajo', 3),
('Personal', 3),
('Estudio', 3),
('Hogar', 3);
-- Insertar todas las categorías para cada usuario existente
-- INSERT INTO categorias (nombre, id_usuario)
-- SELECT 'Trabajo', id FROM usuarios
-- UNION ALL
-- SELECT 'Personal', id FROM usuarios
-- UNION ALL
-- SELECT 'Estudio', id FROM usuarios
-- UNION ALL
-- SELECT 'Hogar', id FROM usuarios;

-- Insertar datos de ejemplo en la tabla tareas
INSERT INTO tareas (nombre, descripcion, fecha_vencimiento,prioridad, id_usuario, id_categoria, status) VALUES
('Completar reporte mensual', 'Preparar y enviar el reporte mensual para la reunión de equipo.', '2024-12-15',1, 2, 5, 1),
('Comprar materiales de oficina', 'Adquirir papel, bolígrafos y carpetas para la oficina.', '2024-12-10', 1,2, 5, 1),
('Estudiar para el examen', 'Revisar capítulos 4, 5 y 6 del libro de matemáticas.', '2024-12-20', 1,1, 3, 1),
('Limpiar la cocina', 'Hacer una limpieza profunda de la cocina.', '2024-12-12', 1,1, 2, 1),
('Enviar facturas', 'Revisar y enviar todas las facturas pendientes.', '2024-12-08', 1,3, 7, 1);
