@extends('templates.app')

@section('content')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Amist</b>APP</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Ahora puede recuperar su Password</p>
                <form id="formResetPass" name="formResetPass" method="post">
                    <div class="input-group mb-3">
                        <input type="password" id="txtPassword01" name="txtPassword01" class="form-control"
                            placeholder="Ingrese su nueva Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="txtPassword02" name="txtPassword02" class="form-control"
                            placeholder="Repita su nueva Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Cambiar Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">Recuerda su Password?</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
