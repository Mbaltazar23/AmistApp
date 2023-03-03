let tableProductos;
$(document).on("focusin", function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

window.addEventListener(
    "load",
    function () {
        tableProductos = $("#tableCatTeacher").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nameP" },
                { data: "categoria" },
                { data: "puntos" },
                { data: "total_canjeados" },
                { data: "total_puntos" },
                { data: "status" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/purchases/products-teacher",
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
    },
    false
);

function fntViewInfo(idProducto) {
    axios
        .get(`/purchases/getPurchaseTe/${idProducto}`)
        .then(function (response) {
            if (response.data.status) {
                document.querySelector(".purchase-row01").classList.add("show");
                document.querySelector(".purchase-row02").classList.add("show");

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
                document.querySelector("#celStockV").innerHTML =
                    objProducto.stock_ven;
                document.querySelector("#celPuntos").innerHTML =
                    objProducto.points_initial;

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

function generarReporte() {
    axios
        .post("/purchases/reportForTeacher")
        .then(function (response) {
            var fecha = new Date();
            let purchasesProducts = response.data.data;
            //console.log(notificaciones);
            //console.log(tecnicos);
            var pdf = new jsPDF();
            var columns = [
                "NRO",
                "NOMBRE",
                "TIPO",
                "PUNTOS",
                "STOCK-TOTAL",
                "PUNTOS-TOTAL",
                "ESTUDIANTES",
            ];
            var data = [];

            for (let i = 0; i < purchasesProducts.length; i++) {
                data[i] = [
                    i + 1,
                    purchasesProducts[i].nombre,
                    purchasesProducts[i].categoria,
                    purchasesProducts[i].puntos,
                    purchasesProducts[i].total_canjeados,
                    purchasesProducts[i].total_puntos,
                    purchasesProducts[i].students,
                ];
            }

            pdf.text(
                20,
                20,
                "Reportes de los Productos Canjeados por los Alumnos del Colegio"
            );

            pdf.autoTable(columns, data, {
                startY: 40,
                styles: {
                    cellPadding: 7,
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
            pdf.save("ReporteCanjeosProductsT.pdf");
            swal("Exito", "Reporte Imprimido Exitosamente..", "success");
        })
        .catch(function (error) {
            console.log(error);
        });
}
