CREATE DATABASE IF NOT EXISTS aseos_DB;
USE aseos_DB;

-- ======================================================
-- 1. CREACIÓN DE TABLAS (Con Timestamps para Laravel)
-- ======================================================

-- Tabla AULA
CREATE TABLE aula (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre varchar(100) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabla PROFESOR
CREATE TABLE profesor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre varchar(50),
    apellidos varchar(100),
    email varchar(100) UNIQUE,
    password varchar(100),
    rol varchar(20) DEFAULT 'profesor',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabla ALUMNO
CREATE TABLE alumno (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nre INT UNIQUE,
    nombre varchar(50),
    apellidos varchar(100),
    profesor_id int,
    aula_id int,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabla REGISTRO
CREATE TABLE registro (
    id INT PRIMARY KEY AUTO_INCREMENT,
    alumno_id int,
    profesor_id int,
    fecha_salida datetime,
    fecha_entrada datetime NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE curso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    
);
-- ======================================================
-- 2. DEFINICIÓN DE FOREIGN KEYS
-- ======================================================

ALTER TABLE alumno 
ADD CONSTRAINT fk_alumno_aula FOREIGN KEY (aula_id) REFERENCES aula(id) ON DELETE CASCADE;

ALTER TABLE alumno 
ADD CONSTRAINT fk_alumno_profesor FOREIGN KEY (profesor_id) REFERENCES profesor(id) ON DELETE SET NULL;

ALTER TABLE registro 
ADD CONSTRAINT fk_registro_alumno FOREIGN KEY (alumno_id) REFERENCES alumno(id) ON DELETE CASCADE;

ALTER TABLE registro 
ADD CONSTRAINT fk_registro_profesor FOREIGN KEY (profesor_id) REFERENCES profesor(id) ON DELETE CASCADE;