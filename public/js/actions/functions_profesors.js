let tableTutors,
    tableAlumns;
let rowTable = "";
document.addEventListener("DOMContentLoaded", function () {
    tableTutors = $("#tableProfesors").dataTable({
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
                data: "nombre"
            },
            {
                data: "email"
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
            url: "/teachers",
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

    // NUEVO TUTOR
    let formTutor = document.querySelector("#formTutor");
    formTutor.onsubmit = function (e) {
        e.preventDefault();
        let txtRutT = $("#txtRutT").val();
        let nombreRol = $("#txtNombres").val();
        let correo = $("#txtCorreoT").val();
        let listCurso = $("#listCursos").val();
        let telefono = $("#txtTelefono").val();
        let regexCorreo = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;

        if (txtRutT == "" || nombreRol == "" || listCurso == "" || correo == "" || telefono == "") {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else if (! regexCorreo.test(correo)) {
            swal("Error !!", "El correo ingresado no es valido..", "error");
            return false;
        } else if (! regulTele.test(telefono)) {
            swal("Error !!", "El telefono ingresado no es valido", "error");
            return false;
        } else {
            let formData = new FormData(formTutor);
            axios.post("/teachers/setTeacher", formData).then(function (response) {
                if (response.data.status) {
                    $("#modalFormTutores").modal("hide");
                    formTutor.reset();
                    tableTutors.api().ajax.reload();
                    swal("Exito !!", response.data.msg, "success");
                } else {
                    swal("Error", response.data.msg, "error");
                }
            }).catch(function (error) {
                console.log(error);
                swal("Error", "Ocurrió un error al procesar la petición", "error");
            });
        }
    };

    fntSelectsCourses();
}, false);

function fntSelectsCourses() {
    if (document.querySelector("#listCursos")) {
        let select = "profesor";
        axios.post(`/courses/select/${select}`).then((response) => {
            $(".selectCursos select").html(response.data).fadeIn();
            // Agregar la opción de seleccionar varios cursos
            $(".selectCursos select").select2({placeholder: "Seleccione los cursos", allowClear: true});
        }).catch((error) => {
            console.log(error);
        });
    }
}

function fntViewInfo(idTutor) {
    axios.get(`/teachers/getTeacher/${idTutor}`).then(function (response) {
        if (response.data.status) {
            let estado = response.data.data.status == 1 ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';

            document.querySelector("#celRutT").innerHTML = response.data.data.dni;
            document.querySelector("#celNombresT").innerHTML = response.data.data.nombre;
            document.querySelector("#celCorreoT").innerHTML = response.data.data.correo;
            document.querySelector("#celTelefonoT").innerHTML = response.data.data.telefono;
            document.querySelector("#celDireccionT").innerHTML = response.data.data.direccion != "" ? response.data.data.direccion : "No hay una direccion registrada";
            document.querySelector("#celCursoT").innerHTML = response.data.data.curso;
            document.querySelector("#celEstadoT").innerHTML = estado;
            $("#modalViewTutor").modal("show");
        } else {
            swal("Error", objData.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
}

function fntEditInfo(element, idTutor) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").textContent = "Actualizar Profesor";
    document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").textContent = "  Actualizar";
    axios.get(`/teachers/getTeacher/${idTutor}`).then(function (response) {
        if (response.data.status) {
            document.querySelector("#idProfe").value = response.data.data.id;
            document.querySelector("#txtRutT").value = response.data.data.dni;
            document.querySelector("#txtNombres").value = response.data.data.nombre.toString().toLowerCase();

            document.querySelector("#txtCorreoT").value = response.data.data.correo.toString().toLowerCase();
            document.querySelector("#txtTelefono").value = response.data.data.telefono;
            document.querySelector("#txtDireccion").value = response.data.data.direccion;
            document.querySelector("#txtPassword").parentElement.style.display = "block";

            // Select2: Limpiar la selección actual
            $("#listCursos").val(null).trigger('change');

            // Select2: Seleccionar los cursos asociados
            const cursos = response.data.data.cursos;
            const cursosIds = cursos.map(curso => curso.id);  // Obtener los IDs de los cursos
            $("#listCursos").val(cursosIds).trigger('change');  // Preseleccionar cursos por sus IDs

            validadorRut("txtRutT");
            $("#modalFormTutores").modal("show");
        } else {
            swal("Error", response.data.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
}

function fntDelInfo(idTutor) {
    swal({
        title: "Inhabilitar Profesor",
        text: "¿Realmente quiere inhabilitar a este profesor?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios.post(`/teachers/status/${idTutor}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Inhabilitada!", response.data.msg, "success");
                    tableTutors.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function fntActivateInfo(idTutor) {
    swal({
        title: "Habilitar Profesor",
        text: "¿Realmente quiere habilitar este profesor?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios.post(`/teachers/status/${idTutor}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Habilitada !!", response.data.msg, "success");
                    tableTutors.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function fntDelAll(idProfe) {
    swal({
        title: "Eliminar Profesor",
        text: "¿Realmente borrar a este Profesor?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            axios.post(`/teachers/delete/${idProfe}`).then((response) => {
                if (response.data.status) {
                    swal("Exito !!", response.data.msg, "success");
                    tableTutors.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function openModal() {
    document.querySelector("#idProfe").value = "";
    document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").textContent = "  Guardar";
    document.querySelector("#titleModal").textContent = "Nuevo Profesor";
    document.querySelector("#formTutor").reset();
    $("#listCursos").val(null).trigger("change");
    $("#txtPassword").parent().hide(); // Ocultar campo de contraseña
    validadorRut("txtRutT");
    $("#modalFormTutores").modal("show");
}

function generarReportTutores() {
    axios.post("/teachers/report").then(function (response) {
        var fecha = new Date();
        var tutores = response.data.data;
        // console.log(tecnicos);
        let estado = "";
        var pdf = new jsPDF();
        pdf.text(20, 20, "Reportes de los Tutores Registrados");
        var data = [];
        var columns = [
            "RUT",
            "NOMBRE",
            "CORREO",
            "CURSO",
            "DIRECCION",
            "ESTADO",
        ];
        for (let i = 0; i < tutores.length; i++) {
            if (tutores[i].status == 1) {
                estado = "ACTIVO";
            } else {
                estado = "INACTIVO";
            } data[i] = [
                tutores[i].dni,
                tutores[i].nombre,
                tutores[i].correo,
                tutores[i].curso,
                tutores[i].direccion != "" ? tutores[i].direccion : "No se cuenta con una direccion registrada",
                estado,
            ];
        }
        pdf.autoTable(columns, data, {
            startY: 40,
            styles: {
                cellPadding: 8,
                fontSize: 8,
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
        swal("Exito", "Reporte Imprimido Exitosamente..", "success");
    }).catch(function (error) {
        console.log(error);
    });
}
