let tableProductos;
$(document).on("focusin", function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

window.addEventListener(
    "load",
    function () {
        tableProductos = $("#tableProducts").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nameP" },
                { data: "points" },
                { data: "stock" },
                { data: "category" },
                { data: "status" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/products",
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

        if (document.querySelector("#formProductos")) {
            let formProductos = document.querySelector("#formProductos");
            formProductos.onsubmit = function (e) {
                e.preventDefault();
                let strNombre = document.querySelector("#txtNombre").value;
                let txtPuntos = document.querySelector("#txtPuntos").value;
                let intStock = document.querySelector("#txtStock").value;
                if (strNombre == "" || txtPuntos == "" || intStock == "") {
                    swal(
                        "Atención",
                        "Todos los campos son obligatorios.",
                        "error"
                    );
                    return false;
                } else {
                    let formData = new FormData(formProductos);
                    axios
                        .post("/products/setProduct", formData)
                        .then(function (response) {
                            if (response.data.status) {
                                $("#modalFormProductos").modal("hide");
                                formProductos.reset();
                                swal("Exito !!", response.data.msg, "success");

                                tableProductos.api().ajax.reload();
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
                    if (
                        type != "image/jpeg" &&
                        type != "image/jpg" &&
                        type != "image/png"
                    ) {
                        contactAlert.innerHTML =
                            '<p class="errorArchivo">El archivo no es válido.</p>';
                        if (document.querySelector("#img")) {
                            document.querySelector("#img").remove();
                        }
                        document
                            .querySelector(".delPhoto")
                            .classList.add("notBlock");
                        foto.value = "";
                        return false;
                    } else {
                        contactAlert.innerHTML = "";
                        if (document.querySelector("#img")) {
                            document.querySelector("#img").remove();
                        }
                        document
                            .querySelector(".delPhoto")
                            .classList.remove("notBlock");
                        let objeto_url = nav.createObjectURL(this.files[0]);
                        document.querySelector(".prevPhoto div").innerHTML =
                            "<img id='img' src=" + objeto_url + ">";
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
                    buttons: true,
                }).then((isClosed) => {
                    if (isClosed) {
                        document.querySelector("#foto_remove").value = 1;
                        removePhoto();
                    }
                });
            };
        }

        fntCategorias();
    },
    false
);

function removePhoto() {
    document.querySelector("#foto").value = "";
    document.querySelector(".delPhoto").classList.add("notBlock");
    if (document.querySelector("#img")) {
        document.querySelector("#img").remove();
    }
}

function fntViewInfo(idProducto) {
    axios
        .get(`/products/getProduct/${idProducto}`)
        .then(function (response) {
            if (response.data.status) {
                let objProducto = response.data.data;
                let estadoProducto =
                    objProducto.status == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNombre").innerHTML =
                    objProducto.nombre;
                document.querySelector("#celPrecio").innerHTML =
                    objProducto.puntos;
                document.querySelector("#celStock").innerHTML =
                    objProducto.stock;
                document.querySelector("#celCategoria").innerHTML =
                    objProducto.categoria;
                document.querySelector("#celFecha").innerHTML =
                    objProducto.fecha;
                document.querySelector("#celHora").innerHTML = objProducto.hora;
                document.querySelector("#celStatus").innerHTML = estadoProducto;

                document.querySelector("#celFoto").innerHTML =
                    '<img src="' +
                    objProducto.url_image +
                    '" width="120" height="100"/>';
                $("#modalViewProducto").modal("show");
            } else {
                swal("Error", objData.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntEditInfo(element, idProducto) {
    rowTable = element.parentNode.parentNode.parentNode;
    axios
        .get(`/products/getProduct/${idProducto}`)
        .then(function (response) {
            if (response.data.status) {
                let objProducto = response.data.data;
                document.querySelector("#idProducto").value = objProducto.id;
                document.querySelector("#txtNombre").value = objProducto.nombre
                    .toString()
                    .toLowerCase();
                document.querySelector("#txtPuntos").value = Math.round(
                    objProducto.puntos
                );
                document.querySelector("#txtStock").value = objProducto.stock;
                document.querySelector("#listCategoria").value =
                    objProducto.category_id;

                document.querySelector("#foto_actual").value =
                    objProducto.image;
                document.querySelector("#foto_remove").value = 0;

                const imgContainer = document.querySelector(".prevPhoto div");
                if (imgContainer) {
                    imgContainer.innerHTML = `<img id="img" src="${objProducto.url_image}"/>`;
                }

                if (objProducto.image == "product.png") {
                    document
                        .querySelector(".delPhoto")
                        .classList.add("notBlock");
                } else {
                    document
                        .querySelector(".delPhoto")
                        .classList.remove("notBlock");
                }

                $("#modalFormProductos").modal("show");
            } else {
                swal("Error", response.data.msg, "error");
            }
        })

        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntDelInfo(idProducto) {
    swal({
        title: "Inhabilitar Producto",
        text: "¿Realmente quiere inhabilitar el producto?",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            {
                let status = 0;
                axios
                    .post(`/products/status/${idProducto}`, { status: status })
                    .then((response) => {
                        if (response.data.status) {
                            swal("Inhabilitada!", response.data.msg, "success");
                            tableProductos.api().ajax.reload();
                        } else {
                            swal("Atención!", response.data.msg, "error");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        }
    });
}

function fntActivateInfo(idProducto) {
    swal({
        title: "Activar Producto",
        text: "¿Realmente quiere dejar activo el producto?",
        icon: "info",
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            {
                let status = 1;
                axios
                    .post(`/products/status/${idProducto}`, { status: status })
                    .then((response) => {
                        if (response.data.status) {
                            swal("Inhabilitada!", response.data.msg, "success");
                            tableProductos.api().ajax.reload();
                        } else {
                            swal("Atención!", response.data.msg, "error");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        }
    });
}

function fntCategorias() {
    if (document.querySelector("#listCategoria")) {
        axios
            .post("/categories/select")
            .then((response) => {
                $(".selectCategoria select").html(response.data).fadeIn();
            })
            .catch((error) => {
                console.log(error);
            });
    }
}

function openModal() {
    rowTable = "";
    document.querySelector("#idProducto").value = "";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nuevo Producto";
    document.querySelector("#formProductos").reset();
    document.querySelector("#img").src = defaultImage;
    $("#modalFormProductos").modal("show");
}

function generarReporte() {
    axios
        .post("/products/report")
        .then(function (response) {
            var fecha = new Date();
            let productos = response.data.data;
            //console.log(notificaciones);
            //console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            var columns = ["NOMBRE", "CATEGORIA", "STOCK", "PUNTOS", "ESTADO"];
            var data = [];

            for (let i = 0; i < productos.length; i++) {
                if (productos[i].status == 1) {
                    estado = "ACTIVO";
                } else {
                    estado = "INACTIVO";
                }
                data[i] = [
                    productos[i].nombre,
                    productos[i].categoria,
                    productos[i].stock,
                    productos[i].puntos,
                    estado,
                ];
            }

            pdf.text(20, 20, "Reportes de los Productos Registrados");

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
            pdf.save("ReporteProductos.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
