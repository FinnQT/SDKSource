@extends('webpay/layout')
@section('title')
    Account
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
                            <div class="card-header">
                                <h3 class="card-title" style="color: rgb(20, 156, 8)"><b>THÔNG TIN CÁ NHÂN</b></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>TÊN ĐĂNG NHẬP</th>
                                            <td><input class="form-control" type="text" value="{{ $data->username }}"
                                                    disabled>
                                            <td>Không thể thay đổi</td>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>EMAIL</th>
                                            <td><input class="form-control" type="text" value="{{ $data->email }}"
                                                    disabled>
                                            <td>Không thể thay đổi</td>
                                        </tr>
                                        <tr>
                                            <th>MÃ BẢO VỆ</th>
                                            <td><input class="form-control" type="text" value="************"
                                                    disabled></td>
                                            <td>
                                                <a href="{{ route('forgotprotectcode_WP') }}" class="btn btn-block btn-success">Cập Nhật</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>HỌ VÀ TÊN</th>
                                            <td><input class="form-control" type="text" value="{{ $data->name }}"
                                                    disabled></td>
                                            <td rowspan="3">
                                                <button type="button" onclick="openBox()"
                                                    class="btn btn-block btn-success">Cập Nhật</button>
                                            </td>
                                      
                                        </tr>
                                        <tr>
                                            <th>SỐ CCCD</th>
                                            <td><input class="form-control" type="text" value="{{ $data->CCCD }}"
                                                    disabled>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>ĐỊA CHỈ</th>
                                            <td>
                                                <textarea class="form-control" name="detail_location" id="detail_location" disabled>{{ $data->location }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <!-- popup change info -->

                <div class="modal fade" id="popupconfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-primary"><b>NHẬP THÔNG TIN THAY ĐỔI</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="POST" id="form-updateInfo">
                                @csrf
                                <div class="modal-body">
                                    <div class="card">
                                        <!-- /.card-header -->
                                        <div class="card-body p-0">

                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th id="">HỌ VÀ TÊN</th>
                                                        <td><input class="form-control" type="text"
                                                                value="{{ $data->name }}" name="detail_name"
                                                                id="detail_name">
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>SỐ CCCD</th>
                                                        <td><input class="form-control" type="text"
                                                                value="{{ $data->CCCD }}" name="detail_CCCD"
                                                                id="detail_CCCD">
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                    <tr>
                                                        <th>ĐỊA CHỈ</th>
                                                        <td>
                                                            <textarea class="form-control" placeholder="" name="detail_location" id="detail_location">{{ $data->location }}
                                                        </textarea>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">HỦY</button>
                                    <button type="submit" class="btn btn-primary">CẬP NHẬT</button>
                                </div>
                            </form>
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
    <script>
        function openBox() {
            $('#popupconfirm').modal('show');
        }

        function closeBox() {
            $('#popupconfirm').modal('hide');
        }

        $(function() {
            @if (session('success'))
                var message = '{{ session('success') }}';
                alert(message);
            @endif
            $("#form-updateInfo").submit(function(e) {
                closeBox();
                e.preventDefault();
                var formData = $('#form-updateInfo').serialize();
                $.ajax({
                    url: "{{ route('updateInfo') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            alert("Cập nhật thành công");
                            window.location.reload()
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection
