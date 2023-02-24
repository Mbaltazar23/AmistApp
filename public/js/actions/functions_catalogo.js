let tableProductos;
$(document).on("focusin", function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

window.addEventListener(
    "load",
    function () {
        tableProductos = $("#tableCatalogo").dataTable({
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
                url: "/purchases",
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
        .get(`/purchases/getPurchase/${idProducto}`)
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
