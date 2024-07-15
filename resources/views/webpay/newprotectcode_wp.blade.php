@extends('clients/layout')
@section('title')
    New Protect Code
@endsection
@section('style')
@endsection
@section('body-class', 'hold-transition login-page')
@section('content')
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">CẬP NHẬT MÃ BẢO VỆ MỚI</p>
                <form action="{{ route('resetprotectcode_WP.post') }}" method="post">
                    @csrf
                    <input type="text" class="form-control" hidden name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Nhập email" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <span style="color: red";>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Nhập mã bảo vệ mới"
                                name="protect_code">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @error('protect_code')
                            <span style="color: red";>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Xác nhận lại mã bảo vệ"
                                name="cprotect_code">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @error('cprotect_code')
                            <span style="color: red";>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Change password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="{{ route('loginPay') }}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
