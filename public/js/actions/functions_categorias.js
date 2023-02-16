let tableCategorias;
let rowTable = "";
document.addEventListener(
    "DOMContentLoaded",
    function () {
        tableCategorias = $("#tableCategorias").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nro" },
                { data: "name" },
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
                url: "/categories",
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

        //NUEVA CATEGORIA
        let formCategoria = document.querySelector("#formCategoria");
        formCategoria.onsubmit = function (e) {
            e.preventDefault();
            let strNombre = document.querySelector("#name").value;
            if (strNombre == "") {
                swal("Atención", "Debe ingresar un nombre..", "error");
                return false;
            } else {
                let formData = new FormData(formCategoria);
                axios
                    .post("/categories/setCategoria", formData)
                    .then(function (response) {
                        if (response.status) {
                            $("#modalFormCategorias").modal("hide");
                            formCategoria.reset();
                            swal("Exito !!", response.data.msg, "success");

                            tableCategorias.api().ajax.reload();
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

function fntViewInfo(nro, idcategoria) {
    axios
        .get(`/categories/getCategory/${idcategoria}`)
        .then(function (response) {
            if (response.data.status) {
                let estado =
                    response.data.data.status == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML =
                    response.data.data.nombre;
                document.querySelector("#celEstado").innerHTML = estado;
                document.querySelector("#celFecha").innerHTML =
                    response.data.data.fecha;
                document.querySelector("#celHora").innerHTML =
                    response.data.data.hora;
                $("#modalViewCategoria").modal("show");
            } else {
                swal("Error", response.data.msg, "error");
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}

function fntEditInfo(element, idcategoria) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector("#titleModal").innerHTML = "Actualizar Categoría";
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-primary", "btn-info");
    document.querySelector("#btnText").innerHTML = "Actualizar";
    axios
        .get(`/categories/getCategory/${idcategoria}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector("#id").value = response.data.data.id;
                document.querySelector("#name").value = String(
                    response.data.data.nombre
                ).toLowerCase();

                $("#modalFormCategorias").modal("show");
            } else {
                swal("Error", response.msg, "error");
            }
        })
        .catch(function (error) {
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
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 0;
            axios
                .post(`/categories/status/${idcategoria}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Inhabilitada!", response.data.msg, "success");
                        tableCategorias.api().ajax.reload();
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

function fntActivateInfo(idcategoria) {
    swal({
        title: "Habilitar Categoría",
        text: "¿Realmente quiere habilitar esta categoría?",
        icon: "info",
        dangerMode: true,
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            let status = 1;
            axios
                .post(`/categories/status/${idcategoria}`, { status: status })
                .then((response) => {
                    if (response.data.status) {
                        swal("Activada !", response.data.msg, "success");
                        tableCategorias.api().ajax.reload();
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
    document.querySelector("#id").value = "";
    document
        .querySelector(".modal-header")
        .classList.replace("headerUpdate", "headerRegister");
    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nueva Categoría";
    document.querySelector("#formCategoria").reset();
    $("#modalFormCategorias").modal("show");
}

function generarReporte() {
    $.post(
        //base_url + "/Categorias/getCategoriasReport",
        function (response) {
            var fecha = new Date();
            let categorias = JSON.parse(response);
            //console.log(notificaciones);
            //console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            var columns = ["NRO", "NOMBRE", "FECHA", "HORA", "ESTADO"];
            var data = [];

            for (let i = 0; i < categorias.length; i++) {
                if (categorias[i].status == 1) {
                    estado = "ACTIVO";
                } else {
                    estado = "INACTIVO";
                }
                data[i] = [
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
            pdf.save("ReporteCategorias.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        }
    );
}
