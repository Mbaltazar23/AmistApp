<!--Modal del envio de puntos de alumnos a otros alumnos manipular-->
<div class="modal fade" id="modalFormPoints" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPoints" name="formPoints" class="form-horizontal">
                    <input type="hidden" id="idUserSen" name="idUserSen" value="" />
                    <input type="hidden" id="idUserRec" name="idUserRec" value="" />
                    <input type="hidden" id="pointsInput" name="pointsInput" value="" />
                    <div class="form-group">
                        <label class="control-label">Rut</label>
                        <input class="form-control" id="txtRutAlu" name="txtRutAlu" type="text" maxlength="12"
                            disabled />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nombres</label>
                        <input class="form-control" id="txtNombres" name="txtNombres" type="text" disabled />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Correo</label>
                        <input class="form-control" id="txtCorreoAlu" name="txtCorreoAlu" type="text" disabled />
                    </div>
                    <div class="form-group selectActions">
                        <label class="control-label">Accion a realizar</label>
                        <select class="form-control" id="listActions" name="listActions"
                            onchange="loadPoints()"></select>
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
