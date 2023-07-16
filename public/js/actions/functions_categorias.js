let tableCategorias;
let rowTable = "";
document.addEventListener("DOMContentLoaded", function () {
    tableCategorias = $("#tableCategorias").dataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        columns: [
            {
                data: "nro"
            },
            {
                data: "nameC"
            },
            {
                data: "fecha"
            },
            {
                data: "hora"
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
            url: "/categories",
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

    // NUEVA CATEGORIA
    if (document.querySelector("#formCategoria")) {
        let formCategoria = document.querySelector("#formCategoria");
        formCategoria.onsubmit = function (e) {
            e.preventDefault();
            let strNombre = document.querySelector("#name").value;
            if (strNombre == "") {
                swal("Atención", "Debe ingresar un nombre..", "error");
                return false;
            } else {
                let formData = new FormData(formCategoria);
                axios.post("/categories/setCategory", formData).then(function (response) {
                    if (response.data.status) {
                        $("#modalFormCategorias").modal("hide");
                        formCategoria.reset();
                        swal("Exito !!", response.data.msg, "success");

                        tableCategorias.api().ajax.reload();
                    } else {
                        swal("Error", response.data.msg, "error");
                    }
                }).catch(function (error) {
                    console.log(error);
                    swal("Error", "Ocurrió un error al procesar la petición", "error");
                });
            }
        };
    }

    if (document.querySelector("#image")) {
        let foto = document.querySelector("#image");
        foto.onchange = function (e) {
            let uploadFoto = document.querySelector("#image").value;
            let fileimg = document.querySelector("#image").files;
            let nav = window.URL || window.webkitURL;
            let contactAlert = document.querySelector("#form_alert");
            if (uploadFoto != "") {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != "image/jpeg" && type != "image/jpg" && type != "image/png") {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    if (document.querySelector("#img")) {
                        document.querySelector("#img").remove();
                    }
                    document.querySelector(".delPhoto").classList.add("notBlock");
                    foto.value = "";
                    return false;
                } else {
                    contactAlert.innerHTML = "";
                    if (document.querySelector("#img")) {
                        document.querySelector("#img").remove();
                    }
                    document.querySelector(".delPhoto").classList.remove("notBlock");
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    document.querySelector(".prevPhoto div").innerHTML = "<img id='img' src=" + objeto_url + ">";
                }
            } else {
                swal("Error !!", "No selecciono una foto", "error");
                if (document.querySelector("#img")) {
                    document.querySelector("#img").remove();
                }
            }
        };
    }

    if (document.querySelector(".delPhoto")) {
        let delPhoto = document.querySelector(".delPhoto");
        delPhoto.onclick = function (e) {
            e.preventDefault();
            swal({
                title: "Borrar Imagen",
                text: "¿Realmente quiere borrar esta imagen de este producto?",
                icon: "warning",
                dangerMode: true,
                buttons: true
            }).then((isClosed) => {
                if (isClosed) {
                    document.querySelector("#foto_remove").value = 1;
                    removePhoto();
                }
            });
        };
    }
}, false);

function removePhoto() {
    document.querySelector("#foto").value = "";
    document.querySelector(".delPhoto").classList.add("notBlock");
    if (document.querySelector("#img")) {
        document.querySelector("#img").remove();
    }
}

function fntViewInfo(nro, idcategoria) {
    axios.get(`/categories/getCategory/${idcategoria}`).then(function (response) {
        if (response.data.status) {
            let estado = response.data.data.status == 1 ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
            document.querySelector("#celNro").innerHTML = nro;
            document.querySelector("#celNombre").innerHTML = response.data.data.nombre;
            document.querySelector("#celEstado").innerHTML = estado;
            document.querySelector("#celFecha").innerHTML = response.data.data.fecha;
            document.querySelector("#celHora").innerHTML = response.data.data.hora;
            document.querySelector("#celFoto").innerHTML = '<img src="' + response.data.data.url_image + '" width="120" height="100"/>';
            $("#modalViewCategoria").modal("show");
        } else {
            swal("Error", response.data.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
}

function fntEditInfo(element, idcategoria) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").innerHTML = "Actualizar Categoría";
    document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "Actualizar";
    axios.get(`/categories/getCategory/${idcategoria}`).then(function (response) {
        if (response.data.status) {
            document.querySelector("#id").value = response.data.data.id;
            document.querySelector("#name").value = String(response.data.data.nombre).toLowerCase();

            document.querySelector("#foto_actual").value = response.data.data.image;
            document.querySelector("#foto_remove").value = 0;

            const imgContainer = document.querySelector(".prevPhoto div");
            if (imgContainer) {
                imgContainer.innerHTML = `<img id="img" src="${
                    response.data.data.url_image
                }"/>`;
            }

            if (response.data.data.image == "portada_categoria.png") {
                document.querySelector(".delPhoto").classList.add("notBlock");
            } else {
                document.querySelector(".delPhoto").classList.remove("notBlock");
            }
            $("#modalFormCategorias").modal("show");
        } else {
            swal("Error", response.msg, "error");
        }
    }).catch(function (error) {
        console.log(error);
        swal("Error", "Ocurrió un error al procesar la petición", "error");
    });
}

function fntDelInfo(idcategoria) {
    swal({
        title: "Inhabilitar Categoría",
        text: "¿Realmente quiere inhabilitar al categoría?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios.post(`/categories/status/${idcategoria}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Inhabilitada!", response.data.msg, "success");
                    tableCategorias.api().ajax.reload();
                } else {
                    swal("Atención!", response.data.msg, "error");
                }
            }).catch((error) => {
                console.error(error);
            });
        }
    });
}

function fntActivateInfo(idcategoria) {
    swal({
        title: "Habilitar Categoría",
        text: "¿Realmente quiere habilitar esta categoría?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios.post(`/categories/status/${idcategoria}`, {status: status}).then((response) => {
                if (response.data.status) {
                    swal("Activada !", response.data.msg, "success");
                    tableCategorias.api().ajax.reload();
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
    document.querySelector("#id").value = "";
    document.querySelector(".modal-header").classList.replace("headerUpdate", "headerRegister");
    document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nueva Categoría";
    document.querySelector("#formCategoria").reset();
    document.querySelector("#img").src = "images/categories/portada_categoria.png";
    $("#modalFormCategorias").modal("show");
}

function generarReporte() {
    axios.post("/categories/report").then(function (response) {
        var fecha = new Date();
        let categorias = response.data.data;
        // console.log(notificaciones);
        // console.log(tecnicos);
        let estado = "";
        var pdf = new jsPDF();
        var columns = [
            "NRO",
            "NOMBRE",
            "FECHA",
            "HORA",
            "ESTADO"
        ];
        var data = [];

        for (let i = 0; i < categorias.length; i++) {
            if (categorias[i].status == 1) {
                estado = "ACTIVO";
            } else {
                estado = "INACTIVO";
            } data[i] = [
                i + 1,
                categorias[i].nombre,
                categorias[i].fecha,
                categorias[i].hora,
                estado,
            ];
        }

        pdf.text(20, 20, "Reportes de las Categorias Registradas");

        pdf.autoTable(columns, data, {
            startY: 40,
            styles: {
                cellPadding: 9,
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
        pdf.save("ReporteCategorias.pdf");
        swal("Exito", "Reporte Imprimido Exitosamente..", "success");
    }).catch(function (error) {
        console.log(error);
    });
}
