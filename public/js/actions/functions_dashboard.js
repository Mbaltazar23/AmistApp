function chunk(array, size) {
    return array.reduce(
        (acc, _, i) => (i % size ? acc : [...acc, array.slice(i, i + size)]),
        []
    );
}

function getNotificationQuest() {
    var notificationId = event.target.getAttribute("data-notification-id");
    //console.log(notificationId);
    axios
        .get(`/notificationQuest/getQuestion/${notificationId}`)
        .then(function (response) {
            const modalDialog = document.querySelector(".modal-dialog");
            if (response.data.data.tipo == "Question") {
                modalDialog.classList.add("modal-lg");
                viewPreguntaNotificacion(response.data.data);
            } else {
                modalDialog.classList.remove("modal-lg");
                viewVideoMessageNotificacion(response.data.data);
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function viewPreguntaNotificacion(arrNotificacion) {
    document.querySelector("#titleModal").innerHTML =
        'Pregunta del Día :  "<strong>' +
        arrNotificacion.mensaje +
        '</strong>"';
    const notifId = arrNotificacion.id;
    const poinst = arrNotificacion.puntos;
    // Obtiene las preguntas y sus alternativas de la notificación
    const preguntas = arrNotificacion.notifacion_message;
    const preguntasHtml = chunk(preguntas, 1)
        .map((preguntasPar) =>
            preguntasPar
                .map(
                    (pregunta, index) =>
                        `
    <div class="pregunta-container col-md-4">
      <p class="pregunta-text">${pregunta.pregunta}</p>
      ${pregunta.respuestas
          .map(
              (respuesta) =>
                  `<div class="form-check icheck-primary">
                   <input class="form-check-input" type="radio" name="pregunta_${index}" id="alternativa_${
                      respuesta.id
                  }" value="${respuesta.id}" data-consejo="${
                      respuesta.consejo || ""
                  }"> 
                   <label class="form-check-label" for="alternativa_${
                       respuesta.id
                   }"> 
                   ${respuesta.respuesta} </label> 
                   </div>`
          )
          .join("")}
    </div> <br>`
                )
                .join("")
        )
        .join("");

    const formContent = `
<div class="row">
    <input type="hidden" name="idNot" id="idNot" value="${notifId}" />
    <input type="hidden" name="points" id="points" value="${poinst}" />
    <input type="hidden" name="type" id="type" value="${arrNotificacion.type}" />
    ${preguntasHtml}
</div>
<br>
<div class="modal-footer">
   <button type="button" class="btn btn-secondary" data-dismiss="modal">
   <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
   <button type="submit" class="btn btn-primary">
   <i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Enviar respuesta</button>
</div>`;

    formNotificationResponse.innerHTML = formContent;

    $("#modalFormNotificationResponse").modal("show");
}

function viewVideoMessageNotificacion(arrNotificacion, color) {
    //obtenemos el id encriptado del notification
    const notifId = arrNotificacion.id;
    const poinst = arrNotificacion.puntos;
    // obtener elementos del modal
    const titleModal = document.getElementById("titleModal");
    const formNotificationResponse = document.getElementById(
        "formNotificationResponse"
    );
    // establecer título del modal
    titleModal.innerText = "Pregunta del Día";
    // generar contenido para el cuerpo del modal
    const colors = [
        "primary",
        "secondary",
        "success",
        "warning",
        "danger",
        "dark",
        "light",
        "orange",
        "purple",
        "green",
    ];
    const randomColor =
        color || colors[Math.floor(Math.random() * colors.length)];
    // generar contenido para el cuerpo del modal
    const preguntaRespuesta = arrNotificacion.notifacion_message
        .map((mensaje) => {
            return `
       <div class="alert alert-${randomColor} alert-dismissible">
         <h5>${mensaje.pregunta}</h5>
         <p>${mensaje.respuesta}</p>
       </div>
     `;
        })
        .join("");

    const formContent = `
    <input type="hidden" name="idNot" id="idNot" value="${notifId}" />
    <input type="hidden" name="consejo" id="consejo" value="${arrNotificacion.notifacion_message[0].consejo}"/>
    <input type="hidden" name="points" id="points" value="${poinst}" />
    <input type="hidden" name="type" id="type" value="${arrNotificacion.type}" />
    <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">
            <i class="icon fas fa-info"></i>&nbsp;
            ${arrNotificacion.mensaje}
          </h3>
        </div>
        <div class="card-body">
          ${preguntaRespuesta}
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
      <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
    </button>
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Enviar respuesta
    </button>
  </div>`;

    formNotificationResponse.innerHTML = formContent;

    // Mostrar el modal
    $("#modalFormNotificationResponse").modal("show");
}

if (document.querySelector("#formNotificationResponse")) {
    let formNotificationResponse = document.querySelector(
        "#formNotificationResponse"
    );
    formNotificationResponse.onsubmit = function (e) {
        e.preventDefault();
        let typeInput = document.querySelector("#type").value;

        if (typeInput === "Pregunta") {
            let radioButtons = document.querySelectorAll("input[type='radio']");
            let radioChecked = false;

            radioButtons.forEach((radio) => {
                if (radio.checked) {
                    radioChecked = true;
                }
            });

            if (!radioChecked) {
                swal(
                    "Atención !!",
                    "Seleccione una alternativa porfavor !!",
                    "error"
                );
                return;
            }
        }

        // Obtener el valor del consejo del input seleccionado
        const selectedInput = document.querySelector(
            "input[type='radio']:checked"
        );
        const consejo = selectedInput
            ? selectedInput.getAttribute("data-consejo")
            : document.querySelector("#consejo").value;

        let points = document.querySelector("#points").value;

        swal({
            title: "Responder Notificacion",
            text: "¿Quiere responder y ganar " + points + " puntos ?",
            icon: "info",
            buttons: true,
        }).then((isClosed) => {
            if (isClosed) {
                {
                    let formData = new FormData(formNotificationResponse);
                    formData.append("consejo", consejo);

                    axios
                        .post(`/notificationQuest/setQuestionNot`, formData)
                        .then((response) => {
                            swal({
                                title: "Exito !",
                                text: response.data.msg,
                                icon: "success",
                            }).then(function () {
                                location.reload();
                            });
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                }
            }
        });
    };
}
