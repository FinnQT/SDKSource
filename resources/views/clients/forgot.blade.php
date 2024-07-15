@extends('clients/layout')
@section('title')
    Forgot Password
@endsection
@section('link')
    <link rel="stylesheet" href="{{ asset('assets\clients\popup.css') }}">
@endsection
@section('body-class', 'hold-transition login-page')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>Quên Mật Khẩu</b></a>
        </div>
        <div class="card pt-4 shadow rounded p-1">
            <p class="login-box-msg"><b>NHẬP THÔNG TIN CẦN THIẾT</b></p>
            <div class="alert alert-danger" id="messages-fail" style="display: none; margin-left: 20px; margin-right: 20px;">
            </div>
            <div class="alert alert-success" id="messages-sucess"
                style="display: none; margin-left: 20px; margin-right: 20px;"></div>
            <form id="form-validate" action="{{ route('forgot.validate') }}" method="post">
                <div class="card-body login-card-body" id="card1">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nhập tài khoản" name="username"
                                value="{{ old('username') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div id="username-error" style="color: red"></div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Nhập mã bảo vệ" name="protect_code">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div id="protect_code-error" style="color: red"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Xác Nhận</button>
                    @csrf
            </form>
        </div>
        <div class="card-body login-card-body" id="card2" style="pointer-events: none; opacity: 0.2;">
            <p class="login-box-msg"><b>THAY ĐỔI MẬT KHẨU MỚI</b></p>
            <form id="form-newpassord" method="post">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu mới" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div id="password-error" style="color: red"></div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu mới" name="cpassword">
                        <input type="hidden" name="hidden_value" value="Hidden Value" id="hidden_value">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div id="cpassword-error" style="color: red"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Xác Nhận</button>
                @csrf
            </form>
        </div>
        <p class="text-center">
            <a href="{{ route('forgotprotectcode') }}"><b>Quên mã bảo vệ</b></a>
        </p>
        <p class="text-center">
            <a href="{{ route('login') }}"><b>Quay lại</b></a>
        </p>
 
    </div>

    </div>
    <div class="modal fade" id="successpopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color: #28a745;"><b>THÀNH CÔNG</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">
                                <tr>
                                    <th id="error_details" class="text-center">Đổi mật khẩu thành công - Quay về đăng
                                        nhập</th>
                                </tr>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="closeSuccessPopup()">OKE</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function closeSuccessPopup() {
            window.location.href = "{{ route('login') }}";
        }
        function openSuccessPopup() {
            $('#successpopup').modal('show');
        }
        $(function() {
            $("#form-validate").submit(function(e) {
                e.preventDefault();
                var formData = $('#form-validate').serialize();
                $.ajax({
                    url: "{{ route('forgot.validate') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            $('#messages-sucess').html(res.messages);
                            $('#messages-fail').css('display', 'none');
                            $('#messages-sucess').css('display', 'block')
                            $('#username-error').empty();
                            $('#protect_code-error').empty();
                            $('#card2').css({
                                'pointer-events': 'auto'
                            });
                            $('#card2').css({
                                'opacity': '1'
                            });
                            $('#card1').css({
                                'pointer-events': 'none'
                            });
                            $('#card1').css({
                                'opacity': '0.2'
                            });
                            $('#hidden_value').val(res.username);
                        } else {
                            if (res.messages) {
                                $('#username-error').empty();
                                $('#protect_code-error').empty();
                                $('#messages-fail').html(res.messages);
                                $('#messages-fail').css('display', 'block');
                            } else {
                                $('#messages-fail').css('display', 'none');
                                if (res.message.username) {
                                    $('#username-error').html(res.message.username[0]);
                                } else {
                                    $('#username-error').empty();
                                }
                                if (res.message.protect_code) {
                                    $('#protect_code-error').html(res.message.protect_code[0]);
                                } else {
                                    $('#protect_code-error').empty();
                                }
                            }
                        }
                    }
                });
            });
        })

        $(function() {
            $('#form-newpassord').submit(function(e) {
                e.preventDefault();
                var formData = $('#form-newpassord').serialize();
                $.ajax({
                    url: "{{ route('forgot.newpass') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            $('#messages-fail').css('display', 'none');
                            $('#password-error').empty();
                            $('#cpassword-error').empty();
                            openSuccessPopup();

                        } else {
                            $('#messages-sucess').css('display', 'none');
                            if (res.messages) {
                                $('#messages-fail').html(res.messages);
                                $('#messages-fail').css('display', 'block');
                            } else {
                                $('#messages-fail').css('display', 'none');
                                if (res.message.password) {
                                    $('#password-error').html(res.message.password[0]);
                                } else {
                                    $('#password-error').empty();
                                }
                                if (res.message.cpassword) {
                                    $('#cpassword-error').html(res.message.cpassword[0]);
                                } else {
                                    $('#cpassword-error').empty();
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
