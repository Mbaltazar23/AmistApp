<!-- Modal para registrar/actualizar a los Administradores de Colegios-->
<div class="modal fade" id="modalFormColegios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formColegio" name="formColegio" class="form-horizontal">
                    <input type="hidden" id="idColegio" name="idColegio" value="">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Rut</label>
                            <input class="form-control" id="txtRut" name="txtRut" type="text">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" id="txtNombre" name="txtNombre" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Telefono</label>
                        <input class="form-control" id="txtTelefono" name="txtTelefono" type="text" value="+569" maxlength="12">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Direccion</label>
                        <textarea class="form-control" id="txtDireccion" name="txtDireccion" rows="2"></textarea>
                    </div> 
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;<span id="btnText">Guardar</span></button>
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewAdmin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos del Colegio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered tableViewAdmin">
                    <tbody>
                        <tr>
                            <td>Rut:</td>
                            <td id="celRut"></td>
                        </tr>
                        <tr>
                            <td>Nombre:</td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr>
                            <td>Telefono:</td>
                            <td id="celTelefono"></td>
                        </tr>
                        <tr>
                            <td>Direccion:</td>
                            <td id="celDireccion"></td>
                        </tr>
                        <tr>
                            <td>Fecha:</td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td>Hora:</td>
                            <td id="celHora"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="celStatus"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
