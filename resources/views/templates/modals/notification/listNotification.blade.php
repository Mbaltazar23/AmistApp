
@include("templates.modals.notification.modalResponseNot")
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
                    <li>
                        <!-- drag handle -->
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <!-- checkbox -->
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo1" id="todoCheck1">
                            <label for="todoCheck1"></label>
                        </div>
                        <!-- todo text -->
                        <span class="text">Design a nice theme</span>
                        <!-- Emphasis label -->
                        <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
</div>