let tableColegio;
let rowTable = "";
document.addEventListener(
    "DOMContentLoaded",
    function () {
        validadorRut("txtRut");
        tableColegio = $("#tableColegios").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "rut" },
                { data: "nombre" },
                { data: "telefono" },
                { data: "status" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/colleges",
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

        let formColegio = document.querySelector("#formColegio");
        formColegio.onsubmit = function (e) {
            e.preventDefault();
            let txtRut = document.querySelector("#txtRut").value;
            let strNombre = document.querySelector("#txtNombre").value;
            let txtTelefono = $("#txtTelefono").val();
            //var regexClave = new RegExp("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$");
            var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;
            if (strNombre == "" || txtRut == "" || txtTelefono == "") {
                swal(
                    "Atención",
                    "Debe ingresar datos para crear al Admin",
                    "error"
                );
                return false;
            } else if (!regulTele.test(txtTelefono.trim())) {
                swal(
                    "Por favor",
                    "Ingrese un Telefono Valido para registrarse...",
                    "error"
                );
                $("#txtTelefono").val("+569");
                return false;
            } else {
                let formData = new FormData(formColegio);
                axios
                    .post("/colleges/setCollege", formData)
                    .then(function (response) {
                        if (response.status) {
                            tableColegio.api().ajax.reload();
                            $("#modalFormColegios").modal("hide");
                            formColegio.reset();
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
    },
    false
);

function fntViewInfo(idColegio) {
    axios
        .get(`/colleges/getCollege/${idColegio}`)
        .then(function (response) {
            if (response.data.status) {
                let estado =
                    response.data.data.status == 1
                        ? '<span class="badge badge-success">En espera</span>'
                        : '<span class="badge badge-dark">Vinculado</span>';

                let direccion =
                    response.data.data.direccion != ""
                        ? response.data.data.direccion
                        : "No se tiene una direccion registrada";
                document.querySelector("#celRut").innerHTML =
                    response.data.data.rut;
                document.querySelector("#celNombre").innerHTML =
                    response.data.data.nombre;
                document.querySelector("#celTelefono").innerHTML =
                    response.data.data.telefono;
                document.querySelector("#celDireccion").innerHTML = direccion;
                document.querySelector("#celFecha").innerHTML =
                    response.data.data.fecha;
                document.querySelector("#celHora").innerHTML =
                    response.data.data.hora;
                document.querySelector("#celStatus").innerHTML = estado;

                $("#modalViewAdmin").modal("show");
            } else {
                swal("Error", response.data.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntEditInfo(element, idColegio) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").innerHTML = "Actualizar Colegio";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "Actualizar";
    axios
        .get(`/colleges/getCollege/${idColegio}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idColegio").value =
                    response.data.data.id;
                document.querySelector("#txtRut").value =
                    response.data.data.rut;
                document.querySelector("#txtNombre").value =
                    response.data.data.nombre.toString().toLowerCase();
                document.querySelector("#txtTelefono").value =
                    response.data.data.telefono;
                document.querySelector("#txtDireccion").value =
                    response.data.data.direccion.toString().toLowerCase();

                $("#modalFormColegios").modal("show");
            } else {
                swal("Error", response.data.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntDelInfo(idcolegio) {
    swal({
        title: "Inhabilitar Colegio",
        text: "¿Realmente quiere inhabilitar a este colegio?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios
                .post(`/colleges/status/${idcolegio}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitada!", response.data.msg, "success");
                        tableColegio.api().ajax.reload();
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

function fntActivateInfo(idcolegio) {
    swal({
        title: "Habilitar Colegio",
        text: "¿Realmente quiere habilitar a este colegio?",
        icon: "info",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios
                .post(`/colleges/status/${idcategoria}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Activada !!", response.data.msg, "success");
                        tableColegio.api().ajax.reload();
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
    document.querySelector("#idColegio").value = "";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nuevo Colegio";
    document.querySelector("#formColegio").reset();
    $("#modalFormColegios").modal("show");
}

function generarReporte() {
    axios
        .post("/colleges/report")
        .then(function (response) {
            var fecha = new Date();
            let colegios = response.data.data;
            //console.log(notificaciones);
            //console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            var columns = ["RUT", "NOMBRE", "TELEFONO", "DIRECCION", "ESTADO"];
            var data = [];

            for (let i = 0; i < colegios.length; i++) {
                if (colegios[i].status == 1) {
                    estado = "ACTIVO";
                } else {
                    estado = "INACTIVO";
                }
                data[i] = [
                    colegios[i].dni,
                    colegios[i].name,
                    colegios[i].phone,
                    colegios[i].address != ""
                    ? colegios[i].address
                    : "No tiene una dirección registrada",
                    estado,
                ];
            }

            pdf.text(20, 20, "Reportes de las colegios Registradas");

            pdf.autoTable(columns, data, {
                startY: 40,
                styles: {
                    cellPadding: 6,
                    fontSize: 6,
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
            pdf.save("ReporteColegios.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
