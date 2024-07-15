@extends('webpay/layout')
@section('title')
    Exchange Rate
@endsection

@section('body-class', 'hold-transition sidebar-mini sidebar-collapse')
@section('loginUsername')
    {{ Session::get('loginUsernamePay') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title" style="color: rgb(20, 156, 8)"><b>TỈ GIÁ NẠP - CÀO THẺ</b></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered text-nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>Loại Thẻ</th>
                                            <th>Thẻ<br> 10,000đ</th>
                                            <th>Thẻ<br> 20,000đ</th>
                                            <th>Thẻ<br> 50,000đ</th>
                                            <th>Thẻ<br> 100,000đ</th>
                                            <th>Thẻ<br> 200,000đ</th>
                                            <th>Thẻ<br> 500,000đ</th>
                                            <th>Thẻ<br> 1,000,000đ</th>
                                            <th>Thẻ<br> 2,000,000đ</th>
                                            <th>Thẻ<br> 3,000,000đ</th>
                                            <th>Thẻ<br> 5,000,000đ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($data->rate))
                                        <tr>
                                            <th>Tỉ giá</th>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                            <td>{{ $data->rate * 100 }}%</td>
                                        </tr>
                                        <tr>
                                            <th>Thực Nhận</th>
                                            <td>{{ 10000 - $data->rate * 10000 }} xu</td>
                                            <td>{{ 20000 - $data->rate * 20000 }} xu</td>
                                            <td>{{ 50000 - $data->rate * 50000 }} xu</td>
                                            <td>{{ 100000 - $data->rate * 100000 }} xu</td>
                                            <td>{{ 200000 - $data->rate * 200000 }} xu</td>
                                            <td>{{ 500000 - $data->rate * 500000 }} xu</td>
                                            <td>{{ 1000000 - $data->rate * 1000000 }} xu</td>
                                            <td>{{ 2000000 - $data->rate * 2000000 }} xu</td>
                                            <td>{{ 3000000 - $data->rate * 3000000 }} xu</td>
                                            <td>{{ 5000000 - $data->rate * 5000000 }} xu</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>

@endsection