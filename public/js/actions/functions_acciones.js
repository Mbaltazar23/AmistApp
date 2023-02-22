let tableAcciones;
let rowTable = "";
document.addEventListener(
    "DOMContentLoaded",
    function () {
        tableAcciones = $("#tableAcciones").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nro" },
                { data: "nombre" },
                { data: "puntos" },
                { data: "fecha" },
                { data: "hora" },
                { data: "status" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/actions",
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

        //NUEVA ACCION
        let formAccion = document.querySelector("#formAccion");
        formAccion.onsubmit = function (e) {
            e.preventDefault();
            let strNombre = document.querySelector("#txtNombre").value;
            let strPuntos = document.querySelector("#txtPuntaje").value;
            if (strNombre == "" || strPuntos == "") {
                swal(
                    "Atención",
                    "Debe ingresar un nombre y su puntaje..",
                    "error"
                );
                return false;
            } else {
                let formData = new FormData(formAccion);
                axios
                    .post("/actions/setAction", formData)
                    .then(function (response) {
                        if (response.data.status) {
                            $("#modalFormAcciones").modal("hide");
                            formAccion.reset();
                            swal("Exito !!", response.data.msg, "success");

                            tableAcciones.api().ajax.reload();
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
    },
    false
);

function fntViewInfo(nro, idaccion) {
    axios
        .get(`/actions/getAction/${idaccion}`)
        .then(function (response) {
            if (response.data.status) {
                let estado =
                    response.data.data.status == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML =
                    response.data.data.nombre;
                document.querySelector("#celPuntaje").innerHTML =
                    response.data.data.puntos;
                document.querySelector("#celEstado").innerHTML = estado;
                document.querySelector("#celFecha").innerHTML =
                    response.data.data.fecha;
                document.querySelector("#celHora").innerHTML =
                    response.data.data.hora;
                $("#modalViewAccion").modal("show");
            } else {
                swal("Error", objData.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntEditInfo(element, idaccion) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").innerHTML = "Actualizar Accion";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "Actualizar";
    axios
        .get(`/actions/getAction/${idaccion}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idAccion").value =
                    response.data.data.id;
                document.querySelector("#txtNombre").value = String(
                    response.data.data.nombre
                ).toLowerCase();
                document.querySelector("#txtPuntaje").value =
                    response.data.data.puntos;

                $("#modalFormAcciones").modal("show");
            } else {
                swal("Error", objData.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntDelInfo(idaccion) {
    swal({
        title: "Inhabilitar Accion",
        text: "¿Realmente quiere inhabilitar esta accion?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios
                .post(`/actions/status/${idaccion}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitada!", response.data.msg, "success");
                        tableAcciones.api().ajax.reload();
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

function fntActivateInfo(idaccion) {
    swal({
        title: "Habilitar Accion",
        text: "¿Realmente quiere habilitar esta accion?",
        icon: "info",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios
                .post(`/actions/status/${idaccion}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Habilitada !!", response.data.msg, "success");
                        tableAcciones.api().ajax.reload();
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

function openModal() {
    document.querySelector("#idAccion").value = "";
    document
        .querySelector(".modal-header")
        .classList.replace("headerUpdate", "headerRegister");
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nueva Accion";
    document.querySelector("#formAccion").reset();
    $("#modalFormAcciones").modal("show");
}

function generarReporte() {
    axios
        .post("/actions/report")
        .then(function (response) {
            var fecha = new Date();
            let acciones = response.data.data;
            //console.log(notificaciones);
            //console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            var columns = [
                "NRO",
                "NOMBRE",
                "PUNTOS",
                "FECHA",
                "HORA",
                "ESTADO",
            ];
            var data = [];

            for (let i = 0; i < acciones.length; i++) {
                if (acciones[i].status == 1) {
                    estado = "ACTIVO";
                } else {
                    estado = "INACTIVO";
                }
                data[i] = [
                    i + 1,
                    acciones[i].nombre,
                    acciones[i].puntos,
                    acciones[i].fecha,
                    acciones[i].hora,
                    estado,
                ];
            }

            pdf.text(20, 20, "Reportes de las Acciones Registradas");

            pdf.autoTable(columns, data, {
                startY: 40,
                styles: {
                    cellPadding: 8,
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
            pdf.save("ReporteAcciones.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
