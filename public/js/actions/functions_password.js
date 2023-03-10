$(document).ready(function () {
    if (document.querySelector("#formRecover")) {
        let formRecover = document.querySelector("#formRecover");
        formRecover.onsubmit = function (e) {
            e.preventDefault();

            let txtEmail = $("#txtEmail").val();
            let regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (txtEmail == "") {
                swal(
                    "Por favor",
                    "Escriba su correo para identificarlo.",
                    "error"
                );
                return false;
            } else if (!regex.test(txtEmail.trim())) {
                $("#txtEmail").val("");
                swal("Error", "El correo ingresado no es valido...", "error");
                return false;
            } else {
                axios
                    .post("/login/getEmail", {
                        email: txtEmail,
                    })
                    .then(function (response) {
                        if (response.data.success) {
                            swal({
                                title: "Exito !",
                                text: response.data.msg,
                                icon: "success",
                            }).then(function () {
                                window.location = "/change-password";
                            });
                        } else {
                            swal("Atención", response.data.message, "error");
                            document.querySelector("#txtEmail").value = "";
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        };
    }

    if (document.querySelector("#formResetPass")) {
        let formResetPass = document.querySelector("#formResetPass");
        formResetPass.onsubmit = function (e) {
            e.preventDefault();
            let strPassword = $("#txtPassword01").val();
            let strPassword02 = $("#txtPassword02").val();
            if (
                strPassword == "" ||
                strPassword02 != strPassword ||
                strPassword.length < 8
            ) {
                document.querySelector("#txtPassword01").value = "";
                document.querySelector("#txtPassword02").value = "";
                swal(
                    "Error !!",
                    "La Password ingresada no es valida !!",
                    "error"
                );
                return false;
            } else {
                let formData = new FormData(formResetPass);
                axios
                    .post("/login/setPassword", formData)
                    .then(function (response) {
                        if (response.data.success) {
                            swal({
                                title: "Exito !",
                                text:
                                    "Bienvenido(a) señor(a) " +
                                    response.data.userData.name,
                                icon: "success",
                            }).then(function () {
                                window.location = "/dashboard";
                            });
                        } else {
                            swal("Atención", response.data.message, "error");
                            document.querySelector("#txtPassword01").value = "";
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        };
    }
});
