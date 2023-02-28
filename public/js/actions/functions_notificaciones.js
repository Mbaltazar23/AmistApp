let tableNotificaciones, tableQuestions;
let rowTable = "";
$(document).on("focusin", function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

document.addEventListener(
    "DOMContentLoaded",
    function () {
        tableNotificaciones = $("#tableNotificaciones").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nro" },
                { data: "mensaje" },
                { data: "tipo" },
                { data: "fecha" },
                { data: "status" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/notifications",
                method: "GET",
                dataSrc: function (json) {
                    if (!json.status) {
                        console.error(json.message);
                        return [];
                    }
                    return json.data;
                },
            },
            paging: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            bDestroy: true,
            iDisplayLength: 10,
            order: [[0, "asc"]],
        });

        if (document.querySelector("#formNotificaciones")) {
            let formNotificaciones = document.querySelector(
                "#formNotificaciones"
            );
            formNotificaciones.onsubmit = function (e) {
                e.preventDefault();

                let listTipoNotificacion = document.querySelector(
                    "#listTipoNotificacion"
                ).value;
                let idNot = document.querySelector("#idNotificacion").value;
                let idQ = document.querySelector("#idQuestion").value;
                let idA = document.querySelector("#idAnswers").value;
                let questionAndAnswers = [];

                if (listTipoNotificacion == "") {
                    swal(
                        "Error !!",
                        "No selecciono el tipo de Notificacion a registrar !!",
                        "error"
                    );
                    return false;
                } else {
                    if (listTipoNotificacion == "Question") {
                        var title =
                            document.getElementById("titleQuestion").value;
                        var question =
                            document.getElementById("question").value;
                        var points =
                            document.getElementById("pointsQuestion").value;
                        var answer1 = document.getElementById("answer1").value;
                        var answer2 = document.getElementById("answer2").value;
                        var answer3 = document.getElementById("answer3").value;
                        var advice1 = document.getElementById("advice1").value;
                        var advice2 = document.getElementById("advice2").value;
                        var advice3 = document.getElementById("advice3").value;
                        var answers = [
                            { answer: answer1, advice: advice1 },
                            { answer: answer2, advice: advice2 },
                            { answer: answer3, advice: advice3 },
                        ];

                        questionAndAnswers = { answers: answers };

                        if (
                            !title ||
                            !question ||
                            !points ||
                            !answer1 ||
                            !advice1 ||
                            !answer2 ||
                            !advice2 ||
                            !answer3 ||
                            !advice3
                        ) {
                            swal(
                                "Error !!",
                                "Faltan campos por completar !!",
                                "error"
                            );
                            return false;
                        }

                        axios
                            .post("/notifications/setNotification", {
                                idNotificacion: idNot,
                                Question: question,
                                Answers: questionAndAnswers,
                                listTipoNotificacion: listTipoNotificacion,
                                pointsQuestion: points,
                                title: title,
                            })
                            .then(function (response) {
                                if (response.status) {
                                    tableNotificaciones.api().ajax.reload();
                                    $("#modalFormNotificaciones").modal("hide");
                                    swal(
                                        "Exito !!",
                                        response.data.msg,
                                        "success"
                                    );
                                } else {
                                    swal("Error", response.data.msg, "error");
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                                swal(
                                    "Error",
                                    "Ocurrió un error al procesar la petición",
                                    "error"
                                );
                            });
                    } else {
                        var message = document.getElementById("message").value;
                        var response =
                            document.getElementById("response").value;
                        var points =
                            document.getElementById("pointsMessage").value;
                        var advice = document.getElementById("advice").value;
                        var titleMessage =
                            document.getElementById("titleMessage").value;

                        if (!message || !response || !advice || !titleMessage) {
                            swal(
                                "Error !!",
                                "Faltan campos por completar !!",
                                "error"
                            );
                            return false;
                        }

                        axios
                            .post("/notifications/setNotification", {
                                idNotificacion: idNot,
                                idQuestion: idQ,
                                idAnswers: idA,
                                listTipoNotificacion: listTipoNotificacion,
                                Message: message,
                                Response: response,
                                Advice: advice,
                                pointsMessage: points,
                                title: titleMessage,
                            })
                            .then(function (response) {
                                if (response.data.status) {
                                    tableNotificaciones.api().ajax.reload();
                                    $("#modalFormNotificaciones").modal("hide");
                                    swal(
                                        "Exito !!",
                                        response.data.msg,
                                        "success"
                                    );
                                } else {
                                    swal("Error", response.data.msg, "error");
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                                swal(
                                    "Error",
                                    "Ocurrió un error al procesar la petición",
                                    "error"
                                );
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
                    { id: idres1, answer: answer1, advice: advice1 },
                    { id: idres2, answer: answer2, advice: advice2 },
                    { id: idres3, answer: answer3, advice: advice3 },
                ];

                let questionAndAnswers = { answers: answers };

                if (
                    !question ||
                    !answer1 ||
                    !advice1 ||
                    !answer2 ||
                    !advice2 ||
                    !answer3 ||
                    !advice3
                ) {
                    swal("Error !!", "Faltan campos por completar !!", "error");
                    return false;
                } else {
                    axios
                        .post("/notifications/setQuestion", {
                            idNotificacion: idNot,
                            idQuestion: idQ,
                            Question: question,
                            Answers: questionAndAnswers,
                        })
                        .then(function (response) {
                            if (response.data.status) {
                                tableQuestions.api().ajax.reload();
                                $("#modalFormQuestions").modal("hide");
                                cerrarModal();
                                swal("Exito !!", response.data.msg, "success");
                            } else {
                                swal("Error", response.data.msg, "error");
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                            swal(
                                "Error",
                                "Ocurrió un error al procesar la petición",
                                "error"
                            );
                        });
                }
            };
        }

        if (document.querySelector("#formTitleN")) {
            let formTitleN = document.querySelector("#formTitleN");
            formTitleN.onsubmit = function (e) {
                e.preventDefault();
                let idNot = document.querySelector("#idNotificacionT").value;
                let tipoNotificacion =
                    document.querySelector("#tipoNotificacion").value ==
                    "Pregunta"
                        ? "Question"
                        : "";
                let titleNotificacion = document.querySelector("#title").value;
                let question = document.querySelector("#txtQuestion").value;
                let points = document.querySelector("#points").value;
                if (titleNotificacion == "" || points == "") {
                    swal(
                        "Error !!",
                        "El titulo o puntaje no pueden estar vacios !!",
                        "error"
                    );
                    return false;
                } else {
                    axios
                        .post("/notifications/setNotification", {
                            idNotificacion: idNot,
                            listTipoNotificacion: tipoNotificacion,
                            title: titleNotificacion,
                            Question: question,
                            pointsQuestion: points,
                        })
                        .then(function (response) {
                            if (response.data.status) {
                                tableNotificaciones.api().ajax.reload();
                                $("#modalFormNotificacionTitle").modal("hide");
                                swal("Exito !!", response.data.msg, "success");
                            } else {
                                swal("Error", response.data.msg, "error");
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                            swal(
                                "Error",
                                "Ocurrió un error al procesar la petición",
                                "error"
                            );
                        });
                }
            };
        }
    },
    false
);

function openModal() {
    document.querySelector("#idNotificacion").value = "";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nueva Notificacion";
    document.querySelector("#formNotificaciones").reset();
    document.querySelector("#notificacionesPreguntas").style.display = "none";
    document.querySelector("#notificacionesMessage").style.display = "none";
    document.querySelector(".modal-dialog").classList.remove("modal-lg");
    $("#modalFormNotificaciones").modal("show");
    vaciarCampos();
}

function cambiarDisplay(value) {
    if (value == "Question") {
        document.querySelector("#notificacionesPreguntas").style.display =
            "block";
        document.querySelector(".modal-dialog").classList.add("modal-lg");
        document.querySelector("#notificacionesMessage").style.display = "none";
        vaciarCampos();
    } else if (value == "Video/Message") {
        document.querySelector("#notificacionesPreguntas").style.display =
            "none";
        document.querySelector(".modal-dialog").classList.remove("modal-lg");
        document.querySelector("#notificacionesMessage").style.display =
            "block";
        vaciarCampos();
    } else {
        document.querySelector("#notificacionesPreguntas").style.display =
            "none";
        document.querySelector("#notificacionesMessage").style.display = "none";
        document.querySelector(".modal-dialog").classList.remove("modal-lg");
        vaciarCampos();
    }
}

function vaciarCampos() {
    document.querySelector(
        "#notificacionesPreguntas input[type='text']"
    ).value = "";
    document.querySelector("#notificacionesMessage textarea").value = "";
    document.querySelector("#notificacionesPreguntas textarea").value = "";
}

function fntViewInfo(nro, idNotificacion) {
    document.querySelector("#celContenido").innerHTML = "";
    axios
        .get(`/notifications/getNotification/${idNotificacion}`)
        .then(function (response) {
            if (response.data.status) {
                let estado =
                    response.data.data.status == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML =
                    response.data.data.mensaje;
                document.querySelector("#celTipo").innerHTML =
                    response.data.data.type;
                document.querySelector("#celPuntos").innerHTML =
                    response.data.data.puntos;
                document.querySelector("#celFecha").innerHTML =
                    response.data.data.fecha;
                document.querySelector("#celHora").innerHTML =
                    response.data.data.hora;
                document.querySelector("#celStatus").innerHTML = estado;

                let objTipo = response.data.data.tipo;
                let objQuestions = response.data.data.notifacion_message;
                console.log(objQuestions);
                if (objTipo == "Question") {
                    let pregunta = objQuestions.pregunta;
                    let answersQ = objQuestions.AnswersQ;

                    let answersHTML = "<ul>";
                    answersQ.forEach(function (a) {
                        answersHTML += `<li> ${a.respuesta}  -  ${
                            a.consejo || ""
                        } </li>`;
                    });
                    answersHTML += "</ul>";

                    document.querySelector(
                        "#celContenido"
                    ).innerHTML += `<b>${pregunta}</b> ${answersHTML}`;
                } else {
                    let objNotifacionMessage =
                        response.data.data.notifacion_message[0];

                    document.querySelector("#celContenido").innerHTML += `<ul>
                                                        <li>${
                                                            objNotifacionMessage.pregunta
                                                        }</li>
                                                        <li>${
                                                            objNotifacionMessage.respuesta
                                                        }</li>
                                                        <li>${
                                                            objNotifacionMessage.consejo ||
                                                            ""
                                                        }</li>
                                                   </ul>`;
                }
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
    $("#modalViewNotificacion").modal("show");
}

function fntEditInfo(element, idNotificacion) {
    rowTable = element.parentNode.parentNode.parentNode;
    axios
        .get(`/notifications/getNotification/${idNotificacion}`)
        .then(function (response) {
            if (response.data.status) {
                if (response.data.data.tipo == "Question") {
                    viewListPreguntaNotificacion(response.data.data);
                } else {
                    viewFormVideoMessageNotificacion(response.data.data);
                }
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function viewListPreguntaNotificacion(arrNotificacion) {
    document.querySelector("#titleModalL").innerHTML =
        "Preguntas de la Notificacion - " + arrNotificacion.mensaje;
    loadQuestions(arrNotificacion.id);
    $("#modalQuestionList").modal("show");
    document
        .querySelector("#btnAddQuestion")
        .addEventListener("click", function () {
            viewFormPreguntaNotificacion(arrNotificacion);
            $("#modalQuestionList").modal("hide");
            $("#modalFormQuestions").modal("show");
        });
    document
        .querySelector("#btnNotificacion")
        .addEventListener("click", function () {
            viewFormTitleNotificacion(arrNotificacion);
            $("#modalQuestionList").modal("hide");
            $("#modalFormNotificacionTitle").modal("show");
        });
}

function loadQuestions(idNotificacion) {
    tableQuestions = $("#tableQuestions").dataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
        },
        columns: [
            { data: "nro" },
            { data: "pregunta" },
            { data: "fecha" },
            { data: "hora" },
            {
                data: "options",
                render: function (data) {
                    return data;
                },
            },
        ],
        ajax: {
            url: `/notifications/questions/${idNotificacion}`,
            method: "GET",
            dataSrc: function (json) {
                if (!json.status) {
                    console.error(json.message);
                    return [];
                }
                return json.data;
            },
        },
        paging: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        bDestroy: true,
        iDisplayLength: 10,
        order: [[0, "asc"]],
    });
}

function viewFormPreguntaNotificacion(arrNotificacion) {
    let form = document.querySelector("#formQuestions");
    let inputs = form.getElementsByTagName("input");
    let textAreas = form.getElementsByTagName("textarea");
    // Resetear el valor de los inputs
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].value = "";
    }

    for (let i = 0; i < textAreas.length; i++) {
        textAreas[i].value = "";
    }

    document.querySelector("#idQ").value = "";
    document.querySelector("#titleModalQ").innerHTML =
        "Nueva Pregunta a la Notificacion";
    document.querySelector("#idNotificacionQ").value = arrNotificacion.id;
    document.querySelector("#titleQ").value = arrNotificacion.mensaje
        .toString()
        .toLowerCase();
    document.querySelector("#titleQ").disabled = true;
    document.querySelector("#btnTextQ").innerHTML = "  Guardar";
}

function viewFormTitleNotificacion(arrNotificacion) {
    document.querySelector("#titleModalT").innerHTML =
        "Actualizar Titulo de la Notificacion";
    document.querySelector("#idNotificacionT").value = arrNotificacion.id;
    document.querySelector("#tipoNotificacion").value = arrNotificacion.type;
    document.querySelector("#title").value = arrNotificacion.mensaje
        .toString()
        .toLowerCase();
    document.querySelector("#points").value = arrNotificacion.puntos;
}

function fntEditQuestionInfo(element, idQuestion) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModalQ").innerHTML =
        "Actualizar Pregunta de la Notificacion";
    document.querySelector("#btnTextQ").innerHTML = "  Actualizar";
    axios
        .get(`/notifications/question/${idQuestion}`)
        .then(function (response) {
            if (response.data.status) {
                let objAnswers = response.data.data.answers;

                console.log(objAnswers);
                //console.log(objData.data);
                document.querySelector("#idNotificacionQ").value =
                    response.data.data.idNot;
                document.querySelector("#idQ").value = response.data.data.id;
                document.querySelector("#titleQ").value =
                    response.data.data.mensaje.toString().toLowerCase();
                document.querySelector("#titleQ").disabled = true;
                document.querySelector("#Question").value =
                    response.data.data.pregunta.toString().toLowerCase();
                for (let i = 0; i < objAnswers.length; i++) {
                    document.getElementById(`idres${i + 1}`).value =
                        objAnswers[i].id;
                    document.getElementById(`respu${i + 1}`).value = objAnswers[
                        i
                    ].text_answer
                        .toString()
                        .toLowerCase();
                    document.getElementById(`conse${i + 1}`).value = objAnswers[
                        i
                    ].advice
                        .toString()
                        .toLowerCase();
                }

                $("#modalFormQuestions").modal("show");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function viewFormVideoMessageNotificacion(arrNotificacion) {
    console.log(arrNotificacion);
    document.querySelector("#titleModal").innerHTML = "Actualizar Notificacion";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "Actualizar";
    cambiarDisplay(arrNotificacion.tipo);
    document.querySelector("#titleMessage").value = arrNotificacion.mensaje
        .toString()
        .toLowerCase();
    document.querySelector("#idNotificacion").value = arrNotificacion.id;
    document.querySelector("#listTipoNotificacion").value =
        arrNotificacion.tipo;
    document.querySelector("#pointsMessage").value = arrNotificacion.puntos;
    document.querySelector("#idQuestion").value =
        arrNotificacion.notifacion_message[0].id;
    document.querySelector("#idAnswers").value =
        arrNotificacion.notifacion_message[0].idRes;
    document.querySelector("#message").value =
        arrNotificacion.notifacion_message[0].pregunta.toString().toLowerCase();
    document.querySelector("#response").value =
        arrNotificacion.notifacion_message[0].respuesta
            .toString()
            .toLowerCase();
    document.querySelector("#advice").value =
        arrNotificacion.notifacion_message[0].consejo.toString().toLowerCase();
    $("#modalFormNotificaciones").modal("show");
}

function cerrarModal() {
    $("#modalQuestionList").modal("show");
}

function fntDelInfo(idnotificacion) {
    swal({
        title: "Inhabilitar Notificacion",
        text: "¿Realmente quiere inhabilitar esta notificacion?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios
                .post(`/notifications/status/${idnotificacion}`, {
                    status: status,
                })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitada!", response.data.msg, "success");
                        tableNotificaciones.api().ajax.reload();
                    } else {
                        swal("Atención!", response.data.msg, "error");
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    });
}

function fntActivateInfo(idnotificacion) {
    swal({
        title: "Habilitar Notificacion",
        text: "¿Realmente quiere habilitar esta notificacion?",
        icon: "info",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios
                .post(`/notifications/status/${idnotificacion}`, {
                    status: status,
                })
                .then((response) => {
                    if (response.data.status) {
                        swal("Habilitada !!", response.data.msg, "success");
                        tableNotificaciones.api().ajax.reload();
                    } else {
                        swal("Atención!", response.data.msg, "error");
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    });
}

function fntDelQuestionInfo(idquestion) {
    swal({
        title: "Remover Pregunta",
        text: "¿Realmente quiere eliminar esta pregunta?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            axios
                .post(`/notifications/questionDel/${idquestion}`)
                .then((response) => {
                    if (response.data.status) {
                        swal("Exito!", response.data.msg, "success");
                        tableQuestions.api().ajax.reload();
                    } else {
                        swal("Atención!", response.data.msg, "error");
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    });
}

function generarReporte() {
    axios
        .post("/notifications/report")
        .then(function (response) {
            var fecha = new Date();
            // Obtener las notificaciones del cuerpo de la respuesta
            let notificaciones = response.data.data;
            var pdf = new jsPDF();
            var columns = ["NRO", "NOMBRE", "TIPO", "CONTENIDO", "ESTADO"];
            var data = [];
            for (let i = 0; i < notificaciones.length; i++) {
                // ...código para crear cada fila de la tabla...
                data[i] = [
                    i + 1,
                    notificaciones[i].NOMBRE,
                    notificaciones[i].TIPO,
                    notificaciones[i].CONTENIDO,
                    notificaciones[i].ESTADO,
                ];
            }

            pdf.text(20, 20, "Reportes de las Notificaciones Registradas");

            pdf.autoTable(columns, data, {
                startY: 40,
                styles: {
                    cellPadding: 10,
                    fontSize: 8,
                    font: "helvetica",
                    textColor: [0, 0, 0],
                    fillColor: [255, 255, 255],
                    lineWidth: 0.1,
                    halign: "center",
                    valign: "middle",
                },
            });

            pdf.text(
                20,
                pdf.autoTable.previous.finalY + 20,
                "Fecha de Creacion : " +
                    fecha.getDate() +
                    "/" +
                    (fecha.getMonth() + 1) +
                    "/" +
                    fecha.getFullYear()
            );
            pdf.save("ReporteNotificaciones.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
