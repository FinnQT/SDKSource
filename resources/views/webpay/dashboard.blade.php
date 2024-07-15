@extends('webpay/layout')
@section('title')
    Dasboard
@endsection
@section('body-class', 'hold-transition sidebar-mini sidebar-collapse')
@section('loginUsername')
    {{ Session::get('loginUsernamePay') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-2 d-flex justify-content-center ">
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                              <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('assets\clients\image\mong-giang-ho-sap-ra-mat-game-thu-viet-chinh-la-dai-vo-hiep-vat-ngu.webp') }}"
                                     alt="User profile picture">
                              </div>
                              <p class="text-muted text-center">Thông Tin Tài Khoản</p>
              
                              <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                  <b>Tài Khoản</b> <p class="float-right">{{ $data->username }}</p>
                                </li>
                                <li class="list-group-item">
                                  <b>Số dư</b> <p class="float-right">{{ $data->balance }} xu</p>
                                </li>
                                <li class="list-group-item">
                                  <b>Thông tin tài khoản</b> <a href="{{ route('account') }}" class="float-right">Cập nhật</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lịch sử nạp</b> <a href="{{ route('history') }}" class="float-right">Xem</a>
                                  </li>
                              </ul>
                                <a href="{{ route('recharge') }}" class="btn btn-primary btn-block"><b>Nạp Ví</b></a>
                                <a href="#" class="btn btn-primary btn-block"><b>Nạp Game</b></a>
                            </div>
                            <!-- /.card-body -->
                          </div>
                    </div>
                    
                </div>
            </div>
        </section>
    </div>

    
@endsection
