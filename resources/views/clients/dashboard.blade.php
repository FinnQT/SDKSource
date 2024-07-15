@extends('clients/layout')
@section('title')
    Dasboard
@endsection
@section('style')
    <style>
        body {
            background-image: url("https://didongviet.vn/dchannel/wp-content/uploads/2023/08/hinh-nen-3d-hinh-nen-iphone-dep-3d-didongviet@2x-576x1024.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
    </style>
@endsection
@section('body-class', 'hold-transition login-page')
@section('content')
    <div class="login-box" style="background-color: #150dac">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div>
                </div>
                <div class="text-center">
                    <h3 style="color: #28a745;"><b>Đăng nhập thành công</b></h3>
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ asset('assets\clients\image\mong-giang-ho-sap-ra-mat-game-thu-viet-chinh-la-dai-vo-hiep-vat-ngu.webp') }}"
                        alt="User profile picture">
                    <div>
                        Xin chào,<br>
                        <span style="color: #150dac;"><b>{{ $data->username }}</b></span>
                    </div>
                </div>
                <ul class="list-group list-group-unbordered mb-3">

                </ul>
                <a href="" class="btn btn-primary btn-block"><b>Vào trò chơi</b></a>
                <a href="{{ route('logout') }}" class="btn btn-primary btn-block"><b>Thoát tài khoản</b></a>
                @if($data->link==0)
                <a href="{{ route('linkAccount',['uuid'=>$data->uuid]) }}" class="btn btn-primary btn-block"><b>Liên kết tài khoản</b></a>
                @endif 
            </div>
            <!-- /.card-body -->
        </div>


    </div>
@endsection
