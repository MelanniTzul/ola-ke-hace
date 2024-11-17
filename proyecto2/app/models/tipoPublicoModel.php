<?php
class tipoPublicoModel{
    private $conn;
    public function __construct(){
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }
   
    public function getTipoPublico(){
        $tipoP = [];
        $sql = "SELECT *FROM ola_ke_hace.tipo_publico";
        $resultado = $this->conn->query($sql);
        if($resultado->num_rows>0){
            while($fila = $resultado->fetch_assoc()){
                $tipoP[]=$fila;
            }
        }
        return $tipoP;
    }
}