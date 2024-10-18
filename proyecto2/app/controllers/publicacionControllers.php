//*Mostrar los departamentos
<?php
require_once __DIR__ . '/../models/publicacionModel.php';
class ReservationCOntroller{
    private $reservationModel;
    public function __construct(){
        $this->reservationModel = new publicacionModel();
    }

    //*VISUALIZAR LA VISTA
    public function showPublicacion(){
        $reserva = $this->reservationModel->getReservation();
        extract(['reserva'=>$reserva]);
        include __DIR__.'/../views/publicacion/publicacion.php';
    }
}
?>