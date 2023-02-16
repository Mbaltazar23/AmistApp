let tableAdmins;
let rowTable = "";
document.addEventListener(
    "DOMContentLoaded",
    function () {
        validadorRut("txtDni");
        tableAdmins = $("#tableAdmins").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "dni" },
                { data: "nombre" },
                { data: "email" },
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
                url: "/adminsColleges",
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
        //NUEVO ADMIN
        let formAdmin = document.querySelector("#formAdmin");
        formAdmin.onsubmit = function (e) {
            e.preventDefault();
            let txtRut = document.querySelector("#txtDni").value;
            let strNombre = document.querySelector("#txtNombre").value;
            let txtCorreo = $("#txtEmail").val();
            let txtTelefono = $("#txtTelefono").val();
            var regexCoreo =
                /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            //var regexClave = new RegExp("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$");
            var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;
            if (
                strNombre == "" ||
                txtRut == "" ||
                txtCorreo == "" ||
                txtTelefono == ""
            ) {
                swal(
                    "Atención",
                    "Debe ingresar datos para crear al Admin",
                    "error"
                );
                return false;
            } else if (
                !regexCoreo.test(txtCorreo.trim()) ||
                !regulTele.test(txtTelefono.trim())
            ) {
                swal(
                    "Por favor",
                    "Ingrese un Telefono o Correo Valido para registrarse...",
                    "error"
                );
                $("#txtTelefono").val("+569");
                return false;
            } else {
                let formData = new FormData(formAdmin);
                axios
                    .post("/adminsColleges/setAdmin", formData)
                    .then(function (response) {
                        if (response.status) {
                            tableAdmins.api().ajax.reload();
                            $("#modalFormAdmins").modal("hide");
                            formAdmin.reset();
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

        let formColegioA = document.querySelector("#formColegioA");
        formColegioA.onsubmit = function (e) {
            e.preventDefault();
            let listColegios = document.querySelector("#listColegios").value;
            if (listColegios == "") {
                swal(
                    "Atención",
                    "Debe ingresar datos para crear al Admin",
                    "error"
                );
                return false;
            } else {
                let formData = new FormData(formColegioA);
                axios
                    .post("/adminsColleges/setCollege", formData)
                    .then(function (response) {
                        if (response.status) {
                            tableAdmins.api().ajax.reload();
                            $("#modalFormColegiosAd").modal("hide");
                            formColegioA.reset();
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

        fntColegios();
    },
    false
);

function fntViewInfo(idAdmin) {
    axios
        .get(`/adminsColleges/getAdmin/${idAdmin}`)
        .then(function (response) {
            if (response.data.status) {
                let estado =
                    response.data.data.status == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-dark">Vinculado</span>';
                document.querySelector("#celRut").innerHTML =
                    response.data.data.dni;
                document.querySelector("#celNombre").innerHTML =
                    response.data.data.nombre;
                document.querySelector("#celEmail").innerHTML =
                    response.data.data.email;
                document.querySelector("#celTelefono").innerHTML =
                    response.data.data.telefono;
                document.querySelector("#celDireccion").innerHTML =
                    response.data.data.direccion != ""
                        ? response.data.data.direccion
                        : "No se tiene una direccion registrada";
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

function fntEditInfo(element, idAdmin) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").innerHTML =
        "Actualizar Adminitrador de Colegio";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "Actualizar";
    axios
        .get(`/adminsColleges/getAdmin/${idAdmin}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idAdmin").value =
                    response.data.data.id;
                document.querySelector("#txtDni").value =
                    response.data.data.dni;
                document.querySelector("#txtNombre").value =
                    response.data.data.nombre.toString().toLowerCase();
                document.querySelector("#txtEmail").value =
                    response.data.data.email.toString().toLowerCase();
                document.querySelector("#txtTelefono").value =
                    response.data.data.telefono;
                document.querySelector("#txtDireccion").value =
                    response.data.data.direccion.toString().toLowerCase();

                $("#modalFormAdmins").modal("show");
            } else {
                swal("Error", response.data.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntDelInfo(idadmin) {
    swal({
        title: "Inhabilitar Administrador",
        text: "¿Realmente quiere inhabilitar a este administrador?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios
                .post(`/adminsColleges/status/${idadmin}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitada!", response.data.msg, "success");
                        tableAdmins.api().ajax.reload();
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

function fntActivateInfo(idadmin) {
    swal({
        title: "Habilitar Administrador",
        text: "¿Realmente quiere habilitar a este administrador?",
        icon: "info",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios
                .post(`/adminsColleges/status/${idadmin}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitada!", response.data.msg, "success");
                        tableAdmins.api().ajax.reload();
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
    document.querySelector("#idAdmin").value = "";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML =
        "Nuevo Adminitrador de Colegio";
    document.querySelector("#formAdmin").reset();
    $("#modalFormAdmins").modal("show");
}

/*Funciones para el añadir/actualizar colegio por parte del Admin*/

function fntSchoolA(idAdmin) {
    document.querySelector("#titleModalA").innerHTML =
        "Agregar Colegio al Admin";
    document
        .querySelector("#btnActionFormA")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnTextA").innerHTML = "Guardar";
    axios
        .get(`/adminsColleges/getAdmin/${idAdmin}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idAdminC").value =
                    response.data.data.id;
                document.querySelector("#idVinCol").value = "";
                document.querySelector("#txtDniA").value =
                    response.data.data.dni;
                document.querySelector("#txtNombreA").value =
                    response.data.data.nombre;
                document.querySelector("#listColegios").value = "0";
                $("#modalFormColegiosAd").modal("show");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}
function fntSchoolU(idAdmin) {
    document.querySelector("#titleModalA").innerHTML =
        "Actualizar Colegio al Admin";
    document
        .querySelector("#btnActionFormA")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnTextA").innerHTML = "Actualizar";
    axios
        .get(`/adminsColleges/getAdmin/${idAdmin}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idAdminC").value =
                    response.data.data.id;
                document.querySelector("#idVinCol").value =
                    response.data.data.idColegioUser;
                document.querySelector("#txtDniA").value =
                    response.data.data.dni;
                document.querySelector("#txtNombreA").value =
                    response.data.data.nombre;
                document.querySelector("#listColegios").value =
                    response.data.data.colegio_id;
                $("#modalFormColegiosAd").modal("show");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntDelSchool(idadmin) {
    swal({
        title: "Remover Colegio",
        text: "¿Realmente quiere quitar este colegio?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            axios
                .post(`/adminsColleges/delCollege/${idadmin}`)
                .then((response) => {
                    if (response.data.status) {
                        swal("Exito !!", response.data.msg, "success");
                        tableAdmins.api().ajax.reload();
                    } else {
                        swal("Atención !!", response.data.msg, "error");
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    });
}

function fntColegios() {
    if (document.querySelector("#listColegios")) {
        axios
            .post("/colleges/select")
            .then((response) => {
                $(".selectColegios select").html(response.data).fadeIn();
            })
            .catch((error) => {
                console.log(error);
            });
    }
}

function generarReporte() {
    axios
        .post("/adminsColleges/report")
        .then(function (response) {
            var fecha = new Date();
            let adminsColegio = response.data.data;
            //console.log(notificaciones);
            //console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            var columns = ["DNI", "NOMBRE", "TELEFONO", "DIRECCION", "ESTADO"];
            var data = [];

            for (let i = 0; i < adminsColegio.length; i++) {
                if (adminsColegio[i].status == 1) {
                    estado = "ACTIVO";
                } else {
                    estado = "VINCULADO";
                }

                data[i] = [
                    adminsColegio[i].dni,
                    adminsColegio[i].name,
                    adminsColegio[i].phone,
                    adminsColegio[i].address != ""
                        ? adminsColegio[i].address
                        : "No tiene una dirección registrada",
                    estado,
                ];
            }

            pdf.text(
                20,
                20,
                "Reportes de los Administradores de Colegios Registrados"
            );

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
            pdf.save("ReporteAdminsColegio.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
