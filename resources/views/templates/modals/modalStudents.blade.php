<!--Modal de la insercion/actualizacion de los alumnos a manipular-->
<div class="modal fade" id="modalFormAlumno" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAlumns" name="formAlumns" class="form-horizontal">
                    <input type="hidden" id="idAlumn" name="idAlumn" value="">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Rut</label>
                            <input class="form-control" id="txtRutAlu" name="txtRutAlu" type="text" maxlength="12" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombres</label>
                            <input class="form-control" id="txtNombres" name="txtNombres" type="text" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Correo</label>
                            <input class="form-control" id="txtCorreoAlu" name="txtCorreoAlu" type="text" />
                        </div>
                        <div class="form-group col-md-6 selectCursos">
                            <label class="control-label">Curso</label>
                            <select class="form-control" id="listCurso" name="listCurso"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Puntaje Inicial</label>
                        <input class="form-control" id="txtPuntajeInicial" name="txtPuntajeInicial" type="number" />
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


<!--Modal donde se vera el detalle del alumno registrado-->
<div class="modal fade" id="modalViewAlumno" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Alumno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Rut:</strong></td>
                            <td id="celRut"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombres:</strong></td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr>
                            <td><strong>Curso:</strong></td>
                            <td id="celCurso"></td>
                        </tr>
                        <tr>
                            <td><strong>Telefono:</strong></td>
                            <td id="celTelefono"></td>
                        </tr>
                        <tr>
                            <td><strong>Direccion:</strong></td>
                            <td id="celDireccion"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstado"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!--Modal donde se pueda seleccionar un curso y asi poder insertar varios alumnos a la vez-->
<div class="modal fade" id="modalFormAlumnosIns" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Alumnos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group selectCurso">
                    <label class="control-label">Curso a Insertar</label>
                    <select class="form-control" id="listCursos" name="listCursos" onclick=""></select>
                </div>
                <div class="form-group">
                    <label for="fileInput" id="fileInputLabel" style="display: none;">Subir Registros</label>
                    <input type="file" class="form-control-file" id="fileInput" name="fileInput" style="display: none;">
                </div>
                <div class="modal-footer">
                    <button id="btnActionIns" class="btn btn-primary"><i
                            class="fas fa-check-circle"></i><span>&nbsp; Guardar</span></button>&nbsp;&nbsp;
                    <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                            class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>