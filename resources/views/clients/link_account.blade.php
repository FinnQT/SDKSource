@extends('clients/layout')
@section('title')
    Link Account
@endsection
@section('link')
@endsection
@section('body-class', 'hold-transition register-page')
@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href=""><b>Liên Kết Tài Khoản</b></a>
        </div>
        <div class="card shadow rounded p-1">
            <div class="card-body register-card-body">
                <form action="{{ route('linkAccountPost') }}" method="post" id="form-register">
                  <input type="hidden" name="hidden_uuid" value={{ $uuid }} id="hidden_value">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nhập tên tài khoản" name="username"
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
                            <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div id="password-error" style="color: red"></div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="cpassword">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div id="cpassword-error" style="color: red"></div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Nhập mã bảo vệ (bảo mật cấp 2)"
                                name="protect_code">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div id="protect_code-error" style="color: red"></div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Nhập email" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div id="email-error" style="color: red"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mb-2">Liên Kết Tài Khoản</button>
                    @csrf
                </form>
                <p class="text-center">
                    <a href="{{ route('dashboard') }}"><b>Quay lại</b></a>
                </p>
            </div>
        </div>
    </div>
    {{-- pop up success --}}
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
                                    <th id="error_details" class="text-center">Liên kết tài khoản thành công - Quay về Trang chủ</th>
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
            $("#form-register").submit(function(e) {
                e.preventDefault();
                var formData = $('#form-register').serialize();
                $.ajax({
                    url: "{{ route('linkAccountPost') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            $('#username-error').empty();
                            $('#password-error').empty();
                            $('#cpassword-error').empty();
                            $('#protect_code-error').empty();
                            $('#email-error').empty();

                            openSuccessPopup();
                            $('#messages-fail').css('display', 'none');
                        } else {
                            if (res.messages) {
                                $('#messages-fail').html(res.messages);
                                $('#messages-fail').css('display', 'block');
                            } else {
                                $('#messages-fail').css('display', 'none');
                                if (res.message.username) {
                                    $('#username-error').html(res.message.username[0]);
                                } else {
                                    $('#username-error').empty();
                                }
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

                                if (res.message.protect_code) {
                                    $('#protect_code-error').html(res.message.protect_code[0]);
                                } else {
                                    $('#protect_code-error').empty();
                                }
                                if (res.message.email) {
                                    $('#email-error').html(res.message.email[0]);
                                } else {
                                    $('#email-error').empty();
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
