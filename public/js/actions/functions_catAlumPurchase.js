let tableProductos;
$(document).on("focusin", function (e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

window.addEventListener(
    "load",
    function () {
        tableProductos = $("#tableProductPur").dataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
            },
            columns: [
                { data: "nameP" },
                { data: "categoria" },
                { data: "stock" },
                { data: "total_puntos" },
                {
                    data: "options",
                    render: function (data) {
                        return data;
                    },
                },
            ],
            ajax: {
                url: "/purchases/alum",
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


function fntDelCanj(idProducto){
    swal({
        title: "Devolver Producto",
        text: "¿Quiere recuperar sus puntos por este producto?",
        icon: "warning",
        buttons: true,
    }).then((isClosed) => {
        if (isClosed) {
            {
                axios
                    .post(`/purchases/delPurchase/${idProducto}`)
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