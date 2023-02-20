let tableAlumns;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableAlumns = $('#tableAlumns').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Alumnos/getAlumnos",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombres%"},
            {"data": "fono"},
            {"data": "curso"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });

    //NUEVO ALUMNO
    let formAlumns = document.querySelector("#formAlumns");
    formAlumns.onsubmit = function (e) {
        e.preventDefault();
        let txtRutAlu = document.querySelector("#txtRutAlu").value;
        let listEspecialidad = document.querySelector("#listEspecialidad").value;
        let listCurso = document.querySelector("#listCurso").value;
        let listProfesors = document.querySelector("#listProfesors").value;
        let listGuia = document.querySelector("#listGuia").value;
        let txtCorreoAlu = $("#txtCorreoAlu").val();
        let txtNombreAlu = $("#txtNombreAlu").val();
        let txtApellidoAlu = $("#txtApellidoAlu").val();
        let txtTelefono01 = $("#txtTelefono01").val();

        let regexCorreo = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;

        if (txtRutAlu == "" || listEspecialidad == "" || listCurso == ""
                || txtCorreoAlu == "" || listProfesors == "" || listGuia == "" || txtNombreAlu == "" || txtApellidoAlu == "" || txtTelefono01 == "") {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else if (!regulTele.test(txtTelefono01) || !regexCorreo.test(txtCorreoAlu)) {
            swal("Error !!", "El correo u telefono ingresados no son validos", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Alumnos/setAlumno';
            let formData = new FormData(formAlumns);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableAlumns.api().ajax.reload();
                        $('#modalFormAlumno').modal("hide");
                        formAlumns.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };
    fntSelectsAlumns();
}, false);

function fntSelectsAlumns() {
    if (document.querySelector('#listProfesors')) {
        let ajaxUrl = base_url + '/Guias/getSelectGuias';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                $('.selectGuias select').html(request.responseText).fadeIn();
            }
        };
    }

    if (document.querySelector('#listGuia')) {
        let ajaxUrl = base_url + '/Tutores/getSelectTutors';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                $('.selectProfesores select').html(request.responseText).fadeIn();
            }
        };
    }
    
    if (document.querySelector("#listEspecialidad")) {
         let ajaxUrl = base_url + '/Especialidades/getSelectEspecialidades';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                $('.selectEspecialidades select').html(request.responseText).fadeIn();
            }
        };
    }
    if (document.querySelector("#listCurso")) {
         let ajaxUrl = base_url + '/Cursos/getSelectCursos';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                $('.selectCursos select').html(request.responseText).fadeIn();
            }
        };
    }
}

function fntViewInfo(idalu) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumno/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objGuia = objData.data.detailGuia;
                let objTutor = objData.data.detailTutor;

                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-dark">Con Plan</span>';
                $("#celPlan").hide();
                document.querySelector("#celRutA").innerHTML = objData.data.rut;
                document.querySelector("#celNombreA").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#celEspecialidadA").innerHTML = objData.data.especialidad;
                document.querySelector("#celCursoA").innerHTML = objData.data.curso;
                document.querySelector("#celTelefonosA").innerHTML = objData.data.fono02 != "+569" ? objData.data.fono + " - " + objData.data.fono02 : objData.data.fono;
                document.querySelector("#labalTelefonoA").innerHTML = objData.data.fono02 != "+569" ? "Telefonos" : "Telefono";
                document.querySelector("#celCursoA").innerHTML = objData.data.curso;
                document.querySelector("#celTutorA").innerHTML = objTutor.nombre + " " + objTutor.apellido;
                document.querySelector("#celGuiaA").innerHTML = objGuia.nombre + " " + objGuia.apellido;
                document.querySelector("#celEstadoA").innerHTML = estado;
                $('#modalViewAlumno').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idalu) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').textContent = "Actualizar Alumno";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').textContent = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumno/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idAlumn").value = objData.data.id;
                document.querySelector("#txtRutAlu").value = objData.data.rut;
                document.querySelector("#txtCorreoAlu").value = objData.data.correo.toString().toLowerCase();
                document.querySelector("#txtNombreAlu").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtApellidoAlu").value = objData.data.apellido.toString().toLowerCase();
                document.querySelector("#listEspecialidad").value = objData.data.idesp;
                document.querySelector("#listCurso").value = objData.data.idcurso;
                document.querySelector("#txtTelefono01").value = objData.data.fono;
                document.querySelector("#txtTelefono02").value = objData.data.fono02;
                document.querySelector("#listProfesors").value = objData.data.profesor_id;
                document.querySelector("#listGuia").value = objData.data.guia_id;
                validadorRut('txtRutAlu');
                $('#modalFormAlumno').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idalumn) {
    swal({
        title: "Inhabilitar Alumno",
        text: "¿Realmente quiere inhabilitar este rol?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Alumnos/setStatusAlumn';
            let strData = "idAlum=" + idalumn + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableAlumns.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idalumn) {
    swal({
        title: "Habilitar Alumno",
        text: "¿Realmente quiere habilitar a este alumno?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Alumnos/setStatusAlumn';
            let strData = "idAlum=" + idalumn + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tableAlumns.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idAlumn').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').textContent = "  Guardar";
    document.querySelector('#titleModal').textContent = "Nuevo Alumno";
    document.querySelector("#formAlumns").reset();
    validadorRut('txtRutAlu');
    $('#modalFormAlumno').modal('show');
}


function generarReportAlumns() {
    $.post(base_url + "/Alumnos/getAlumnosReport",
            function (response) {
                var fecha = new Date();
                var Alumnos = JSON.parse(response);
                //console.log(tecnicos);
                let estado = "";
                var pdf = new jsPDF();
                pdf.text(20, 20, "Reportes de los Alumnos Registrados");
                var data = [];
                let telefons = "";
                var columns = ["RUT", "NOMBRES", "TELEFONO", "CURSO", "ESTADO"];
                for (let i = 0; i < Alumnos.length; i++) {
                    if (Alumnos[i].status == 1) {
                        estado = 'ACTIVO';
                    } else if (Alumnos[i].status == 2) {
                        estado = 'CON PLAN';
                    } else {
                        estado = 'INACTIVO';
                    }
                    telefons = Alumnos[i].fono02 != "+569" ? Alumnos[i].fono + " - " + Alumnos[i].fono02 : Alumnos[i].fono;
                    data[i] = [Alumnos[i].rut, Alumnos[i].nombre, telefons, Alumnos[i].curso, estado];
                }
                pdf.autoTable(columns, data,
                        {margin: {top: 40}}
                );
                pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                pdf.save('ReporteAlumnos.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
            }
    );
}
