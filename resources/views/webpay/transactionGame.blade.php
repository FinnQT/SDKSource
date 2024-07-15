@extends('webpay/layout')
@section('title')
    Transaction Game
@endsection
@section('link')
@endsection
@section('body-class', 'hold-transition sidebar-mini sidebar-collapse')
@section('loginUsername')
    {{ Session::get('loginUsernamePay') }}
@endsection
@section('content')
<div class="content-wrapper">
  <section class="content">
      <div class="container-fluid">
          <div class="row text-center row d-flex justify-content-center align-items-center">
              <div class="col-md-10 mt-5">
                  <div class="card">
                      <div class="card-header">
                        <h3 class="card-title" style="color: rgb(20, 156, 8)"><b>LỊCH SỬ NẠP GAME-  30 GIAO DỊCH GẦN NHẤT</b></h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body table-responsive p-0" style="height: 400px;">
                          <table class="table table-head-fixed text-nowrap">
                              <thead>
                                  <tr>
                                      <th>STT</th>
                                      <th>Mã GD</th>
                                      <th>Loại</th>
                                      <th>Serial</th>
                                      <th>Mệnh giá</th>
                                      <th>Trạng thái</th>
                                      <th>Nội dung</th>
                                      <th>Thời gian</th>
                                  </tr>
                              </thead>
                              <tbody>

                              </tbody>
                          </table>
                      </div>
                      <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
              </div>
          </div>
      </div>
  </section>
</div>
@endsection
