DROP DATABASE IF exists ola_ke_hace;
CREATE DATABASE IF NOT EXISTS ola_ke_hace;

USE ola_ke_hace;

-- CREACION DE TABLA ROL
CREATE TABLE IF NOT EXISTS rol(
    id_rol   INTEGER PRIMARY KEY AUTO_INCREMENT,
    tipo  VARCHAR(30) NOT NULL
);

-- CREACION DE TABLA PAIS
CREATE TABLE IF NOT EXISTS pais(
    id_pais  INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre  VARCHAR(25) NOT NULL
);

-- CRACION DE TABLA TIPO DE PUBLICACION
CREATE TABLE IF NOT EXISTS tipo_publico(
    idpublico   INTEGER PRIMARY KEY AUTO_INCREMENT,
    tipo_publico  VARCHAR(45) NOT NULL
);

-- CREACION DE TABLA CATEGORIA PUBLICACION
CREATE TABLE IF NOT EXISTS categoria_publicacion(
    id   INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre_categoria  VARCHAR(45) NOT NULL
);

-- CREACION DE TABLA USUARIO
CREATE TABLE IF NOT EXISTS usuario(
    id_usuario   INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre  VARCHAR(45) NOT NULL,
    username  VARCHAR(30) NOT NULL,
    pass VARCHAR(45) NOT NULL,
    correo  VARCHAR(45) NULL,
    id_pais INTEGER NOT NULL,
    id_rol INTEGER NOT NULL,
    FOREIGN KEY (id_pais) REFERENCES pais(id_pais),
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

-- CREACION DE TABLA PUBLICACION
CREATE TABLE IF NOT EXISTS publicacion(
    id_publicacion   INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre_publicacion  VARCHAR(45) NOT NULL,
    estado boolean NOT NULL,
    descripcion VARCHAR(100) NULL,
    fecha DATE NULL,
    id_categoria INTEGER NOT NULL,
    ubicacion VARCHAR(100) NULL,
    hora VARCHAR(50) NULL,
    id_tipo_publico  INTEGER NOT NULL,
    limite_personas  INTEGER NULL,
    FOREIGN KEY (id_categoria) REFERENCES categoria_publicacion(id),
    FOREIGN KEY (id_tipo_publico) REFERENCES tipo_publico(idpublico)
);

-- CREACION DE TABLA NOTIFICACION
CREATE TABLE IF NOT EXISTS solicitud_notificacion(
    id_solicitud_notificacion   INTEGER PRIMARY KEY AUTO_INCREMENT,
    mensaje  VARCHAR(100) NULL,
    fecha DATE NULL,
    id_usuario INTEGER NOT NULL,
    id_publicacion INTEGER NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_publicacion) REFERENCES publicacion(id_publicacion)
);

-- CREACION DE TABLA REPORTE PUBLICACION 
CREATE TABLE IF NOT EXISTS reporte_publicacion(
id_reporte_publicacion  INTEGER PRIMARY KEY AUTO_INCREMENT,
motivo VARCHAR(100) NOT NULL,
id_publicacion  INTEGER NOT NULL,
FOREIGN KEY (id_publicacion) REFERENCES publicacion(id_publicacion)
);


-- CREACION DE TABLA REPORTE NOTIFICACION
CREATE TABLE IF NOT EXISTS reporte_notificacion(
    id_reporte_notificacion INTEGER PRIMARY KEY,
    mensaje VARCHAR(100) NOT NULL,
    fecha DATE NULL,
    id_usuario INTEGER NOT NULL,
    id_reporte_publicacion INTEGER NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_reporte_publicacion) REFERENCES reporte_publicacion(id_reporte_publicacion)
);

-- CREACION DE TABLA RESERVACION EVENTO
CREATE TABLE IF NOT EXISTS reservacion_evento(
id_reservacion_e  INTEGER PRIMARY KEY AUTO_INCREMENT,
activo boolean NOT NULL,
id_usuario  INTEGER NOT NULL,
id_publicacion  INTEGER NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
FOREIGN KEY (id_publicacion) REFERENCES publicacion(id_publicacion)
);


---INSERCIONES 
SELECT *FROM tipo_publico;



-- CRACION DE TABLA TIPO DE PUBLICACION
CREATE TABLE IF NOT EXISTS tipo_publico(
    idpublico   INTEGER PRIMARY KEY AUTO_INCREMENT,
    tipo_publico  VARCHAR(45) NOT NULL
);


INSERT INTO tipo_publico (tipo_publico)
VALUES
    ('Infantil'),
    ('Juvenil'),
    ('Adulto');
    
SELECT * FROM categoria_publicacion;

INSERT INTO categoria_publicacion(nombre_categoria)
VALUES
('Ciencia'),
('Historia'),
('Arte');

SELECT * FROM rol;
INSERT INTO rol(tipo)
VALUES
('Administrador'),
('Publicador'),
('Usuario registrado');	
    
SELECT * FROM pais;
INSERT INTO pais(nombre)
VALUES
('Guatemala'),
('Argentina'),
('Costa Rica');	

SELECT * FROM publicacion;
ALTER TABLE publicacion
ADD COLUMN imagen VARCHAR(255) DEFAULT NULL;


INSERT INTO publicacion (
    nombre_publicacion,
    estado,
    descripcion,
    fecha,
    id_categoria,
    ubicacion,
    hora,
    id_tipo_publico,
    limite_personas
) VALUES (
    'Evento de Tecnología',
    true,
    'Una conferencia sobre las últimas tendencias en tecnología.',
    '2024-10-15',
    2,
    'Centro de Convenciones',
    '10:00 AM',
    1,
    150
);
UPDATE publicacion
SET imagen = 'https://cig.industriaguate.com/wp-content/uploads/2023/10/02-Banner-Interior-768x432.jpg'
WHERE id_publicacion = 1;

INSERT INTO publicacion (
    nombre_publicacion,
    estado,
    descripcion,
    fecha,
    id_categoria,
    ubicacion,
    hora,
    id_tipo_publico,
    limite_personas,
    imagen
) VALUES (
    'Evento de Tecnología',
    true,
    'Una conferencia sobre las últimas tendencias en tecnología.',
    '2024-10-15',
    2,
    'Centro de Convenciones',
    '10:00 AM',
    1,
    150,
    'https://entercommla.com/hipegot/2024/07/PORTADA-1-1.png'
);

SELECT *FROM usuario;
INSERT INTO usuario (nombre, username, pass, correo, id_pais, id_rol) 
VALUES 
    ('Marina Baquiax', 'marinaB', '12345', 'marina@example.com', 1, 1),
    ('Ronald Tzul', 'ronaldT', '1234', 'ronald@example.com', 2, 2);

-- RESERVACION EVENTO-- 
SELECT *FROM reservacion_evento;
INSERT INTO reservacion_evento (activo, id_usuario, id_publicacion)
VALUES
(1,1,1);

-- SOLICITUD NOTIFICACION-- 
SELECT *FROM solicitud_notificacion;
INSERT INTO solicitud_notificacion (mensaje, fecha, id_usuario, id_publicacion)
VALUES
('Mensaje ofensivo', '2024-10-15', 1, 1);

UPDATE solicitud_notificacion
SET mensaje = 'Aceptar esta publicacion'
WHERE id_solicitud_notificacion = 1;

 -- REPORTE DE PUBLICACION 	
 SELECT *FROM ola_ke_hace.reporte_publicacion;
 INSERT INTO reporte_publicacion(motivo, id_publicacion)
 VALUES
 ('No cumple las politicas reservadas',
 '3'
 );
 
-- REPORTE NOTIFIACION--
SELECT *FROM reporte_notificacion;
SHOW CREATE TABLE reporte_notificacion;

INSERT INTO reporte_notificacion (mensaje, fecha, id_usuario, id_reporte_publicacion) 
VALUES ('Mensaje de reporte de notificación', '2024-10-15', 1, 1);

 -- Reporte publicacion 
 SELECT *FROM ola_ke_hace.reporte_publicacion;


INSERT INTO ola_ke_hace.publicacion (nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas,imagen
) VALUES (
    'Cumpleaños',
    true,
    'Fiesta de agradecimiento.',
    '2024-10-17',
    1,
    'Casa de habitación',
    '10:00 AM',
    1,
    150,
    'https://as1.ftcdn.net/v2/jpg/04/67/76/82/1000_F_467768289_A0WvZDOo4QN33y3vsoR7U4HovvwHf5e1.jpg'
);



