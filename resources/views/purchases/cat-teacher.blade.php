@extends('templates.base')
@section('content')

@include('templates.modals.modalProducts')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>&nbsp;{{ $data['page_title'] }} - {{env("NOMBRE_DASHBOARD")}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item active">{{ $data['page_title'] }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-lg">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <div class="input-group mb-15">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                        {{$data['page_title']}} 
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" onclick="generarReporte();">Generar Reporte</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tableCatTeacher" class="table table-hover table-responsive-lg" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Categoria</th>
                                        <th>Puntaje</th>
                                        <th>Cantidad Solicitado</th>
                                        <th>Puntos Acumulados</th>
                                        <th>Status</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                        </div>
                        <!-- /.card-footer-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection