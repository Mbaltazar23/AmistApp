document.addEventListener(
    "DOMContentLoaded",
    function () {
        getPutPerfil();
        validadorRut("txtRut");

        //Actualizar Perfil
        if (document.querySelector("#formPerfil")) {
            let formPerfil = document.querySelector("#formPerfil");
            formPerfil.onsubmit = function (e) {
                e.preventDefault();
                let txtRut = document.querySelector("#txtRut").value;
                let txtNombre = document.querySelector("#txtNombre").value;
                let intTelefono = document.querySelector("#txtTelefono").value;
                let strPassword = document.querySelector("#txtPassword").value;
                let strPasswordConfirm = document.querySelector(
                    "#txtPasswordConfirm"
                ).value;

                if (txtRut == "" || txtNombre == "" || intTelefono == "") {
                    swal(
                        "Atención",
                        "Todos los campos son obligatorios.",
                        "error"
                    );
                    document.querySelector("#txtTelefono").value = "+569";
                    return false;
                }
                if (strPassword != "") {
                    if (strPassword != "" || strPasswordConfirm != "") {
                        if (strPassword != strPasswordConfirm) {
                            swal(
                                "Atención",
                                "Las contraseñas no son iguales.",
                                "info"
                            );
                            return false;
                        }
                        if (strPassword.length < 8) {
                            swal(
                                "Atención",
                                "La contraseña debe tener un mínimo de 8 caracteres.",
                                "info"
                            );
                            return false;
                        }
                    }
                }
                let formData = new FormData(formPerfil);
                axios
                    .post("/dashboard/putProfile", formData)
                    .then(function (response) {
                        if (response.data.status) {
                            swal({
                                title: "Exito !",
                                text: response.data.msg,
                                icon: "success",
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            swal("Error", objData.msg, "error");
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
            };
        }
    },
    false
);

function getPutPerfil() {
    axios
        .get(`/dashboard/getProfile`)
        .then(function (response) {
            if (response.data.status) {
                //console.log(objData);
                document.querySelector("#txtRut").value =
                    response.data.data.dni;
                document.querySelector("#txtNombre").value =
                    response.data.data.nombre.toString().toLocaleLowerCase();
                document.querySelector("#txtEmail").value =
                    response.data.data.email.toString().toLocaleLowerCase();
                document.querySelector("#txtTelefono").value =
                    response.data.data.telefono;
                document.querySelector("#txtDireccion").value =
                    response.data.data.direccion != ""
                        ? response.data.data.direccion
                              .toString()
                              .toLocaleLowerCase()
                        : "";
            }
        })
        .catch(function (error) {
            console.log(error);
            swal("Error", "Ocurrió un error al procesar la petición", "error");
        });
}
