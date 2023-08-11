<!--Modal de la insercion/actualizacion de los tutores a manipular-->
<div class="modal fade" id="modalFormTutores" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTutor" name="formTutor" class="form-horizontal">
                    <input type="hidden" id="idProfe" name="idProfe" value="">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Rut</label>
                            <input class="form-control" id="txtRutT" name="txtRutT" type="text" maxlength="12" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombres</label>
                            <input class="form-control" id="txtNombres" name="txtNombres" type="text" />
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="control-label">Correo</label>
                        <input class="form-control" id="txtCorreoT" name="txtCorreoT" type="text" />
                    </div>
                    <div class="form-group selectCursos">
                        <label class="control-label">Curso</label>
                        <select class="form-control" id="listCursos" name="listCursos[]" multiple></select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Contraseña</label>
                        <input class="form-control" id="txtPassword" name="txtPassword" type="password">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Telefono</label>
                            <input type="text" class="form-control" id="txtTelefono" name="txtTelefono"
                                maxlength="12" value="+569" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Direccion</label>
                            <textarea class="form-control" id="txtDireccion" name="txtDireccion"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i
                                class="fas fa-check-circle"></i><span id="btnText"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                                class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal donde se vera el detalle del tutor registrado-->
<div class="modal fade" id="modalViewTutor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Profesor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Rut:</strong></td>
                            <td id="celRutT"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombres:</strong></td>
                            <td id="celNombresT"></td>
                        </tr>
                        <tr>
                            <td><strong>Correo:</strong></td>
                            <td id="celCorreoT"></td>
                        </tr>
                        <tr>
                            <td><strong>Curso(s) Designado</strong></td>
                            <td id="celCursoT"></td>
                        </tr>
                        <tr>
                            <td><strong>Telefono:</strong></td>
                            <td id="celTelefonoT"></td>
                        </tr>
                        <tr>
                            <td><strong>Direccion:</strong></td>
                            <td id="celDireccionT"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoT"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
