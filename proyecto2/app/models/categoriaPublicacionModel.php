<?php
class categoriaPublicacionModel{
    private $conn;
    public function __construct(){
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }
   
    public function getCategoriaP(){
        $categoriaP = [];
        $sql = "SELECT *FROM ola_ke_hace.categoria_publicacion";
        $resultado = $this->conn->query($sql);
        if($resultado->num_rows>0){
            while($fila = $resultado->fetch_assoc()){
                $categoriaP[]=$fila;
            }
        }
        return $categoriaP;
    }
}