let tableAlumns;
let rowTable = "";
document.addEventListener(
    "DOMContentLoaded",
    function () {
        tableAlumns = $("#tableAlumnsCom").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "dni" },
                { data: "nombre" },
                { data: "email" },
                { data: "puntos" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/alumns-teacher",
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

        if (document.querySelector("#formPoints")) {
            let formPoints = document.querySelector("#formPoints");
            formPoints.onsubmit = function (e) {
                e.preventDefault();
                let listActions = document.querySelector("#listActions").value;
                let points = document.querySelector("#pointsInput").value;
                if (listActions == "") {
                    swal(
                        "Atención !!",
                        "Debe seleccionar una accion a realizar",
                        "error"
                    );
                    return false;
                } else {
                    swal({
                        title: "Dar puntos",
                        text:
                            "¿Realmente quiere perder " + points + " puntos ?",
                        icon: "info",
                        buttons: true,
                    }).then((isClosed) => {
                        if (isClosed) {
                            let formData = new FormData(formPoints);
                            axios
                                .post(`/companios/donate`, formData)
                                .then((response) => {
                                    if (response.data.status) {
                                        $("#modalFormPoints").modal('hide');
                                        swal(
                                            "Exito !!",
                                            response.data.msg,
                                            "success"
                                        );
                                        tableAlumns.api().ajax.reload();
                                    } else {
                                        swal(
                                            "Atención!",
                                            response.data.msg,
                                            "error"
                                        );
                                    }
                                })
                                .catch((error) => {
                                    console.error(error);
                                });
                        }
                    });
                }
            };
        }
        fntActions();
    },
    false
);

function fntCanjPoints(idAlum) {
    document.querySelector("#titleModal").innerHTML = "Donar puntos al Alumnos";
    document.querySelector("#btnText").innerHTML = "  Enviar";
    axios
        .get(`/companios/alum/${idAlum}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idUserSen").value =
                    response.data.data.idUserS;
                document.querySelector("#idUserRec").value =
                    response.data.data.id;
                document.querySelector("#txtRutAlu").value =
                    response.data.data.dni;
                document.querySelector("#txtNombres").value =
                    response.data.data.nombre.toString().toLowerCase();
                document.querySelector("#txtCorreoAlu").value =
                    response.data.data.correo.toString().toLowerCase();
            }
            $("#modalFormPoints").modal("show");
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntActions() {
    if (document.querySelector("#listActions")) {
        axios
            .post("/actions/select")
            .then((response) => {
                $(".selectActions select").html(response.data).fadeIn();
            })
            .catch((error) => {
                console.log(error);
            });
    }
}

function loadPoints() {
    // Obtener el elemento select y el input hidden
    const select = document.getElementById("listActions");
    const pointsInput = document.getElementById("pointsInput");
    // Obtener la opción seleccionada
    const selectedOption = select.options[select.selectedIndex];
    // Obtener el valor de points asociado a la opción seleccionada
    const points = selectedOption.getAttribute("data-points");
    // Asignar el valor de points al input hidden
    pointsInput.value = points;
}
