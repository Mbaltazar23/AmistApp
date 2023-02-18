<!-- Modal FormCategorias-->
<div class="modal fade" id="modalFormAcciones">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAccion" name="formAccion" class="form-horizontal">
                    <input type="hidden" id="idAccion" name="idAccion" value="">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombre" name="txtNombre" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Puntaje</label>
                        <input class="form-control" id="txtPuntaje" name="txtPuntaje" type="text"/>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-times-circle"></i>&nbsp;Cerrar</button>
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-check-circle"></i>&nbsp;<span id="btnText">Guardar</span></button>&nbsp;&nbsp;
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Modal -->
<div class="modal fade" id="modalViewAccion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos de la Accion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Nro:</td>
                            <td id="celNro"></td>
                        </tr>
                        <tr>
                            <td>Nombre:</td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr>
                            <td>Puntaje:</td>
                            <td id="celPuntaje"></td>
                        </tr>
                        <tr>
                            <td>Fecha Creada:</td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td>Hora Creada:</td>
                            <td id="celHora"></td>
                        </tr>
                        <tr>
                            <td>Estado:</td>
                            <td id="celEstado"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





