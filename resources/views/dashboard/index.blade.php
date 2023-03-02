@extends('templates.base')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">{{ $data['page_title'] }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Inicio</a></li>
                            <li class="breadcrumb-item active">{{ env('NOMBRE_DASHBOARD') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @foreach ($cardsPanel as $card)
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box {{ $card['color'] }}">
                                <div class="inner">
                                    <h3>{{ is_array($card['value']) ? implode(',', $card['value']) : $card['value'] }}</h3>
                                    <p>{{ $card['title'] }}</p>
                                </div>
                                <div class="icon">
                                    <i class="{{ $card['icon'] }}"></i>
                                </div>
                                <a href=" {{ url($card['url']) }}" class="small-box-footer">Más info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                    <!-- ./col -->
                </div>
                @if (Auth::user()->roles->first()->role == env('ROLALU'))
                    @include('templates.modals.notification.listNotification')
                @endif
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
