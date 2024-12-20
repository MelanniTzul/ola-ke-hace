SELECT * FROM ola_ke_hace.reporte_publicacion;
SELECT * FROM ola_ke_hace.usuario;
select * from ola_ke_hace.publicacion;
SELECT 
    p.id_publicacion, 
    p.nombre_publicacion, 
    p.estado, 
    p.descripcion, 
    p.fecha, 
    p.id_categoria, 
    p.ubicacion, 
    p.hora, 
    p.id_tipo_publico, 
    p.limite_personas, 
    p.imagen, 
    p.id_usuario,
    u.nombre,
    rp.total_reportes
FROM 
    ola_ke_hace.publicacion p
LEFT JOIN (
    SELECT 
        id_publicacion, 
        COUNT(*) AS total_reportes 
    FROM 
        ola_ke_hace.reporte_publicacion
    WHERE 
        estado = 1
    GROUP BY 
        id_publicacion
) rp ON p.id_publicacion = rp.id_publicacion
LEFT JOIN 
    ola_ke_hace.usuario u ON p.id_usuario = u.id_usuario 
WHERE 
    p.estado = 1 
    AND p.aprobado = 1 
    AND (rp.total_reportes < 3 OR rp.total_reportes IS NULL);
    
    
    