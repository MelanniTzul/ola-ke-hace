<?php
require_once __DIR__ . '/../../controllers/tipoPublicoController.php';
require_once __DIR__ . '/../../controllers/categoriaPublicacionController.php';
require_once __DIR__ . '/../../controllers/publicationController.php';

//* Inicialización de controladores
$addPublicationController = new PublicationController();
$tipoPublicoController = new TipoPublicoController();
$tipoCategoriaController = new CategoriaPublicacionController();

// Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $addPublicationController->addPublication($_POST);
}

//* Obtención de datos para los selects
$tipoPublico = $tipoPublicoController->mostrarTipoPublico();
$categoria = $tipoCategoriaController->mostrarCategoria();
?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Agregar Publicación</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/app/views/publicacion/addpublicacion.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Informacion del perfil</label>
                        <input 
                            type="text" 
                            name="nombre_publicacion" 
                            class="form-control" 
                            id="nombre" 
                            placeholder="Cumpleaños" 
                            required>
                    </div>
                    <input type="hidden" name="estado" value="1">

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea 
                            name="descripcion" 
                            class="form-control" 
                            id="descripcion" 
                            rows="3" 
                            placeholder="Descripción del evento" 
                            required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select id="id_categoria" name="id_categoria" class="form-control" required>
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
                            <input 
                                type="text" 
                                name="ubicacion" 
                                class="form-control" 
                                id="ubicacion" 
                                placeholder="Centro de Convenciones" 
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">Fecha del evento</label>
                            <input 
                                type="date" 
                                name="fecha" 
                                class="form-control" 
                                id="fecha" 
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora" class="form-label">Hora del evento</label>
                            <input 
                                type="time" 
                                name="hora" 
                                class="form-control" 
                                id="hora" 
                                required>
                        </div>
                        </div>

                    <div class="mb-3">
                        <label for="id_tipo_publico" class="form-label">Tipo Público</label>
                        <select id="id_tipo_publico" name="id_tipo_publico" class="form-control" required>
                            <option disabled selected>Tipo de Público</option>
                            <?php foreach ($tipoPublico as $publico): ?>
                                <option value="<?= htmlspecialchars($publico['idpublico']) ?>">
                                    <?= htmlspecialchars($publico['tipo_publico']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="limite_personas" class="form-label">Límite de Personas</label>
                            <input 
                                type="number" 
                                name="limite_personas" 
                                class="form-control" 
                                id="limite_personas" 
                                placeholder="150" 
                                min="1" 
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="imagen" class="form-label">URL de la Imagen</label>
                            <input 
                                type="url" 
                                name="imagen" 
                                class="form-control" 
                                id="imagen" 
                                placeholder="https://example.com/imagen.png" 
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                    <input type="hidden" name="action" value="addPublication">
                </form>
            </div>
        </div>
    </div>
</div>
