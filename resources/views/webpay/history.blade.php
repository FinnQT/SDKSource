@extends('webpay/layout')
@section('title')
    History Pay
@endsection
@section('link')
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
                <div class="row d-flex justify-content-center pt-5">
                    <div class="col-md-6">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped text-center">
                                    <thead>
                                      <tr>
                                        <th colspan="2">LỊCH SỬ GIAO DỊCH</th>
                                    </tr>
                                        <tr>
                                            <td><b>SỐ DƯ HIỆN TẠI</b></td>
                                            <td style="color:rgb(95, 0, 172)">{{ $data->balance}} Xu</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <a href="{{ route('transactionWallet') }}"
                                                    class="btn btn-block btn-success btn-lg">Lịch sử nạp
                                                    ví</a></td>

                                            <td>
                                                <a href="{{ route('transactionGame') }}"
                                                    class="btn btn-block btn-warning btn-lg">Lịch sử
                                                    nạp
                                                    game</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <script></script>
@endsection
