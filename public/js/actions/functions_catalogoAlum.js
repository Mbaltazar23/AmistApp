let tableProductos;
$(document).on("focusin", function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

window.addEventListener(
    "load",
    function () {
        tableProductos = $("#tableCatProducts").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nameP" },
                { data: "category" },
                { data: "puntos" },
                { data: "stock" },
                { data: "status" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/purchases/cat",
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

function fntCanjProduct(idProducto){
    swal({
        title: "Canjear Producto",
        text: "¿Quiere canjear y adquirir este producto?",
        icon: "info",
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            {
                axios
                    .post(`/purchases/setPurchase/${idProducto}`)
                    .then((response) => {
                        if (response.data.status) {
                            swal("Exito !!", response.data.msg, "success");
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
