let tableNotificaciones, tableQuestions;
let rowTable = "";
$(document).on('focusin', function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});


document.addEventListener('DOMContentLoaded', function () {

    tableNotificaciones = $('#tableNotificaciones').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Notificaciones/getNotificaciones",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "mensaje"},
            {"data": "tipo"},
            {"data": "fecha"},
            {"data": "status"},
            {"data": "options"}
        ],
        "paging": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });

    if (document.querySelector("#formNotificaciones")) {
        let formNotificaciones = document.querySelector("#formNotificaciones");
        formNotificaciones.onsubmit = function (e) {
            e.preventDefault();

            let listTipoNotificacion = document.querySelector("#listTipoNotificacion").value;
            let idNot = document.querySelector("#idNotificacion").value;
            let idQ = document.querySelector("#idQuestion").value;
            let idA = document.querySelector("#idAnswers").value;
            let questionAndAnswers = [];

            if (listTipoNotificacion == '') {
                swal("Error !!", "No selecciono el tipo de Notificacion a registrar !!", "error");
                return false;
            } else {
                if (listTipoNotificacion == "Pregunta") {
                    var title = document.getElementById("titleQuestion").value;
                    var question = document.getElementById("question").value;
                    var answer1 = document.getElementById("answer1").value;
                    var answer2 = document.getElementById("answer2").value;
                    var answer3 = document.getElementById("answer3").value;
                    var advice1 = document.getElementById("advice1").value;
                    var advice2 = document.getElementById("advice2").value;
                    var advice3 = document.getElementById("advice3").value;
                    var answers = [
                        {answer: answer1, advice: advice1},
                        {answer: answer2, advice: advice2},
                        {answer: answer3, advice: advice3}
                    ];

                    questionAndAnswers = {answers: answers};

                    if (!title || !question || !answer1 || !advice1 || !answer2 || !advice2 || !answer3 || !advice3) {
                        swal("Error !!", "Faltan campos por completar !!", "error");
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: base_url + "/Notificaciones/setNotificacion",
                        data: {idNotificacion: idNot, Question: question, Answers: questionAndAnswers,
                            listTipoNotificacion: listTipoNotificacion, title: title},
                        success: function (data) {
                            let objData = JSON.parse(data);
                            if (objData.status) {
                                tableNotificaciones.api().ajax.reload();
                                $('#modalFormNotificaciones').modal('hide');
                                swal("Exito !!", objData.msg, "success");
                            } else {
                                swal("Error", objData.msg, "error");
                            }
                        }
                    });
                } else {
                    var message = document.getElementById('message').value;
                    var response = document.getElementById('response').value;
                    var advice = document.getElementById('advice').value;
                    var titleMessage = document.getElementById('titleMessage').value;

                    if (!message || !response || !advice || !titleMessage) {
                        swal("Error !!", "Faltan campos por completar !!", "error");
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: base_url + "/Notificaciones/setNotificacion",
                        data: {
                            idNotificacion: idNot,
                            idQuestion: idQ,
                            idAnswers: idA,
                            listTipoNotificacion: listTipoNotificacion,
                            Message: message,
                            Response: response,
                            Advice: advice,
                            title: titleMessage
                        },
                        success: function (data) {
                            let objData = JSON.parse(data);
                            if (objData.status) {
                                tableNotificaciones.api().ajax.reload();
                                $('#modalFormNotificaciones').modal('hide');
                                swal("Exito !!", objData.msg, "success");
                            } else {
                                swal("Error", objData.msg, "error");
                            }
                        }
                    });

                }
            }
        };
    }

    if (document.querySelector("#formQuestions")) {
        let formQuestions = document.querySelector("#formQuestions");
        formQuestions.onsubmit = function (e) {
            e.preventDefault();
            let idNot = document.querySelector("#idNotificacionQ").value;
            let idQ = document.querySelector("#idQ").value;
            var question = document.getElementById("Question").value;
            var idres1 = document.getElementById("idres1").value;
            var idres2 = document.getElementById("idres2").value;
            var idres3 = document.getElementById("idres3").value;
            var answer1 = document.getElementById("respu1").value;
            var answer2 = document.getElementById("respu2").value;
            var answer3 = document.getElementById("respu3").value;
            var advice1 = document.getElementById("conse1").value;
            var advice2 = document.getElementById("conse2").value;
            var advice3 = document.getElementById("conse3").value;
            var answers = [
                {id: idres1, answer: answer1, advice: advice1},
                {id: idres2, answer: answer2, advice: advice2},
                {id: idres3, answer: answer3, advice: advice3}
            ];

            let questionAndAnswers = {answers: answers};

            if (!question || !answer1 || !advice1 || !answer2 || !advice2 || !answer3 || !advice3) {
                swal("Error !!", "Faltan campos por completar !!", "error");
                return false;
            }

            $.ajax({
                type: "POST",
                url: base_url + "/Notificaciones/setQuestion",
                data: {idNotificacion: idNot,
                    idQuestion: idQ, Question: question, Answers: questionAndAnswers},
                success: function (data) {
                    let objData = JSON.parse(data);
                    if (objData.status) {
                        tableQuestions.api().ajax.reload();
                        $('#modalFormQuestions').modal('hide');
                        cerrarModal();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            });
        };
    }

    if (document.querySelector("#formTitleN")) {
        let formTitleN = document.querySelector("#formTitleN");
        formTitleN.onsubmit = function (e) {
            e.preventDefault();
            let idNot = document.querySelector("#idNotificacionT").value;
            let tipoNotificacion = document.querySelector("#tipoNotificacion").value;
            let titleNotificacion = document.querySelector("#title").value;
            let question = document.querySelector("#txtQuestion").value;
            if (titleNotificacion == "") {
                swal("Error !!", "El titulo no puede estar vacio !!", "error");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + "/Notificaciones/setNotificacion",
                    data: {idNotificacion: idNot,
                        listTipoNotificacion: tipoNotificacion, title: titleNotificacion, Question: question},
                    success: function (data) {
                        let objData = JSON.parse(data);
                        if (objData.status) {
                            tableNotificaciones.api().ajax.reload();
                            $('#modalFormNotificacionTitle').modal('hide');
                            cerrarModal();
                            swal("Exito !!", objData.msg, "success");
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }
                });
            }
        };
    }
}, false);


function openModal() {
    document.querySelector('#idNotificacion').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Notificacion";
    document.querySelector("#formNotificaciones").reset();
    document.querySelector("#notificacionesPreguntas").style.display = "none";
    document.querySelector("#notificacionesMessage").style.display = "none";
    document.querySelector(".modal-dialog").classList.remove("modal-lg");
    $('#modalFormNotificaciones').modal('show');
    vaciarCampos();
}

function cambiarDisplay(value) {
    if (value == "Pregunta") {
        document.querySelector("#notificacionesPreguntas").style.display = "block";
        document.querySelector(".modal-dialog").classList.add("modal-lg");
        document.querySelector("#notificacionesMessage").style.display = "none";
        vaciarCampos();
    } else if (value == "Video/Mensaje") {
        document.querySelector("#notificacionesPreguntas").style.display = "none";
        document.querySelector(".modal-dialog").classList.remove("modal-lg");
        document.querySelector("#notificacionesMessage").style.display = "block";
        vaciarCampos();
    } else {
        document.querySelector("#notificacionesPreguntas").style.display = "none";
        document.querySelector("#notificacionesMessage").style.display = "none";
        document.querySelector(".modal-dialog").classList.remove("modal-lg");
        vaciarCampos();
    }
}


function vaciarCampos() {
    document.querySelector("#notificacionesPreguntas input[type='text']").value = '';
    document.querySelector("#notificacionesMessage textarea").value = '';
    document.querySelector("#notificacionesPreguntas textarea").value = '';
}

function fntViewInfo(nro, idNotificacion) {
    document.querySelector("#celContenido").innerHTML = "";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Notificaciones/getNotificacion/' + idNotificacion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                console.log(objData.data);

                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML = objData.data.mensaje;
                document.querySelector("#celTipo").innerHTML = objData.data.tipo;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celStatus").innerHTML = estado;

                let objTipo = objData.data.tipo;
                let objQuestions = objData.data.notifacion_message;

                if (objTipo == "Pregunta") {
                    objQuestions.forEach(function (h) {
                        let pregunta = h.pregunta;
                        let answersQ = h.AnswersQ;

                        let answersHTML = "<ul>";
                        answersQ.forEach(function (a) {
                            answersHTML += `<li> ${a.respuesta}  -  ${a.consejo || ""} </li>`;
                        });
                        answersHTML += "</ul>";

                        document.querySelector("#celContenido").innerHTML += `<b>${pregunta}</b> ${answersHTML}`;
                    });
                } else {

                    let objNotifacionMessage = objData.data.notifacion_message;

                    document.querySelector("#celContenido").innerHTML += `<ul>
                                                            <li>${objNotifacionMessage.pregunta}</li>
                                                            <li>${objNotifacionMessage.respuesta}</li>
                                                            <li> ${objNotifacionMessage.consejo || ""}</li>
                                                       </ul>`;
                }

            }
        }
        $("#modalViewNotificacion").modal('show');
    };
}


function fntEditInfo(element, idNotificacion) {
    rowTable = element.parentNode.parentNode.parentNode;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Notificaciones/getNotificacion/' + idNotificacion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                if (objData.data.tipo == "Pregunta") {
                    viewListPreguntaNotificacion(objData.data);
                } else {
                    viewFormVideoMessageNotificacion(objData.data);
                }
            }
        }
    };
}

function viewListPreguntaNotificacion(arrNotificacion) {
    document.querySelector('#titleModalL').innerHTML = "Preguntas de la Notificacion - " + arrNotificacion.mensaje;
    loadQuestions(arrNotificacion.id);
    $("#modalQuestionList").modal('show');
    document.querySelector("#btnAddQuestion").addEventListener("click", function () {
        viewFormPreguntaNotificacion(arrNotificacion);
        $("#modalQuestionList").modal('hide');
        $("#modalFormQuestions").modal('show');
    });
    document.querySelector("#btnNotificacion").addEventListener("click", function () {
        viewFormTitleNotificacion(arrNotificacion);
        $("#modalQuestionList").modal('hide');
        $("#modalFormNotificacionTitle").modal('show');
    });
}

function loadQuestions(idNotificacion) {
    tableQuestions = $('#tableQuestions').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Notificaciones/getQuestionsNotificacion/" + idNotificacion,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "pregunta"},
            {"data": "fecha"},
            {"data": "hora"},
            {"data": "options"}
        ],
        "paging": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });
}

function viewFormPreguntaNotificacion(arrNotificacion) {
    document.querySelector("#formQuestions").reset();
    document.querySelector("#idQ").value = "";
    document.querySelector('#titleModalQ').innerHTML = "Nueva Pregunta a la Notificacion";
    document.querySelector("#idNotificacionQ").value = arrNotificacion.id;
    document.querySelector("#titleQ").value = arrNotificacion.mensaje.toString().toLowerCase();
    document.querySelector("#titleQ").disabled = true;
    document.querySelector('#btnTextQ').innerHTML = "  Guardar";
}

function viewFormTitleNotificacion(arrNotificacion) {
    document.querySelector('#titleModalT').innerHTML = "Actualizar Titulo de la Notificacion";
    document.querySelector("#idNotificacionT").value = arrNotificacion.id;
    document.querySelector("#tipoNotificacion").value = arrNotificacion.tipo;
    document.querySelector("#txtQuestion").value = arrNotificacion.pregunta;
    document.querySelector("#title").value = arrNotificacion.mensaje.toString().toLowerCase();
}

function fntEditQuestionInfo(element, idQuestion) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModalQ').innerHTML = "Actualizar Pregunta de la Notificacion";
    document.querySelector('#btnTextQ').innerHTML = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Notificaciones/getQuestion/' + idQuestion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objAnswers = objData.data.answers;
                //console.log(objData.data);
                document.querySelector("#idNotificacionQ").value = objData.data.idNot;
                document.querySelector("#idQ").value = objData.data.id;
                document.querySelector("#titleQ").value = objData.data.mensaje.toString().toLowerCase();
                document.querySelector("#titleQ").disabled = true;
                document.querySelector("#Question").value = objData.data.pregunta.toString().toLowerCase();
                for (let i = 0; i < objAnswers.length; i++) {
                    document.getElementById(`idres${i + 1}`).value = objAnswers[i].id;
                    document.getElementById(`respu${i + 1}`).value = objAnswers[i].respuesta.toString().toLowerCase();
                    document.getElementById(`conse${i + 1}`).value = objAnswers[i].consejo.toString().toLowerCase();
                }

                $("#modalFormQuestions").modal('show');
            }

        }
    };
}

function viewFormVideoMessageNotificacion(arrNotificacion) {
    console.log(arrNotificacion);
    document.querySelector('#titleModal').innerHTML = "Actualizar Notificacion";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    cambiarDisplay(arrNotificacion.tipo);
    document.querySelector("#titleMessage").value = arrNotificacion.mensaje.toString().toLowerCase();
    document.querySelector("#idNotificacion").value = arrNotificacion.id;
    document.querySelector("#listTipoNotificacion").value = arrNotificacion.tipo;
    document.querySelector("#idQuestion").value = arrNotificacion.notifacion_message.id;
    document.querySelector("#idAnswers").value = arrNotificacion.notifacion_message.idRes;
    document.querySelector("#message").value = arrNotificacion.notifacion_message.pregunta.toString().toLowerCase();
    document.querySelector("#response").value = arrNotificacion.notifacion_message.respuesta.toString().toLowerCase();
    document.querySelector("#advice").value = arrNotificacion.notifacion_message.consejo.toString().toLowerCase();
    $("#modalFormNotificaciones").modal('show');
}

function cerrarModal() {
    $("#modalQuestionList").modal('show');
}


function fntDelInfo(idnotificacion) {
    swal({
        title: "Inhabilitar Notificacion",
        text: "¿Realmente quiere inhabilitar esta notificacion?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Notificaciones/setStatusNotificacion';
            let strData = "idNotificacion=" + idnotificacion + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitada!", objData.msg, "success");
                        tableNotificaciones.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}

function fntActivateInfo(idnotificacion) {
    swal({
        title: "Habilitar Notificacion",
        text: "¿Realmente quiere habilitar esta notificacion?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Notificaciones/setStatusNotificacion';
            let strData = "idNotificacion=" + idnotificacion + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Activada!", objData.msg, "success");
                        tableNotificaciones.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}


function fntDelQuestionInfo(idquestion) {
    swal({
        title: "Remover Pregunta",
        text: "¿Realmente quiere eliminar esta pregunta?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Notificaciones/removeQuestionNotificacion';
            let strData = "idQuestion=" + idquestion;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Exito !!", objData.msg, "success");
                        tableQuestions.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}



function generarReporte() {
    $.post(base_url + "/Notificaciones/getNotificacionesReport",
            function (response) {
                var fecha = new Date();
                let notificaciones = JSON.parse(response);
                //console.log(notificaciones);
                //console.log(tecnicos);
                let estado = "";
                var pdf = new jsPDF();
                var columns = ["NRO", "NOMBRE", "TIPO", "CONTENIDO", "ESTADO"];
                var data = [];

                for (let i = 0; i < notificaciones.length; i++) {
                    let CONTENIDO = "";
                    if (notificaciones[i].status == 1) {
                        estado = "ACTIVO";
                    } else {
                        estado = "INACTIVO";
                    }

                    let pregunta, respuesta, consejo;

                    for (let j = 0; j < notificaciones[i].notifacion_message.length; j++) {
                        if (notificaciones[i].tipo != "Video/Mensaje") {
                            pregunta = notificaciones[i].notifacion_message[j].pregunta;
                            respuesta = notificaciones[i].notifacion_message[j].AnswersQ ? notificaciones[i].notifacion_message[j].AnswersQ.map(a => a.respuesta).join("\n") : "";
                            consejo = notificaciones[i].notifacion_message[j].AnswersQ ? notificaciones[i].notifacion_message[j].AnswersQ.map(a => a.consejo).join("\n") : "";
                        }
                        CONTENIDO += `- ${pregunta} \n - Respuesta: ${respuesta} \n - Consejo: ${consejo} \n`;
                    }

                    if (notificaciones[i].tipo != "Pregunta") {
                        pregunta = notificaciones[i].notifacion_message.pregunta;
                        respuesta = notificaciones[i].notifacion_message.respuesta;
                        consejo = notificaciones[i].notifacion_message.consejo;
                        CONTENIDO += `- ${pregunta} \n -  ${respuesta} \n - ${consejo} \n`;
                    }

                    data[i] = [(i + 1), notificaciones[i].mensaje, notificaciones[i].tipo, CONTENIDO, estado];
                }


                pdf.text(20, 20, "Reportes de las Notificaciones Registradas");

                pdf.autoTable(columns, data, {
                    startY: 40,
                    styles: {
                        cellPadding: 10,
                        fontSize: 8,
                        font: 'helvetica',
                        textColor: [0, 0, 0],
                        fillColor: [255, 255, 255],
                        lineWidth: 0.1,
                        halign: 'center',
                        valign: 'middle'
                    },
                    drawRow: function (row, data) {
                        if (data[2] === 'Pregunta') {
                            row.cells[2].styles.fontStyle = 'bold';
                        }
                    }
                });



                pdf.text(20, pdf.autoTable.previous.finalY + 20, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                pdf.save('ReporteNotificaciones.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
            }
    );
}