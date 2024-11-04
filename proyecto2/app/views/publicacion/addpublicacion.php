
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Agregar Publicación</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ADD CONTROLLER -->
                <form action="/app/controllers/addPublicationController.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Publicación</label>
                            <input type="text" name="nombre_publicacion" class="form-control" id="nombre" placeholder="Cumpleaños">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" class="form-select" id="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" id="descripcion" rows="3" placeholder="Descripción del evento"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" id="fecha" placeholder="2024-10-15">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_categoria" class="form-label">ID Categoría</label>
                            <input type="number" name="id_categoria" class="form-control" id="id_categoria" placeholder="2" value="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control" id="ubicacion" placeholder="Centro de Convenciones">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" name="hora" class="form-control" id="hora" placeholder="10:00 AM">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_tipo_publico" class="form-label">ID Tipo Público</label>
                            <input type="number" name="id_tipo_publico" class="form-control" id="id_tipo_publico" placeholder="1" value="1">
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
