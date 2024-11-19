select *from ola_ke_hace.publicacion;
select *from ola_ke_hace.reporte_publicacion;
select *from ola_ke_hace.usuario;

select *from ola_ke_hace.usuario;
select *from ola_ke_hace.publicacion;
select *from ola_ke_hace.rol;

update ola_ke_hace.rol set tipo = 'Publicador_de_anuncios' where id_rol=2;

select *from ola_ke_hace.usuario;
select *from ola_ke_hace.pais;

DELETE FROM ola_ke_hace.pais WHERE id_pais = 4;
select *from ola_ke_hace.pais;

ALTER TABLE ola_ke_hace.usuario MODIFY COLUMN pass VARCHAR(500);


select *from ola_ke_hace.solicitud_notificacion;
select *from ola_ke_hace.reporte_publicacion;

select *from ola_ke_hace.tipo_publico;
DELETE FROM ola_ke_hace.tipo_publico WHERE idpublico = 4;

select *from ola_ke_hace.categoria_publicacion;
DELETE FROM ola_ke_hace.categoria_publicacion WHERE id = 4;
select * from ola_ke_hace.publicacion;
select *from ola_ke_hace.reservacion_evento;

select * from ola_ke_hace.publicacion;
UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = 29;
SELECT * FROM ola_ke_hace.publicacion WHERE id_publicacion = 29;
UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = 29;


ALTER TABLE ola_ke_hace.publicacion ADD COLUMN id_usuario INT NOT NULL;

ALTER TABLE ola_ke_hace.publicacion ADD CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES ola_ke_hace.usuario(id_usuario);

UPDATE ola_ke_hace.publicacion SET estado = 1 WHERE id_publicacion;
ALTER TABLE ola_ke_hace.publicacion ADD COLUMN aprobado int not null;