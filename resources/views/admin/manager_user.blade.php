@extends('admin/layout')
@section('title')
    Manage User
@endsection
@section('body-class', 'hold-transition sidebar-mini sidebar-collapse')
@section('admin')
    {{ Session::get('admin') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title" style="color: rgb(20, 156, 8)"><b>Quản Lý Người Chơi</b></h3>
                                <div class="card-tools">
                                    <form action="{{route('findUser')}}" method="GET" id="form-search">
                                        @csrf
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="username_search" class="form-control float-right"
                                                placeholder="Search">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height:70vh;">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width:5px">STT</th>
                                            <th>Hành Động</th>
                                            <th>Tên Tài Khoản</th>
                                            <th>UUID</th>
                                            <th>Email</th>
                                            <th>Số dư</th>
                                            <th>Họ Tên</th>
                                            <th>Địa Chỉ</th>
                                            <th>CCCD</th>
                                            <th>Trạng Thái</th>
                                            <th>Liên Kết</th>
                                            <th>Quyền</th>
                                            <th>Thời Gian Tạo</th>
                                            <th>Thời Gian Đăng Nhập Gần Nhất</th>
                                            <th>Ip Tạo Tài Khoản</th>
                                            <th>Ip Đăng Nhập Gần Nhất</th>
                                            <th>Log Đổi Thông Tin</th>
                                            <th>Log Đổi Mã Bảo vệ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="">
                                                    <button type="button" onclick="openBox({{ json_encode($user) }})"
                                                        class="btn btn-success"><i class="fas fa-pencil-alt"></i></button>
                                                    <button type="button" onclick="openDeleteBox({{ json_encode($user) }})"
                                                        class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                                </td>
                                                <td>{{ $user->username }}</td>
                                                <td>{{ $user->uuid }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->balance }}đ</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->location }}</td>
                                                <td>{{ $user->CCCD }}</td>
                                                <td> <span>
                                                        {{ $user->status == 1 ? 'Activate' : 'Banned' }}</span>
                                                </td>
                                                <td>{{ $user->link == 1 ? 'Linked' : 'Not Linked' }}</td>
                                                <td>{{ $user->is_admin == 1 ? 'Admin' : 'User' }}</td>
                                                <td>{{ $user->created_at }}</td>
                                                <td>{{ $user->updated_at }}</td>
                                                <td>{{ $user->ip_address }}</td>
                                                <td>{{ $user->last_login_ip }}</td>
                                                <td>
                                                    <button type="button"
                                                        onclick="openBoxLog({{ json_encode($user->log_change_inf ?? '') }})"
                                                        class="btn btn-success"><i class="fas fa-eye"></i></button>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        onclick="openBoxLog({{ json_encode($user->log_protect_code ?? '') }})"
                                                        class="btn btn-success"><i class="fas fa-eye"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="modal fade" id="popupconfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-primary"><b>CẬP NHẬT THÔNG TIN NGƯỜI CHƠI</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="POST" id="form-updateInfo">
                                @csrf
                                <input type="hidden" name="hidden_username" value="Hidden Value" id="hidden_value">

                                <div class="modal-body">
                                    <div class="card">
                                        <!-- /.card-header -->
                                        <div class="card-body p-0">
                                            <div id="email-error" style="color: red"></div>
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>

                                                        <th id="">Email</th>
                                                        <td><input class="form-control" type="email" name="detail_email"
                                                                id="detail_email">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th id="">Họ và tên</th>
                                                        <td><input class="form-control" type="text" name="detail_name"
                                                                id="detail_name">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>ĐỊA CHỈ</th>
                                                        <td>
                                                            <textarea class="form-control" placeholder="" name="detail_location" id="detail_location">
                                                            </textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>SỐ CCCD</th>
                                                        <td><input class="form-control" type="text" name="detail_CCCD"
                                                                id="detail_CCCD">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Trạng Thái</th>
                                                        <td>
                                                            <select class="form-control" name="status_pick" id="status_pick"
                                                                onchange="handleSelectMonney()">
                                                                <option value="1">Activate</option>
                                                                <option value="0">Banned</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Quyền</th>
                                                        <td>
                                                            <select class="form-control" name="role_pick" id="role_pick"
                                                                onchange="handleSelectMonney()">
                                                                <option value="1">Admin</option>
                                                                <option value="0">User</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">HỦY</button>
                                    <button type="submit" class="btn btn-primary">CẬP NHẬT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="popupconfirmDelete" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-warning"><b>XÁC NHẬN XÓA NGƯỜI DÙNG</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="POST" id="form-delete">
                                @csrf
                                <input type="hidden" name="hidden_username_dl" value="Hidden Value"
                                    id="hidden_username_dl">
                                <div class="modal-body">
                                    <div class="card">
                                        <!-- /.card-header -->
                                        <div class="card-body p-3 text-center">
                                            <h3><b>Nếu như xác nhận, mọi thông tin người dùng sẽ bị xóa vĩnh viễn</b></h3>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">HỦY</button>
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Scrollable modal -->
                <div class="modal fade" id="popup_log" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-primary"><b>Lịch Sử Người Dùng Cập Nhật Thông Tin</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body p-3">
                                        <p id="content_log"></p>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">OKE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        function openBox(user) {
            document.getElementById("hidden_value").value = user.username;
            document.getElementById("detail_email").value = user.email;
            document.getElementById("detail_name").value = user.name;
            document.getElementById("detail_location").value = user.location;
            document.getElementById("detail_CCCD").value = user.CCCD;
            document.getElementById("status_pick").value = user.status;
            document.getElementById("role_pick").value = user.is_admin;
            $('#popupconfirm').modal('show');
        }

        function closeBox() {
            $('#popupconfirm').modal('hide');
        }

        function openDeleteBox(user) {
            document.getElementById("hidden_username_dl").value = user.username;
            $('#popupconfirmDelete').modal('show');
        }

        function closeDeleteBox() {
            $('#popupconfirmDelete').modal('hide');
        }

        function openBoxLog(logcontent) {
            const parts = logcontent.split('|');
            const result = parts.join('<br>');
            document.getElementById("content_log").innerHTML = result;
            $('#popup_log').modal('show');
        }

        function closeBoxLog() {
            $('#popup_log').modal('hide');
        }

        $(function() {
            $("#form-updateInfo").submit(function(e) {
                e.preventDefault();
                var formData = $('#form-updateInfo').serialize();
                $.ajax({
                    url: "{{ route('updateInfoUser') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            closeBox();
                            setTimeout(function() {
                                alert("Cập nhật thành công");
                                window.location.reload()
                            }, 300);
                        } else {
                            if (res.messages.detail_email) {
                                $('#email-error').html(res.message.detail_email[0]);
                            } else {
                                closeBox();
                                setTimeout(function() {
                                    alert("Cập nhật thất bại");
                                }, 300);

                            }
                        }
                    }
                });
            });
            $("#form-delete").submit(function(e) {
                e.preventDefault();
                var formData = $('#form-delete').serialize();
                $.ajax({
                    url: "{{ route('delete') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            closeDeleteBox();
                            setTimeout(function() {
                                alert(res.message);
                                window.location.reload()
                            }, 300);
                        } else {
                            closeDeleteBox();
                            setTimeout(function() {
                                alert(res.message);
                            }, 300);
                        }
                    }
                });
            });
            // $("#form-search").submit(function(e) {
            //     e.preventDefault();
            //     var formData = $('#form-search').serialize();
            //     $.ajax({
            //         url: "",
            //         method: "POST",
            //         data: formData,
            //         dataType: 'json',
            //         success: function(res) {
            //             if (res.status == 200) {
            //                 closeDeleteBox();
            //                 setTimeout(function() {
            //                     alert(res.message);
            //                     window.location.reload()
            //                 }, 300);
            //             } else {
            //                 closeDeleteBox();
            //                 setTimeout(function() {
            //                     alert(res.message);
            //                 }, 300);
            //             }
            //         }
            //     });
            // });
        });
    </script>
@endsection
