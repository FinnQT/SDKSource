@extends('clients/layout')
@section('title')
    Forgot Protect Code
@endsection
@section('style')
@endsection
@section('body-class', 'hold-transition login-page')
@section('content')
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                <form action="{{ route('forgotprotectcode.post') }}" method="post">
                    @csrf
                    <div class=" mb-3">
                        @error('email')
                            <span style="color: red";>{{ $message }}</span>
                        @enderror
                        @if (Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (Session::has('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                        @endif
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Email" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Gửi yêu cầu đổi mã bảo vệ</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">Đăng Nhập</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Đăng ký tài khoản mới</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
