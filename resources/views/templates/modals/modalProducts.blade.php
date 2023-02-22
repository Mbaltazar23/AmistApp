<!-- Modal -->
<div class="modal fade" id="modalFormProductos">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Nueva Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formProductos" name="formProductos" class="form-horizontal">
                    <input type="hidden" id="idProducto" name="idProducto" value="">
                    <input type="hidden" id="foto_actual" name="foto_actual" value="">
                    <input type="hidden" id="foto_remove" name="foto_remove" value="0">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" id="txtNombre" name="txtNombre" type="text">
                        </div>
                        <div class="form-group col-md-6 selectCategoria">
                            <label for="listCategoria">Categoría</label>
                            <select class="form-control" id="listCategoria" name="listCategoria">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Puntos</label>
                            <input class="form-control" id="txtPuntos" name="txtPuntos" type="text">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Stock <span class="required">*</span></label>
                            <input class="form-control" id="txtStock" name="txtStock" type="text">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="photo">
                            <label for="image">Agregar Portada</label>
                            <div class="prevPhoto">
                                <span class="delPhoto notBlock">X</span>
                                <label for="image"></label>
                                <div>
                                    <img id="img" />
                                </div>
                            </div>
                            <div class="upimg">
                                <input type="file" name="image" id="image">
                            </div>
                            <div id="form_alert"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i
                                class="fa fa-check-circle"></i>&nbsp;&nbsp;<span id="btnText">Guardar</span></button>
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos del Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr>
                            <td><strong>Precio:</strong></td>
                            <td id="celPrecio"></td>
                        </tr>
                        <tr>
                            <td><strong>Categoría:</strong></td>
                            <td id="celCategoria"></td>
                        </tr>
                        <tr>
                            <td><strong>Stock:</strong></td>
                            <td id="celStock"></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td><strong>Hora:</strong></td>
                            <td id="celHora"></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td id="celStatus"></td>
                        </tr>
                        <tr>
                            <td><strong>Foto:</strong></td>
                            <td id="celFoto">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
