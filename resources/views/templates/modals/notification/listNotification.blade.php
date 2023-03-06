@php
    $dashboard = new App\Http\Controllers\DashboardController();
    $notificationsToShow = $dashboard->getNotificationsToShow();
@endphp

@include('templates.modals.notification.modalResponseNot')
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ion ion-clipboard mr-1"></i>
                    Notificaciones Pendientes
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                    @foreach ($notificationsToShow as $notification)
                        <li>
                            <!-- drag handle -->
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <!-- todo text -->
                            <span class="text">{{ $notification['message'] }}</span>
                            <!-- Emphasis label -->
                            <small class="badge badge-danger"><i class="far fa-clock"></i>
                                {{ $notification['time_left'] }}</small>
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                                <i class="fas fa-edit" data-notification-id="{{ $notification['encryptedId'] }}"></i>
                                <i class="fas fa-trash-o"></i>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
</div>
