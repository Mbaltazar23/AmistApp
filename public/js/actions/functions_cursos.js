let tableCursos;
let rowTable = "";
document.addEventListener(
    "DOMContentLoaded",
    function () {
        tableCursos = $("#tableCursos").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },

            columns: [
                { data: "nro" },
                { data: "nombre" },
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
                url: "/courses",
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

        //NUEVO CURSO
        let formCursos = document.querySelector("#formCursos");
        formCursos.onsubmit = function (e) {
            e.preventDefault();
            let nombreCurso = $("#txtNombre").val();
            let strSection = $("#selectSection").val();
            if (nombreCurso == "" || strSection == "") {
                swal(
                    "Atención",
                    "Debe ingresar el nombre y sección al curso.",
                    "error"
                );
                return false;
            } else {
                let formData = new FormData(formCursos);
                axios
                    .post("/courses/setCourse", formData)
                    .then(function (response) {
                        if (response.data.status) {
                            tableCursos.api().ajax.reload();
                            $("#modalFormCursos").modal("hide");
                            formCursos.reset();
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

function fntViewInfo(nro, idCurso) {
    axios
        .get(`/courses/getCourse/${idCurso}`)
        .then(function (response) {
            if (response.data.status) {
                let estado =
                    response.data.data.status == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML =
                    response.data.data.nombreCur;
                document.querySelector("#celFecha").innerHTML =
                    response.data.data.fecha;
                document.querySelector("#celHora").innerHTML =
                    response.data.data.hora;
                document.querySelector("#celEstado").innerHTML = estado;
                $("#modalViewCurso").modal("show");
            } else {
                swal("Error", objData.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntEditInfo(element, idCurso) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").innerHTML = "Actualizar Curso";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "  Actualizar";
    axios
        .get(`/courses/getCourse/${idCurso}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#idCurso").value =
                    response.data.data.id;
                document.querySelector("#txtNombre").value =
                    response.data.data.nombre.toString().toLowerCase();
                document.querySelector("#selectSection").value =
                    response.data.data.seccion;

                $("#modalFormCursos").modal("show");
            } else {
                swal("Error", response.data.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntDelInfo(idCurso) {
    swal({
        title: "Inhabilitar Curso",
        text: "¿Realmente quiere inhabilitar este curso",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios
                .post(`/courses/status/${idCurso}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitado!", response.data.msg, "success");
                        tableCursos.api().ajax.reload();
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

function fntActivateInfo(idCurso) {
    swal({
        title: "Habilitar Curso",
        text: "¿Realmente quiere habilitar este curso?",
        icon: "info",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios
                .post(`/courses/status/${idCurso}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Habilitada !!", response.data.msg, "success");
                        tableCursos.api().ajax.reload();
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
    document.querySelector("#idCurso").value = "";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "  Guardar";
    document.querySelector("#titleModal").innerHTML = "Nuevo Curso";
    document.querySelector("#formCursos").reset();
    $("#modalFormCursos").modal("show");
}

function generarReporte() {
    axios
        .post("/courses/report")
        .then(function (response) {
            var fecha = new Date();
            var cursos = response.data.data;
            //console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            pdf.text(20, 20, "Reportes de los Cursos Registrados");
            var data = [];
            var columns = [
                "NRO",
                "NOMBRE",
                "FECHA/HORA",
                "ESTUDIANTES",
                "PROFESORES",
                "ESTADO",
            ];
            for (let i = 0; i < cursos.length; i++) {
                if (cursos[i].status == 1) {
                    estado = "ACTIVO";
                } else {
                    estado = "INACTIVO";
                }
                data[i] = [
                    cursos[i].nro,
                    cursos[i].nombre,
                    cursos[i].fecha + " " + cursos[i].hora,
                    cursos[i].students > 0
                        ? cursos[i].students
                        : "No se tiene alumnos registrados",
                    cursos[i].teachers > 0
                        ? cursos[i].teachers
                        : "No se tiene profesores registrados",
                    estado,
                ];
            }
            pdf.autoTable(columns, data, { margin: { top: 40 } });
            pdf.autoTable(columns, data, {
                startY: 40,
                styles: {
                    cellPadding: 8,
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
            pdf.save("ReporteCursos.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
