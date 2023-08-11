let tableTeachers;
let rowTable = "";
document.addEventListener("DOMContentLoaded", function () {
    tableTeachers = $("#tableTeachersAll").dataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        columns: [
            {
                data: "dni"
            },
            {
                data: "profesor"
            },
            {
                data: "colegio"
            },
            {
                data: "cursos"
            }, {
                data: "status"
            }, {
                data: "options",
                render: function (data) {
                    return data;
                }
            },
        ],
        ajax: {
            url: "/teachers-all",
            method: "GET",
            dataSrc: function (json) {
                if (! json.status) {
                    console.error(json.message);
                    return [];
                }
                return json.data;
            }
        },
        paging: true,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        bDestroy: true,
        iDisplayLength: 10,
        order: [
            [0, "asc"]
        ]
    });

    let formTeachers = document.querySelector("#formTeachersPass");
    formTeachers.onsubmit = function (e) {
        e.preventDefault();

        let txtPassword01 = document.querySelector("#txtPassword01").value;
        let txtPassword02 = document.querySelector("#txtPassword02").value;

        if (txtPassword01 !== txtPassword02) {
            swal("Error", "Las contraseñas no coinciden", "error");
            return false;
        }
        let formData = new FormData(formTeachers);

        axios.post("/teachers-all/setPassword", formData).then(function (response) {
            if (response.data.status) {
                tableTeachers.api().ajax.reload();
                $("#modalFormTeacherPass").modal("hide");
                formTeachers.reset();
                swal("Éxito", response.data.msg, "success");
            } else {
                swal("Error", response.data.msg, "error");
            }
        }).catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
    }

    let formTeachersPoints = document.querySelector("#formTeachersPoints");
    formTeachersPoints.onsubmit = function (e) {
        e.preventDefault();

        let txtPuntaje = document.querySelector("#txtPuntaje").value;

        // Validar si el valor no es un número
        if (isNaN(txtPuntaje)) {
            swal("Error", "El puntaje debe ser un número válido.", "error");
            return false;
        }

        // Validar si el valor está vacío
        if (txtPuntaje === "") {
            swal("Error", "El puntaje no puede estar vacío.", "error");
            return false;
        }
        let formData = new FormData(formTeachersPoints);

        axios.post("/teachers-all/setPoints", formData).then(function (response) {
            if (response.data.status) {
                tableTeachers.api().ajax.reload();
                $("#modalFormTeacherPoints").modal("hide");
                formTeachers.reset();
                swal("Éxito", response.data.msg, "success");
            } else {
                swal("Error", response.data.msg, "error");
            }
        }).catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
    }

}, false);


function fntChangePassword(idTeacher) {
    axios.get(`/teachers-all/select/${idTeacher}`).then(function (response) {
        if (response.data.status) {
            document.querySelector("#idTeacher").value = response.data.data.id;
            document.querySelector("#rutTeacher").innerHTML = response.data.data.dni;
            document.querySelector("#nombreTeacher").innerHTML = response.data.data.nombre;
            document.querySelector("#emailTeacher").innerHTML = response.data.data.correo;
        } else {
            swal("Error", response.data.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
    $("#modalFormTeacherPass").modal("show");
}


function fntSetPoints(idTeacher) {
    axios.get(`/teachers-all/select/${idTeacher}`).then(function (response) {
        if (response.data.status) {
            document.querySelector("#idTeacherPoint").value = response.data.data.id;
            document.querySelector("#rutTeacherPoint").innerHTML = response.data.data.dni;
            document.querySelector("#nombreTeacherPoint").innerHTML = response.data.data.nombre;
            document.querySelector("#emailTeacherPoint").innerHTML = response.data.data.correo;
            document.querySelector("#txtPuntaje").value = response.data.data.puntos !== 0 ? response.data.data.puntos : '';
        } else {
            swal("Error", response.data.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
    $("#modalFormTeacherPoints").modal("show");
}


function fntDisableAccount(idTeacher) {
    swal({
        title: "Inhabilitar Cuenta del Profesor",
        text: "¿Realmente quiere inhabilitar a este profesor?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios.post(`/teachers-all/status/${idTeacher}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Inhabilitado!", response.data.msg, "success");
                    tableTeachers.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function fntEnableAccount(idTeacher) {
    swal({
        title: "Habilitar Cuenta del Profesor",
        text: "¿Realmente quiere habilitar a este profesor?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios.post(`/teachers-all/status/${idTeacher}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Habilitado !!", response.data.msg, "success");
                    tableTeachers.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function generarReporte() {
    axios.post("/teachers-all/report").then(function (response) {
        var fecha = new Date();
        var Profesores = response.data.data;
        let estado = "";
        var pdf = new jsPDF();
        pdf.text(20, 20, "Reportes de los Profesores Registrados");
        var data = [];
        var columns = [
            "RUT",
            "PROFESOR",
            "DIRECCION",
            "COLEGIO",
            "CURSOS",
            "ESTADO",
        ];
        for (let i = 0; i < Profesores.length; i++) {
            if (Profesores[i].status == 1) {
                estado = "ACTIVO";
            } else {
                estado = "INACTIVO";
            }

            let nombres = '';
            let telefono = '';
            let direccion = '';

            if (Profesores[i].profesor.hasOwnProperty('Nombres')) {
                nombres = Profesores[i].profesor.Nombres;
            }

            if (Profesores[i].profesor.hasOwnProperty('Email')) {
                email = Profesores[i].profesor.Email;
            }

            if (Profesores[i].profesor.hasOwnProperty('Teléfono')) {
                telefono = Profesores[i].profesor.Teléfono;
            }

            if (Profesores[i].hasOwnProperty('direccion')) {
                direccion = Profesores[i].direccion;
            }

            data[i] = [
                Profesores[i].dni,
                nombres + ' (' + email + ')' + "\n" + " Fono: (" + telefono + ")",
                direccion != "" ? direccion : "No se tiene una dirección registrada",
                Profesores[i].colegio,
                Profesores[i].cursos,
                estado,
            ];
        }

        pdf.autoTable(columns, data, {
            startY: 40,
            styles: {
                cellPadding: 6,
                fontSize: 6,
                font: "helvetica",
                textColor: [
                    0, 0, 0
                ],
                fillColor: [
                    255, 255, 255
                ],
                lineWidth: 0.1,
                halign: "center",
                valign: "middle"
            }
        });

        pdf.text(20, pdf.autoTable.previous.finalY + 20, "Fecha de Creacion : " + fecha.getDate() + "/" + (
            fecha.getMonth() + 1
        ) + "/" + fecha.getFullYear());
        pdf.save("ReporteProfesores.pdf");
        swal("Exito", "Reporte Impreso Exitosamente", "success");
    }).catch(function (error) {
        console.log(error);
    });
}


function decodeHTML(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}
