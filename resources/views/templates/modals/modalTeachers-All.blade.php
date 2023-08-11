<!--Modal de la actualizacion de las passwords de los profesores a manipular-->
<div class="modal fade" id="modalFormTeacherPass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTeachersPass" name="formTeachersPass" class="form-horizontal">
                    <input type="hidden" id="idTeacher" name="idTeacher" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <p><strong>Rut:</strong>&nbsp;&nbsp;<span id="rutTeacher"></span></p>
                                <p><strong>Nombres:</strong>&nbsp;&nbsp;<span id="nombreTeacher"></span></p>
                                <p><strong>Email:</strong>&nbsp;&nbsp;<span id="emailTeacher"></span></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Password</label>
                                <input class="form-control" id="txtPassword01" name="txtPassword01" type="password" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Confirmar Password</label>
                                <input class="form-control" id="txtPassword02" name="txtPassword02" type="password" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i
                                class="fas fa-check-circle"></i><span id="btnText">&nbsp;
                                Actualizar</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                                class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal para asignar puntaje a los profesores-->
<div class="modal fade" id="modalFormTeacherPoints" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Puntaje al Profesor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTeachersPoints" name="formTeachersPoints" class="form-horizontal">
                    <input type="hidden" id="idTeacherPoint" name="idTeacherPoint" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <p><strong>Rut:</strong>&nbsp;&nbsp;<span id="rutTeacherPoint"></span></p>
                                <p><strong>Nombres:</strong>&nbsp;&nbsp;<span id="nombreTeacherPoint"></span></p>
                                <p><strong>Email:</strong>&nbsp;&nbsp;<span id="emailTeacherPoint"></span></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Puntaje a asignar</label>
                            <input class="form-control" id="txtPuntaje" name="txtPuntaje" type="number" />
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i
                                class="fas fa-check-circle"></i><span>&nbsp;
                                Actualizar Puntaje</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                                class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
