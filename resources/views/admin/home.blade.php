@extends('admin/layout')
@section('title')
    Admin home
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
                    <div class="col-lg-3 col-6">
                      <!-- small box -->
                      <div class="small-box bg-info">
                        <div class="inner">
                          <h3>10</h3>
                          <p>NRU (Số người chơi đăng ký mới trong ngày)</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                      </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                      <!-- small box -->
                      <div class="small-box bg-success">
                        <div class="inner">
                          <h3>20</h3>
                          <p>DAU (Số người chơi hoạt động mỗi ngày)</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer"> </i></a>
                      </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                      <!-- small box -->
                      <div class="small-box bg-warning">
                        <div class="inner">
                          <h3>6</h3>
                          <p>PU (Số người chơi trả phí trong ngày)</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                      </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                      <!-- small box -->
                      <div class="small-box bg-danger">
                        <div class="inner">
                          <h3>7,832đ</h3>
                          <p>ARPPU (Doanh thu trung bình mỗi người chơi)</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                      </div>
                    </div>
                    <!-- ./col -->
                  </div>

            </div>
        </section>
    </div>
@endsection
