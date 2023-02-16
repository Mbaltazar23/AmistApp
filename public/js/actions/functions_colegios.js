let tableColegio;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    validadorRut('txtRut');
    tableColegio = $('#tableColegios').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Colegios/getColegios",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombre"},
            {"data": "telefono"},
            {"data": "status"},
            {"data": "options"}
        ],
        responsive: true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });



    let formColegio = document.querySelector("#formColegio");
    formColegio.onsubmit = function (e) {
        e.preventDefault();
        let txtRut = document.querySelector('#txtRut').value;
        let strNombre = document.querySelector('#txtNombre').value;
        let txtTelefono = $('#txtTelefono').val();
        //var regexClave = new RegExp("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$");
        var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;
        if (strNombre == '' || txtRut == '' || txtTelefono == '')
        {
            swal("Atención", "Debe ingresar datos para crear al Admin", "error");
            return false;
        } else if (!regulTele.test(txtTelefono.trim())) {
            swal("Por favor", "Ingrese un Telefono Valido para registrarse...", "error");
            $("#txtTelefono").val("+569");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Colegios/setColegio';
            let formData = new FormData(formColegio);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableColegio.api().ajax.reload();
                        $('#modalFormColegios').modal("hide");
                        formColegio.reset();
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

function fntViewInfo(idColegio) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Colegios/getColegio/' + idColegio;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">En espera</span>' :
                        '<span class="badge badge-dark">Vinculado</span>';

                let direccion = objData.data.direccion != "" ? objData.data.direccion : "No se tiene una direccion registrada";
                document.querySelector("#celRut").innerHTML = objData.data.rut;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                document.querySelector("#celDireccion").innerHTML = direccion;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celStatus").innerHTML = estado;

                $('#modalViewAdmin').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idColegio) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Colegio";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Colegios/getColegio/' + idColegio;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idColegio").value = objData.data.id;
                document.querySelector("#txtRut").value = objData.data.rut;
                document.querySelector("#txtNombre").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtTelefono").value = objData.data.telefono;
                document.querySelector("#txtDireccion").value = objData.data.direccion.toString().toLowerCase();

                $('#modalFormColegios').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}


function fntDelInfo(idcolegio) {
    swal({
        title: "Inhabilitar Colegio",
        text: "¿Realmente quiere inhabilitar a este colegio?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Colegios/setStatusColegio';
            let strData = "idColegio=" + idcolegio + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado !!", objData.msg, "success");
                        tableColegio.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}

function fntActivateInfo(idcolegio) {
    swal({
        title: "Habilitar Colegio",
        text: "¿Realmente quiere habilitar a este colegio?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Colegios/setStatusColegio';
            let strData = "idColegio=" + idcolegio + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Activada !!", objData.msg, "success");
                        tableColegio.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}


function openModal() {
    document.querySelector('#idColegio').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Colegio";
    document.querySelector("#formColegio").reset();
    $('#modalFormColegios').modal('show');
}


function generarReporte() {
    $.post(base_url + "/Colegios/getColegiosReport",
            function (response) {
                var fecha = new Date();
                let colegios = JSON.parse(response);
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
                    data[i] = [colegios[i].rut, colegios[i].nombre, colegios[i].telefono, colegios[i].hora, estado];

                }


                pdf.text(20, 20, "Reportes de las colegios Registradas");

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
                pdf.save('ReporteColegios.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');

            }
    );
}