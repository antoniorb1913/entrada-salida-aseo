CREATE DATABASE aseos_DB;

USE aseos_DB;

CREATE TABLE alumno (
    NRE INT PRIMARY KEY AUTO_INCREMENT,
    nombre varchar(50),
    apellidos varchar(100),
    curso varchar(5),
    profesor_id int,
    aula_id int,
    FOREIGN KEY alumno(profesor_id) REFERENCES profesor(id)
    FOREIGN KEY alumno(aula_id) REFERENCES aula(id)

);

CREATE TABLE profesor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre varchar(50),
    apellidos varchar(100),
    curso varchar(5),
    pass varchar(100),
    rol varchar(20)
);


CREATE TABLE aula (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre varchar(100),
    alumno_id int,
    profesor_id int,
    FOREIGN KEY aula(alumno_id) REFERENCES alumno(id),
    FOREIGN KEY aula(profesor_id) REFERENCES profesor(id)

);

CREATE TABLE registro (
    id INT PRIMARY KEY AUTO_INCREMENT,
    alumno_id int,
    profesor_id int,
    fecha_entrada datetime,
    fecha_salida datetime,
    FOREIGN KEY registro(alumno_id) REFERENCES alumno(id),
    FOREIGN KEY registro(profesor_id) REFERENCES profesor(id)
);