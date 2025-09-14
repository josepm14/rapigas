-- Eliminar base de datos si existe
DROP DATABASE IF EXISTS rapigas_db;

-- Crear la base de datos con charset y collation específicos
CREATE DATABASE rapigas_db
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE rapigas_db;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena_hash VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'supervisor', 'personal') NOT NULL,
    estado ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    ultimo_acceso DATETIME NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    creado_por INT UNSIGNED NULL,
    actualizado_por INT UNSIGNED NULL,
    INDEX idx_usuario_rol (rol),
    INDEX idx_usuario_estado (estado)
) ENGINE=InnoDB;

-- Tabla de empleados
CREATE TABLE empleados (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT UNSIGNED NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(8) UNIQUE NOT NULL,
    fecha_nacimiento DATE NULL,
    genero ENUM('M', 'F', 'O') NULL,
    telefono VARCHAR(15) NULL,
    direccion TEXT NULL,
    cargo VARCHAR(50) NOT NULL,
    departamento VARCHAR(50) NOT NULL,
    fecha_contratacion DATE NOT NULL,
    salario DECIMAL(10,2) NULL,
    estado ENUM('activo', 'vacaciones', 'permiso', 'cesado') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_empleado_estado (estado),
    INDEX idx_empleado_departamento (departamento)
) ENGINE=InnoDB;

-- Tabla de asistencias
CREATE TABLE asistencias (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT UNSIGNED NOT NULL,
    hora_entrada DATETIME NOT NULL,
    hora_salida DATETIME NULL,
    ubicacion_entrada VARCHAR(100) NULL,
    ubicacion_salida VARCHAR(100) NULL,
    estado ENUM('puntual', 'tardanza', 'salida_temprana', 'ausente') NOT NULL,
    observaciones TEXT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
    INDEX idx_asistencia_fecha (hora_entrada),
    INDEX idx_asistencia_estado (estado)
) ENGINE=InnoDB;

-- Tabla de permisos y vacaciones
CREATE TABLE permisos (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT UNSIGNED NOT NULL,
    tipo ENUM('vacaciones', 'enfermedad', 'personal', 'otros') NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    motivo TEXT NULL,
    aprobado_por INT UNSIGNED NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
    FOREIGN KEY (aprobado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_permisos_fechas (fecha_inicio, fecha_fin),
    INDEX idx_permisos_estado (estado)
) ENGINE=InnoDB;

-- Tabla de evaluaciones
CREATE TABLE evaluaciones (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT UNSIGNED NOT NULL,
    evaluador_id INT UNSIGNED NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    puntaje_desempeno DECIMAL(3,2) NULL,
    puntaje_asistencia DECIMAL(3,2) NULL,
    puntaje_puntualidad DECIMAL(3,2) NULL,
    comentarios TEXT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_evaluacion_fecha (fecha_evaluacion)
) ENGINE=InnoDB;

-- Tabla de notificaciones
CREATE TABLE notificaciones (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    tipo ENUM('informacion', 'advertencia', 'error', 'exito') NOT NULL,
    leido_en DATETIME NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_notificaciones_usuario (usuario_id, leido_en),
    INDEX idx_notificaciones_tipo (tipo)
) ENGINE=InnoDB;