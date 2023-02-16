
document.addEventListener('DOMContentLoaded', function () {
    getPutPerfil();
    validadorRut('txtRut');

    //Actualizar Perfil
    if (document.querySelector("#formPerfil")) {
        let formPerfil = document.querySelector("#formPerfil");
        formPerfil.onsubmit = function (e) {
            e.preventDefault();
            let strApellido = document.querySelector('#txtApellido').value;
            let intTelefono = document.querySelector('#txtTelefono').value;
            let strPassword = document.querySelector('#txtPassword').value;
            let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;

            if (strApellido == '' || intTelefono == '')
            {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                document.querySelector('#txtTelefono').value = "+569";
                return false;
            }
            if (strPassword != '') {
                if (strPassword != "" || strPasswordConfirm != "")
                {
                    if (strPassword != strPasswordConfirm) {
                        swal("Atención", "Las contraseñas no son iguales.", "info");
                        return false;
                    }
                    if (strPassword.length < 8) {
                        swal("Atención", "La contraseña debe tener un mínimo de 8 caracteres.", "info");
                        return false;
                    }
                }
            }
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Usuarios/putPerfil';
            let formData = new FormData(formPerfil);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState != 4)
                    return;
                if (request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        $('#modalFormPerfil').modal("hide");
                        swal("Exito !!", objData.msg, "success");
                        getPutPerfil();
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        };
    }

}, false);

function getPutPerfil() {
    let ajaxUrl = base_url + '/Home/getPutUsuario';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState != 4)
            return;
        if (request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                //console.log(objData);
                document.querySelector("#txtRut").value = objData.data.dni;
                document.querySelector("#txtNombre").value = objData.data.nombre.toString().toLocaleLowerCase();
                document.querySelector("#txtEmail").value = objData.data.email.toString().toLocaleLowerCase();
                document.querySelector("#txtDireccion").value = objData.data.direccion.toString().toLocaleLowerCase();
            }
        }
    };
}