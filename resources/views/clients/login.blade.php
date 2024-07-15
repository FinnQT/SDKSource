@extends('clients/layout')
@section('title')
    Login
@endsection
@section('style')
@endsection
@section('body-class', 'hold-transition login-page')
@section('content')
    <div class="login-box ">
        <div class="login-logo">
            <a href=""><b>Đăng Nhập</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card shadow rounded p-1">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Bắt đầu phiên đăng nhập</p>
                <form action="{{ route('login.post') }}" method="post">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger">
                            {{ Session::get('fail') }}
                        </div>
                    @endif
                    <div>
                        <h6>Tài Khoản</h6>
                    </div>
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
                        @error('username')
                            <span style="color: red";>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <h6>Mật Khẩu</h6>
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
                        @error('password')
                            <span style="color: red";>{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    @csrf
                </form>
                <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('register') }}" class="btn btn-block btn-primary">Đăng ký</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('forgot') }}" class="btn btn-block btn-primary">Quên mật khẩu</a>
                        </div>
                    </div>
                </div>
                <p class="mb-1 text-center">
                    <a href="{{ route("registerTestAcc") }}" ><b>Chơi Ngay</b></a>
                </p>
            </div>
        </div>
    </div>
@endsection
