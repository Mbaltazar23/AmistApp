@extends('templates.app')

@section('content')
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{ url('/') }}" class="h1"><b>Amist</b>APP</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Ingrese sus credenciales para Inicia sesión</p>
            <form id="formLogin" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="txtRut" id="txtRut" class="form-control" placeholder="Rut...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-duotone fa-passport"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="txtPassword" name="txtPassword"class="form-control"
                        placeholder="Password..">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Recuérdame
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" id="btnLogin" class="btn btn-primary btn-block">Ingresar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection