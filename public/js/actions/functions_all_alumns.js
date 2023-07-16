let tableAlumns;
let rowTable = "";
document.addEventListener("DOMContentLoaded", function () {
    tableAlumns = $("#tableAlumnsAll").dataTable({
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
                data: "alumno"
            },
            {
                data: "colegio"
            },
            {
                data: "curso"
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
            url: "/students-all",
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
        autoWidth: false,
        responsive: true,
        bDestroy: true,
        iDisplayLength: 10,
        order: [
            [0, "asc"]
        ]
    });

    let formAlumns = document.querySelector("#formAlumnsPass");
    formAlumns.onsubmit = function (e) {
        e.preventDefault();

        let txtPassword01 = document.querySelector("#txtPassword01").value;
        let txtPassword02 = document.querySelector("#txtPassword02").value;

        if (txtPassword01 !== txtPassword02) {
            swal("Error", "Las contraseñas no coinciden", "error");
            return false;
        }
        let formData = new FormData(formAlumns);

        axios.post("/students-all/setPassword", formData).then(function (response) {
            if (response.data.status) {
                tableAlumns.api().ajax.reload();
                $("#modalFormAlumnoPass").modal("hide");
                formAlumns.reset();
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


function fntChangePassword(idAlumn) {
    axios.get(`/students-all/select/${idAlumn}`).then(function (response) {
        if (response.data.status) {
            document.querySelector("#idAlumn").value = response.data.data.id;
            document.querySelector("#rutAlum").innerHTML = response.data.data.dni;
            document.querySelector("#nombreAlum").innerHTML = response.data.data.nombre;
            document.querySelector("#emailAlum").innerHTML = response.data.data.correo;
        } else {
            swal("Error", response.data.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
    $("#modalFormAlumnoPass").modal("show");
}


function fntDisableAccount(idalumn) {
    swal({
        title: "Inhabilitar Cuenta del Alumno",
        text: "¿Realmente quiere inhabilitar a este Alumno?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios.post(`/students-all/status/${idalumn}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Inhabilitado!", response.data.msg, "success");
                    tableAlumns.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function fntEnableAccount(idalumn) {
    swal({
        title: "Habilitar Cuenta del Alumno",
        text: "¿Realmente quiere habilitar a este alumno?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios.post(`/students-all/status/${idalumn}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Habilitado !!", response.data.msg, "success");
                    tableAlumns.api().ajax.reload();
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
    axios.post("/students-all/report").then(function (response) {
        var fecha = new Date();
        var Alumnos = response.data.data;
        let estado = "";
        var pdf = new jsPDF();
        pdf.text(20, 20, "Reportes de los Alumnos Registrados");
        var data = [];
        var columns = [
            "RUT",
            "ALUMNO",
            "DIRECCION",
            "COLEGIO",
            "CURSO",
            "ESTADO",
        ];
        for (let i = 0; i < Alumnos.length; i++) {
            if (Alumnos[i].status == 1) {
                estado = "ACTIVO";
            } else {
                estado = "INACTIVO";
            }

            let nombres = '';
            let telefono = '';
            let direccion = '';

            if (Alumnos[i].alumno.hasOwnProperty('Nombres')) {
                nombres = Alumnos[i].alumno.Nombres;
            }

            if (Alumnos[i].alumno.hasOwnProperty('Email')) {
                email = Alumnos[i].alumno.Email;
            }

            if (Alumnos[i].alumno.hasOwnProperty('Teléfono')) {
                telefono = Alumnos[i].alumno['Teléfono'];
            }

            if (Alumnos[i].hasOwnProperty('direccion')) {
                direccion = Alumnos[i].direccion;
            }

            data[i] = [
                Alumnos[i].dni,
                nombres + ' (' + email + ')' + "\n" +" Fono: (" + telefono + ")",
                direccion != "" ? direccion : "No se tiene una dirección registrada",
                Alumnos[i].colegio,
                Alumnos[i].curso,
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
        pdf.save("ReporteAlumnos.pdf");
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
