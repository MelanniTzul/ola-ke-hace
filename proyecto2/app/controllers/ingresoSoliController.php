<?php
require_once __DIR__ . '/../models/ingresoSoliModel.php';
class ingresoSoliController{
    public function __construct(){}

    public function index(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoge los datos del formulario
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
            $nit = isset($_POST['nit']) ? $_POST['nit'] : '';
            $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
            $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
            $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
            $nivel = isset($_POST['nivel']) ? $_POST['nivel'] : '';
            $num_departamento = isset($_POST['num_departamento']) ? $_POST['num_departamento'] : '';
            $descripcion_departamento = isset($_POST['descripcion_departamento']) ? $_POST['descripcion_departamento'] : '';
            $total_servicio = isset($_POST['total_servicio']) ? $_POST['total_servicio'] : '';
            
            if (empty($nombre) || empty($nit) || empty($telefono) || empty($fecha_inicio) || empty($fecha_fin) || empty($nivel) || empty($num_departamento)||empty($descripcion_departamento)||empty($total_servicio)) {
                echo "Todos los campos son obligatorios.";
                exit;
            }
        
            // Crear instancia del modelo
             $ingresoSoliModel = new IngresoSoliModel();
        
            // Insertar los datos
             $resultado = $ingresoSoliModel->insertServicioEdificioData($total_servicio,$fecha_inicio,$fecha_fin,1,1);
        
            // if ($resultado) {
                echo "Solicitud registrada con éxito.";
                // Redirigir o mostrar un mensaje de éxito
            // } else {
            //     echo "Hubo un error al registrar la solicitud.";
            //     // Mostrar mensaje de error
            // }
        }
    }
}
$controller = new ingresoSoliController();
$controller->index();
?>
