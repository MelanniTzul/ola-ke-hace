<?php
class paisModel{
    private $conn;
    public function __construct(){
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }
   
    public function getPais(){
        $pais = [];
        $sql = "SELECT *FROM ola_ke_hace.pais";
        $resultado = $this->conn->query($sql);
        if($resultado->num_rows>0){
            while($fila = $resultado->fetch_assoc()){
                $pais[]=$fila;
            }
        }
        return $pais;
    }
}