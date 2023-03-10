@extends('templates.app')

@section('content')
<div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{ url('/') }}" class="h1"><b>Amist</b>APP</a>
    </div>
      <div class="card-body">
        <p class="login-box-msg">¿Olvidaste tu Password?
             Aquí puedes recuperar fácilmente una nueva Password
        </p>
        <form id="formRecover" name="formRecover" method="post">
          <div class="input-group mb-3">
            <input type="text" id="txtEmail" name="txtEmail" class="form-control" placeholder="Ingrese su correo ">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Resetear Password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <p class="mt-3 mb-1">
          <a href="{{route('login')}}">Recuerda su Password?</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
@endsection