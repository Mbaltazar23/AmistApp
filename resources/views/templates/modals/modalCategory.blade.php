<!-- Modal FormCategorias-->
<div class="modal fade" id="modalFormCategorias">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Nueva Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCategoria" name="formCategoria" class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="foto_actual" name="foto_actual" value="">
                    <input type="hidden" id="foto_remove" name="foto_remove" value="0">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="name" name="name" type="text" />
                    </div>
                    <div class="col-md-6">
                        <div class="photo">
                            <label for="image">Agregar Portada</label>
                            <div class="prevPhoto">
                                <span class="delPhoto notBlock">X</span>
                                <label for="image"></label>
                                <div>
                                    <img id="img" src="images/categories/portada_categoria.png" />
                                </div>
                            </div>
                            <div class="upimg">
                                <input type="file" name="image" id="image">
                            </div>
                            <div id="form_alert"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i>&nbsp;Cerrar</button>
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i
                                class="fa fa-check-circle"></i>&nbsp;<span
                                id="btnText">Guardar</span></button>&nbsp;&nbsp;
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Modal -->
<div class="modal fade" id="modalViewCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos de la categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nro:</strong></td>
                            <td id="celNro"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha Creada:</strong></td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td><strong>Hora Creada:</strong></td>
                            <td id="celHora"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstado"></td>
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
