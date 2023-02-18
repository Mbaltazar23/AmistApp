<!-- Modal FormNotifiaciones para el insert/update para las notificacion (mayormente de tipo Video/Mensaje-->
<div class="modal fade" id="modalFormNotificaciones">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNotificaciones" name="formNotificaciones" class="form-horizontal">
                    <input type="hidden" id="idNotificacion" name="idNotificacion" value="">
                    <input type="hidden" id="idQuestion" name="idQuestion" value="">
                    <input type="hidden" id="idAnswers" name="idAnswers" value="">
                    <div class="form-group">
                        <label for="listTipoNotificacion">Tipo</label>
                        <select class="form-control" id="listTipoNotificacion" name="listTipoNotificacion"
                            onchange="cambiarDisplay(this.value);">
                            <option value="">Seleccione el tipo de notificacion</option>
                            <option value="{{ env('TIPONOTQ') }}">Pregunta</option>
                            <option value="{{ env('TIPONOTV') }}">Video/Mensaje</option>
                        </select>
                    </div>
                    <div id="notificacionesPreguntas" style="display:none">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for>Titulo</label>
                                <input type="text" class="form-control" id="titleQuestion" name="titleQuestion" />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="question">Pregunta:</label>
                                <input type="text" class="form-control" id="question" name="question">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="points">Puntaje:</label>
                                <input type="text" class="form-control" id="pointsQuestion" name="pointsQuestion">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="answer1">Alternativa 1:</label>
                                <input type="text" id="answer1" class="form-control" name="answer1">
                                <label for="advice1">Consejo 1:</label>
                                <textarea id="advice1" name="advice1" class="form-control"></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="answer2">Alternativa 2:</label>
                                <input type="text" id="answer2" class="form-control" name="answer2">
                                <label for="advice2">Consejo 2:</label>
                                <textarea id="advice2" class="form-control" name="advice2"></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="answer3">Alternativa 3:</label>
                                <input type="text" id="answer3" class="form-control" name="answer3">
                                <label for="advice3">Consejo 3:</label>
                                <textarea id="advice3" class="form-control" name="advice3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="notificacionesMessage" style="display:none">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for>Titulo</label>
                                <input type="text" class="form-control" id="titleMessage" name="titleMessage" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pointsMessage">Puntaje</label>
                                <input type="text" class="form-control" id="pointsMessage" name="pointsMessage" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Mensaje:</label>
                            <textarea id="message" class="form-control" name="message"></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="response">Respuesta:</label>
                                <textarea id="response" class="form-control" name="response"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="advice">Consejo:</label>
                                <textarea id="advice" class="form-control" name="advice"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i
                                class="fa fa-check-circle"></i>&nbsp;&nbsp;<span
                                id="btnText">Guardar</span></button>&nbsp;&nbsp;
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modalFormNotificacionTitle">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalT"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTitleN" name="formTitleN" class="form-horizontal">
                    <input type="hidden" id="idNotificacionT" name="idNotificacionT" value="">
                    <input type="hidden" id="txtQuestion" name="txtQuestion" />
                    <div class="form-group">
                        <label for="tipoNotificacion">Tipo</label>
                        <input type="text" name="tipoNotificacion" id="tipoNotificacion" class="form-control"
                            disabled />
                    </div>
                    <div class="form-group">
                        <label for="title">Titulo</label>
                        <input type="text" class="form-control" id="title" name="title" />
                    </div>
                    <div class="form-group">
                        <label for="title">Puntaje</label>
                        <input type="text" class="form-control" id="points" name="points" />
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal"
                            onclick="cerrarModal();"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                        <button id="btnActionFormT" class="btn btn-info" type="submit"><i
                                class="fa fa-check-circle"></i>&nbsp;&nbsp;Actualizar</button>&nbsp;&nbsp;
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver las notificaciones ya creadas-->
<div class="modal fade" id="modalViewNotificacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos del La Notificacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered tableViewAdmin">
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
                            <td>Tipo:</td>
                            <td id="celTipo"></td>
                        </tr>
                        <tr>
                            <td>Puntos</td>
                            <td id="celPuntos"></td>
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
                        <tr>
                            <td>Contenido:</td>
                            <td id="celContenido"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Modal para visualizar la coleccion de las preguntas obtenidas de la notificacion de tipo "Pregunta"-->
<div class="modal fade" id="modalQuestionList">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalL"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-header">
                    <div class="input-group mb-15">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                Nueva pregunta
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" id="btnAddQuestion">Nuevo</a>
                                <a class="dropdown-item" id="btnNotificacion">Actualizar Titulo</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableQuestions" class="table table-hover table-striped table-responsive-lg"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Pregunta</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Modal para añadir/actualizar las preguntas relacionadas a la notificacion seleccionada-->
<div class="modal fade" id="modalFormQuestions">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalQ"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formQuestions" name="formQuestions" class="form-horizontal">
                    <input type="hidden" id="idNotificacionQ" name="idNotificacionQ" value="">
                    <input type="hidden" id="idQ" name="idQ" value="">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for>Titulo</label>
                            <input type="text" class="form-control" id="titleQ" name="titleQ" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="question">Pregunta:</label>
                            <input type="text" class="form-control" id="Question" name="Question">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="respu1">Alternativa 1:</label>
                            <input type="hidden" id="idres1" name="idres1" />
                            <input type="text" id="respu1" class="form-control" name="respu1">
                            <label for="advice1">Consejo 1:</label>
                            <textarea id="conse1" name="conse1" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="respu2">Alternativa 2:</label>
                            <input type="hidden" id="idres2" name="idres2" />
                            <input type="text" id="respu2" class="form-control" name="respu2">
                            <label for="conse2">Consejo 2:</label>
                            <textarea id="conse2" class="form-control" name="conse2"></textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="respu3">Alternativa 3:</label>
                            <input type="hidden" id="idres3" name="idres3" />
                            <input type="text" id="respu3" class="form-control" name="respu3">
                            <label for="conse3">Consejo 3:</label>
                            <textarea id="conse3" class="form-control" name="conse3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal"
                            onclick="cerrarModal();"><i class="fa fa-times-circle"></i>&nbsp;Cerrar</button>
                        <button id="btnActionFormQ" class="btn btn-primary" type="submit"><i
                                class="fa fa-check-circle"></i>&nbsp;<span
                                id="btnTextQ">Guardar</span></button>&nbsp;&nbsp;
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
