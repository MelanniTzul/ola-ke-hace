<?php
require_once __DIR__ . '/../../controllers/tipoPublicoController.php';
require_once __DIR__ . '/../../controllers/categoriaPublicacionController.php';
require_once __DIR__ . '/../../controllers/addPublicationController.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $userController->mostrarTipoPublico(); 
// }

$addPublicationController = new AddPublicacionController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $addPublicationController->addPublication();
}


$tipoPublicoController = new TipoPublicoController();
$tipoPublico = $tipoPublicoController->mostrarTipoPublico();

$tipoCategoriaController = new CategoriaPublicacionController();
$categoria = $tipoCategoriaController->mostrarCategoria();


?>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Agregar Publicación</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ADD CONTROLLER -->
                <form action="/app/views/publicacion/addpublicacion.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Publicación</label>
                            <input type="text" name="nombre_publicacion" class="form-control" id="nombre" placeholder="Cumpleaños">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" id="descripcion" rows="3" placeholder="Descripción del evento"></textarea>
                        </div>
                    </div>

                        

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id" class="form-label">Categoría</label>
                            <select id="id" name="id_categoria" class="form-control" required>
                                <option disabled selected>Categoría</option>
                                <?php foreach ($categoria as $item): ?>
                                    <option value="<?= htmlspecialchars($item['id']) ?>">
                                        <?= htmlspecialchars($item['nombre_categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control" id="ubicacion" placeholder="Centro de Convenciones">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="idpublico" class="form-label">Tipo Público</label>
                            <select id="idpublico" name="id_tipo_publico" class="form-control" required>
                                <option disabled selected>Tipo de Público</option>
                                <?php foreach ($tipoPublico as $publico): ?>
                                    <option value="<?= htmlspecialchars($publico['idpublico']) ?>">
                                        <?= htmlspecialchars($publico['tipo_publico']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="limite_personas" class="form-label">Límite de Personas</label>
                            <input type="number" name="limite_personas" class="form-control" id="limite_personas" placeholder="150">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="imagen" class="form-label">URL de la Imagen</label>
                            <input type="text" name="imagen" class="form-control" id="imagen" placeholder="https://entercommla.com/hipegot/2024/07/PORTADA-1-1.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>