let tableTutors, tableAlumns;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableTutors = $('#tableTutores').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Tutores/getTutores",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombres%"},
            {"data": "correo"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });

    // NUEVO TUTOR
    let formTutor = document.querySelector("#formTutor");
    formTutor.onsubmit = function (e) {
        e.preventDefault();
        let nombreRol = $("#txtNombreT").val();
        let apellido = $("#txtApellidoT").val();
        let correo = $("#txtCorreoT").val();
        let telefono = $("#txtTelefonoT").val();
        let regexCorreo = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;


        if (nombreRol == "" || apellido == "" || correo == "" || telefono == "") {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else if (!regexCorreo.test(correo)) {
            swal("Error !!", "El correo ingresado no es valido..", "error");
            return false;
        } else if (!regulTele.test(telefono)) {
            swal("Error !!", "El telefono ingresado no es valido", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tutores/setTutor';
            let formData = new FormData(formTutor);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormTutores').modal("hide");
                        formTutor.reset();
                        tableTutors.api().ajax.reload();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };

    //Nuevo PLAN al Alumno vinculado a El
    if (document.querySelector("#formAlumnP")) {
        let formAlumnP = document.querySelector("#formAlumnP");
        formAlumnP.onsubmit = function (e) {
            e.preventDefault();
            let listPlanes = document.querySelector("#listPlanes").value;
            let txtDescripcionP = $("#txtDescripcionP").val();

            if (listPlanes == "" || txtDescripcionP == "") {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            } else {
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url + '/Planes/setDetailPlanAlum';
                let formData = new FormData(formAlumnP);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {

                        let objData = JSON.parse(request.responseText);
                        if (objData.status)
                        {
                            tableAlumns.api().ajax.reload();
                            $('#modalFormAlumnoP').modal("hide");
                            formAlumnP.reset();
                            swal("Exito !!", objData.msg, "success");
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }
                    return false;
                };
            }
        };
    }
    ;
    fntPlanes();
}, false);

function fntPlanes() {
    if (document.querySelector('#listPlanes')) {
        let ajaxUrl = base_url + '/Planes/getSelectPlanes';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                $('.selectPlanes select').html(request.responseText).fadeIn();
            }
        };
    }
}

function fntViewInfo(idTutor) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tutores/getTutor/' + idTutor;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celRutT").innerHTML = objData.data.rut;
                document.querySelector("#celNombresT").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#celCorreoT").innerHTML = objData.data.correo;
                document.querySelector("#celTelefonoT").innerHTML = objData.data.fono;
                document.querySelector("#celEstadoT").innerHTML = estado;
                $('#modalViewTutor').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idtutor) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').textContent = "Actualizar Profesor";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').textContent = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tutores/getTutor/' + idtutor;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idTutor").value = objData.data.id;
                document.querySelector("#txtRutT").value = objData.data.rut;
                document.querySelector("#txtNombreT").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtApellidoT").value = objData.data.apellido.toString().toLowerCase();
                document.querySelector("#txtCorreoT").value = objData.data.correo.toString().toLowerCase();
                document.querySelector("#txtTelefonoT").value = objData.data.fono;
                validadorRut('txtRutT');
                $('#modalFormTutores').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntViewAlumns(idTutor) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tutores/getTutor/' + idTutor;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                //console.log(objData);
                document.querySelector("#titleModalA").innerHTML = "Alumnos del Profesor " + objData.data.nombre;
                cargarTable(objData.data.idtutor, objData.data.rol);
            }
        }
    };
}

function cargarTable(idTutor, Rol) {
    tableAlumns = $("#tableAlumnsPro").dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Alumnos/getAlumnosRoles",
            "dataSrc": "",
            "data": {id: idTutor,
                Rol: Rol},
            'type': 'POST'

        },
        "columns": [
            {"data": "rut"},
            {"data": "nombres%"},
            {"data": "fono"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "bDestroy": true,
        "order": [[0, "desc"]]
    });
    $("#modalListAlumnosP").modal('show');
}

function fntPlanAlum(idalu) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumno/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#titleModalP").textContent = "Añadir plan al Alumno";
                document.querySelector("#btnTextP").textContent = " Guardar";
                document.querySelector("#idAlumnP").value = objData.data.idalum;
                document.querySelector("#txtRutAluP").value = objData.data.rut;
                document.querySelector("#txtNombreAluP").value = objData.data.nombre.toString().toLocaleLowerCase() + " " + objData.data.apellido.toString().toLocaleLowerCase();
                document.querySelector("#txtCursoAluP").value = objData.data.curso.toString().toLocaleLowerCase();
                document.querySelector("#listPlanes").value = "0";
                document.querySelector("#txtDescripcionP").value = "";
                document.querySelector("#idPlanA").value = "";
                $("#modalFormAlumnoP").modal('show');
            }
        }
    };
}

function fntPlanAlumU(idalu) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumnoP/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#titleModalP").textContent = "Actualizar plan al Alumno";
                document.querySelector("#btnTextP").textContent = " Actualizar";
                document.querySelector("#idAlumnP").value = objData.data.idalum;
                document.querySelector("#idPlanA").value = objData.data.iddetalleplan;
                document.querySelector("#listPlanes").value = objData.data.plan_id;
                document.querySelector("#txtDescripcionP").value = objData.data.detalleplan.toString().toLocaleLowerCase();
                document.querySelector("#txtRutAluP").value = objData.data.rut;
                document.querySelector("#txtNombreAluP").value = objData.data.nombre.toString().toLocaleLowerCase() + " " + objData.data.apellido.toString().toLocaleLowerCase();
                document.querySelector("#txtCursoAluP").value = objData.data.curso.toString().toLocaleLowerCase();
                $("#modalFormAlumnoP").modal('show');
            }
        }
    };
}

function fntPlanAlumT(idalum) {
    $("#tableDetalleP tbody").empty();
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumnoP/' + idalum;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objPlan = objData.data;
                //console.log(objPlan);

                document.querySelector("#idPlanT").value = objPlan.plan_id;

                document.querySelector("#planAlum").textContent = objPlan.nombreP;

                $('#tableDetalleP tbody').append(`<tr>
                                            <td>${objPlan.nombre + " " + objPlan.apellido}</td>
                                            <td>${objPlan.especialidad}</td>
                                            <td>${objPlan.curso}</td>
                                            <td>${objPlan.detailGuia.nombre + " " + objPlan.detailGuia.nombre}</td>
                                            <td>${objPlan.detailTutor.nombre + " " + objPlan.detailGuia.nombre}</td>
                                        </tr>`);
                cargarTareasPlan(objPlan.plan_id);
                $("#modalAlumPlanT").modal('show');
            }
        }
    };
}

function cargarTareasPlan(idPlan) {
    tableTareasAl = $('#tableTareasP').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "lengthChange": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Tareas/getTareasPlanActive/" + idPlan,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"},
        ],
        "resonsieve": "true",
        "searching": false,
        "bDestroy": true,
        "iDisplayLength": 3,
        "order": [[0, "asc"]]
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
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tutores/setStatusTutor';
            let strData = "idTutor=" + idTutor + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableTutors.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
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
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tutores/setStatusTutor';
            let strData = "idTutor=" + idTutor + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Habilitado!", objData.msg, "success");
                        tableTutors.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idTutor').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').textContent = "  Guardar";
    document.querySelector('#titleModal').textContent = "Nuevo Profesor";
    document.querySelector("#formTutor").reset();
    validadorRut('txtRutT');
    $('#modalFormTutores').modal('show');
}


function generarReportTutores() {
    $.post(base_url + "/Tutores/getTutorsReport", function (response) {
        var fecha = new Date();
        var tutores = JSON.parse(response);
        // console.log(tecnicos);
        let estado = "";
        var pdf = new jsPDF();
        pdf.text(20, 20, "Reportes de los Tutores Registrados");
        var data = [];
        var columns = ["RUT", "NOMBRE", "CORREO", "ESTADO"];
        for (let i = 0; i < tutores.length; i++) {
            if (tutores[i].status == 1) {
                estado = 'ACTIVO';
            } else {
                estado = 'INACTIVO';
            }
            data[i] = [
                tutores[i].rut,
                tutores[i].nombres + " " + tutores[i].apellidos,
                tutores[i].correo,
                estado
            ];
        }
        pdf.autoTable(columns, data, {
            margin: {
                top: 40
            }
        });
        pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (
                fecha.getMonth() + 1
                ) + "/" + fecha.getFullYear());
        pdf.save('ReporteProfesores.pdf');
        swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
    });
}