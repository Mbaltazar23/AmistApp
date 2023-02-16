let tableAcciones;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableAcciones = $('#tableAcciones').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Acciones/getAcciones",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "fecha"},
            {"data": "hora"},
            {"data": "status"},
            {"data": "options"}
        ],
       "paging": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });

    //NUEVA ACCION
    let formAccion = document.querySelector("#formAccion");
    formAccion.onsubmit = function (e) {
        e.preventDefault();
        let strNombre = document.querySelector('#txtNombre').value;
        if (strNombre == '')
        {
            swal("Atención", "Debe ingresar un nombre..", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Acciones/setAccion';
            let formData = new FormData(formAccion);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableAcciones.api().ajax.reload();
                        $('#modalFormAcciones').modal("hide");
                        formAccion.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };

}, false);

function fntViewInfo(nro, idaccione) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Acciones/getAccion/' + idaccione;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celEstado").innerHTML = estado;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                $('#modalViewAccion').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idaccion) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Accion";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Acciones/getAccion/' + idaccion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idAccion").value = objData.data.id;
                document.querySelector("#txtNombre").value = String(objData.data.nombre).toLowerCase();

                $('#modalFormAcciones').modal('show');

            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idaccion) {
    swal({
        title: "Inhabilitar Accion",
        text: "¿Realmente quiere inhabilitar esta accion?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Acciones/setStatusAccion';
            let strData = "idAccion=" + idaccion + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitada!", objData.msg, "success");
                        tableAcciones.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}

function fntActivateInfo(idaccion) {
    swal({
        title: "Habilitar Accion",
        text: "¿Realmente quiere habilitar esta accion?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Acciones/setStatusAccion';
            let strData = "idAccion=" + idaccion + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Activada!", objData.msg, "success");
                        tableAcciones.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}

function openModal() {
    document.querySelector('#idAccion').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Accion";
    document.querySelector("#formAccion").reset();
    $('#modalFormAcciones').modal('show');
}



function generarReporte() {
    $.post(base_url + "/Acciones/getAccionesReport",
            function (response) {
                var fecha = new Date();
                let acciones = JSON.parse(response);
                //console.log(notificaciones);
                //console.log(tecnicos);
                let estado = "";
                var pdf = new jsPDF();
                var columns = ["NRO", "NOMBRE", "FECHA", "HORA", "ESTADO"];
                var data = [];


                for (let i = 0; i < acciones.length; i++) {
                    if (acciones[i].status == 1) {
                        estado = "ACTIVO";
                    } else {
                        estado = "INACTIVO";
                    }
                    data[i] = [(i + 1), acciones[i].nombre, acciones[i].fecha, acciones[i].hora, estado];

                }


                pdf.text(20, 20, "Reportes de las Acciones Registradas");

                pdf.autoTable(columns, data, {
                    startY: 40,
                    styles: {
                        cellPadding: 10,
                        fontSize: 8,
                        font: 'helvetica',
                        textColor: [0, 0, 0],
                        fillColor: [255, 255, 255],
                        lineWidth: 0.1,
                        halign: 'center',
                        valign: 'middle'
                    }
                });



                pdf.text(20, pdf.autoTable.previous.finalY + 20, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                pdf.save('ReporteAcciones.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');

            }
    );
}