CREATE DATABASE db_restaurante;

USE db_restaurante;

CREATE TABLE tbl_camarero (
    id_camarero INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name_camarero VARCHAR(30) NOT NULL,
    surname_camarero VARCHAR(30) NOT NULL,
    username_camarero VARCHAR(30) NOT NULL,
    pwd_camarero CHAR(64) NOT NULL
);

CREATE TABLE tbl_salas (
    id_salas INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name_sala VARCHAR(15) NOT NULL,
    tipo_sala ENUM("Comedor", "Terraza", "VIP") NOT NULL
);

CREATE TABLE tbl_mesas (
    id_mesa INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    n_asientos INT NOT NULL,
    id_sala INT NOT NULL
);

CREATE TABLE tbl_historial(
    id_historial INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha_A datetime NOT NULL,
    fecha_NA datetime NULL,
    assigned_by INT NOT NULL,
    assigned_to VARCHAR(30) NOT NULL,
    id_mesa INT NOT NULL
);

ALTER TABLE tbl_mesas ADD CONSTRAINT fk_sala_mesa FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_salas);
ALTER TABLE tbl_historial ADD CONSTRAINT fk_camarero_asignado FOREIGN KEY (assigned_by) REFERENCES tbl_camarero(id_camarero);
ALTER TABLE tbl_historial ADD CONSTRAINT fk_mesa_historial FOREIGN KEY (id_mesa) REFERENCES tbl_mesas(id_mesa);

INSERT INTO tbl_camarero (name_camarero, surname_camarero, username_camarero, pwd_camarero)
VALUES
('Carlos', 'García', 'cgarcia', SHA2('Camarero123.', 256)),
('Laura', 'Martínez', 'lmartinez', SHA2('Camarero123.', 256)),
('Ana', 'Sánchez', 'asanchez', SHA2('Camarero123.', 256)),
('Jorge', 'Hernández', 'jhernandez', SHA2('Camarero123.', 256)),
('Elena', 'López', 'elopez', SHA2('Camarero123.', 256));

-- Inserciones en la tabla tbl_salas
INSERT INTO tbl_salas (name_sala, tipo_sala)
VALUES
('Comedor_1', 'Comedor'),
('Terraza_1', 'Terraza'),
('Salon_VIP', 'VIP'),
('Comedor_2', 'Comedor'),
('Jardin', 'Terraza'),
('Terraza_2', 'Terraza'),
('Salon_VIP_2', 'VIP'),
('Salon_romantico', 'VIP'),
('Naturaleza', 'VIP');

-- Inserciones en la tabla tbl_mesas
INSERT INTO tbl_mesas (n_asientos, id_sala) VALUES

-- Comedor_1 
(6, 1),
(6, 1),
(4, 1),
(4, 1),

-- Comedor_2 
(6, 4),
(6, 4),
(4, 4),
(4, 4),

-- Naturaleza 
(2, 9),
(2, 9),
(2, 9),

-- Salon_VIP 
(2, 3),
(2, 3),
(2, 3),

-- Salon_VIP_2 
(2, 7),
(2, 7),
(2, 7),

-- Salon_romantico 
(2, 8),

-- Terraza_1 
(2, 2),
(4, 2),
(4, 2),

-- Terraza_2 
(6, 6),
(4, 6),

-- Jardin 
(4, 5),
(4, 5),
(2, 5),
(2, 5),
(6, 5);

INSERT INTO tbl_historial (fecha_A, fecha_NA, assigned_by, assigned_to, id_mesa) 
VALUES 
(NOW(), NULL, 1, 'Hugo', 1),
(NOW(), NULL, 5, 'Alex', 2),
(NOW(), NULL, 2, 'Erik', 3),
(NOW(), NULL, 4, 'Ming', 4),
(NOW(), NOW(), 3, 'Dylan', 4);