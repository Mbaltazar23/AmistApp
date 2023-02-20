<!--Modal de la insercion/actualizacion de las cursos-->
<div class="modal fade" id="modalFormCursos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCursos" name="formCursos" class="form-horizontal">
                    <input type="hidden" id="idCurso" name="idCurso" value="">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombre" name="txtNombre" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sección</label>
                        <select class="form-control" id="selectSection" name="selectSection" type="text">
                            <option value="0">Seleccione una letra para la sección</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnText"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal donde se vera el detalle de la categoria registrada-->
<div class="modal fade" id="modalViewCurso" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos del Curso</h5>
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
                            <td><strong>Fecha:</strong></td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td id="celHora"></td>
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
