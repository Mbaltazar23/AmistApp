$(document).ready(function () {
    validadorRut("txtRut");

    if (document.querySelector("#formLogin")) {
        let formLogin = document.querySelector("#formLogin");
        formLogin.onsubmit = function (e) {
            e.preventDefault();
            let strRut = $('#txtRut').val();
            let strPassword = document.querySelector('#txtPassword').value;
            if (strRut == "")
            {
                swal("Error !!", "Porfavor.. Ingrese su rut...", "error");
                return false;
            } else if (strPassword == "") {
                swal("Error !!", "Porfavor.. Ingrese  su contraseña...", "error");
                return false;
            } else if (strPassword.length > 20) {
                swal("Error", "La contraseña ingresada no es valida...", "error");
                return false;
            } else {
                axios.post('/login', {
                    dni: strRut,
                    password: strPassword
                  })
                    .then(function (response) {
                      if (response.data.success) {
                        swal({
                          title: "Exito !",
                          text: "Bienvenido(a) señor(a) " + response.data.userData.name,
                          icon: "success"
                        }).then(function () {
                          window.location = '/dashboard';
                        });
                      } else {
                        swal("Atención", response.data.message, "error");
                        document.querySelector('#txtPassword').value = "";
                      }
                    })
                    .catch(function (error) {
                      console.log(error);
                    });
            }
        };
    }
});


function validadorRut(txtRut) {
    document.getElementById(txtRut).addEventListener('input', function (evt) {
        let value = this.value.replace(/\./g, '').replace('-', '');
        if (value.match(/^(\d{2})(\d{3}){2}(\w{1})$/)) {
            value = value.replace(/^(\d{2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4');
        } else if (value.match(/^(\d)(\d{3}){2}(\w{0,1})$/)) {
            value = value.replace(/^(\d)(\d{3})(\d{3})(\w{0,1})$/, '$1.$2.$3-$4');
        } else if (value.match(/^(\d)(\d{3})(\d{0,2})$/)) {
            value = value.replace(/^(\d)(\d{3})(\d{0,2})$/, '$1.$2.$3');
        } else if (value.match(/^(\d)(\d{0,2})$/)) {
            value = value.replace(/^(\d)(\d{0,2})$/, '$1.$2');
        }
        this.value = value;
    });
}