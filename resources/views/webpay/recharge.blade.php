@extends('webpay/layout')
@section('title')
    Recharge
@endsection
@section('link')
    <link rel="stylesheet" href="{{ asset('assets\clients\loader.css') }}">
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
                <div class="row  d-flex justify-content-center">
                    <div class="col-md-6 mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title" style="color: rgb(20, 156, 8)"><b>ĐỔI THẺ CÀO - NẠP QR</b></h3>
                            </div>
                            <form action="" method="POST" id="form-card">
                                <input type="hidden" name="usernameRq" value="{{ $data->username }}" id="hiddenField">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="alert alert-danger" id="messages-fail" style="display: none;"></div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Loại Thẻ</th>
                                                <td>
                                                    <select class="form-control" name="type_pay" id="type-pay"
                                                        style="width:100%;" onchange=handleSelection()>
                                                        <option value="">Loại thẻ</option>
                                                        <option value="CardInputGate">Gate</option>
                                                        <option value="QRCode">QRCode Banking</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Mệnh Giá</th>
                                                <td>
                                                    <div class="group-field-alert  mb-3">
                                                        <select class="form-control" id="blank_selection"
                                                            style="width:100%;">
                                                            <option value="">Vui lòng chọn loại thẻ</option>
                                                        </select>
                                                        <select class="form-control" name="monney_pick" id="droplist_money"
                                                            style="width:100%; display:none;"
                                                            onchange="handleSelectMonney()">
                                                            <option value="">---Chọn mệnh giá---</option>
                                                            <option value="10000">10,000</option>
                                                            <option value="20000">20,000</option>
                                                            <option value="50000">50,000</option>
                                                            <option value="100000">100,000</option>
                                                            <option value="200000">200,000</option>
                                                            <option value="500000">500,000</option>
                                                            <option value="1000000">1,000,000</option>
                                                            <option value="2000000">2,000,000</option>
                                                            <option value="3000000">3,000,000</option>
                                                            <option value="5000000">5,000,000</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Seri</th>
                                                <td>
                                                    <div class="group-field-alert mb-3">
                                                        <input type="text" class="form-control"
                                                            placeholder="Nhập mã seri" id="seri" name="seri">
                                                        <div id="seri-error" style="color: red"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pin</th>
                                                <td>
                                                    <div class="group-field-alert mb-3">
                                                        <input type="text" id="pin" name="pin"
                                                            class="form-control" placeholder="Nhập mã pin">
                                                        <div id="pin-error" style="color: red"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <button type="submit" class="btn btn-primary btn-block float-left">Nạp Ngay</button>
                                </div>
                                @csrf
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <!--pop up confirm-->
                <div class="modal fade" id="popupconfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-primary"><b>XÁC NHẬN NẠP THẺ</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Thẻ</th>
                                                    <td id="popup_type_pay"></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Serial</th>
                                                    <td id="popup_seri"></td>
                                                </tr>
                                                <tr>
                                                    <th>Pin</th>
                                                    <td id="popup_pin"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <div class="text-center text-danger pl-3 pr-3">
                                <p><b>MUỘI SẼ KHÔNG HOÀN TRẢ NẾU THÔNG TIN CỦA HUYNH KHÔNG CHÍNH XÁC</b></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                <button type="button" class="btn btn-primary" onclick="confirm()">Xác Nhận</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- loader --}}
                <div id="loader" class="loader-overlay" style="display: none">
                    <div class="loader-circle"></div>
                </div>
                <!-- pupup error -->
                <div class="modal fade" id="errorpopup" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-danger"><b>LỖI THÔNG TIN THẺ</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <table class="table">
                                            <tr>
                                                <th id="error_details" class="text-center">hahah</th>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">OKE</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- popup success --}}
                <div class="modal fade" id="successpopup" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 style="color: #28a745;"><b>THÀNH CÔNG</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <table class="table">
                                            <tr>
                                                <th id="error_details" class="text-center">Thanh toán thẻ thành công - quay lại ví</th>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" onclick="closeSuccessPopup()">OKE</button>
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
        //handle selection type pay-----------------------------------------------------------------------
        function handleSelection() {
            var selectedValue = document.getElementById("type-pay").value
            if (selectedValue !== "") {
                document.getElementById("blank_selection").style.display = "none";
                if (selectedValue === "CardInputGate") {
                    $('#seri-error').empty();
                    $('#pin-error').empty();
                    $('#messages-fail').css('display', 'none');
                    document.getElementById("droplist_money").style.display = "block";
                    document.getElementById("droplist_money").value = "";
                    document.getElementById("seri").style.pointerEvents = "auto";
                    document.getElementById("seri").style.opacity = "1";
                    document.getElementById("pin").style.pointerEvents = "auto";
                    document.getElementById("pin").style.opacity = "1";
                } else {
                    $('#messages-fail').css('display', 'none');
                    document.getElementById("droplist_money").value = "";
                    document.getElementById("droplist_money").style.display = "block";
                    document.getElementById("seri").style.pointerEvents = 'none';
                    document.getElementById("seri").style.opacity = '0.2';
                    document.getElementById("pin").style.pointerEvents = 'none';
                    document.getElementById("pin").style.opacity = '0.2';
                }
            } else {
                $('#seri-error').empty();
                $('#pin-error').empty();
                $('#messages-fail').css('display', 'none');
                document.getElementById("blank_selection").style.display = "block";
                document.getElementById("droplist_money").style.display = "none";
            }
        }
        function handleSelectMonney() {
            var selectedValue = document.getElementById("droplist_money").value;
            if (selectedValue !== "") {
                $('#messages-fail').css('display', 'none');
            }
        }

        //error popup
        function openErrorBox(error) {
            document.getElementById("error_details").textContent = error;
            $('#errorpopup').modal('show');
        }

        // pop up success
        function closeSuccessPopup() {
            window.location.href = "{{ route('dashboardPay') }}";
        }
        function openSuccessPopup() {
            $('#successpopup').modal('show');
        }

        // Popup confirm form----------------------------------------------------------------------
        function openBox() {
            const type_pay = document.getElementById("type-pay");
            const selectedOptionName = type_pay.options[type_pay.selectedIndex].text;
            document.getElementById("popup_type_pay").innerText = selectedOptionName;
            const seri = document.getElementById("seri");
            document.getElementById("popup_seri").innerText = seri.value;
            const pin = document.getElementById("pin");
            document.getElementById("popup_pin").innerText = pin.value;
            $('#popupconfirm').modal('show');
        }

        function closeBox() {
            $('#popupconfirm').modal('hide');
        }
        //confirm payment
        function confirm() {
            closeBox();
            var formData = $("#form-card").serialize();
            $("#loader").show();
            $.ajax({
                url: "{{ route('recharge.post') }}",
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (res.status == 200) {
                        $("#loader").hide();
                        if (res.type_pay == "CardInputGate") {
                            openSuccessPopup();
                        } else if (res.type_pay == "QRCODE") {
                            $("#loader").hide();
                            var dataRes = res.result;
                            var url = "{{ route('qrcode') }}?dataList=" + encodeURIComponent(JSON.stringify(
                                dataRes));
                            window.location.href = url;
                        }
                    } else {
                        $("#loader").hide();
                        openErrorBox(res.message_code);
                    }
                }
            });
            closeBox();
        }
        //function validate form add to card-----------------------------------------------------------------
        $(function() {
            $("#form-card").submit(function(e) {
                e.preventDefault();
                var formData = $("#form-card").serialize();
                $.ajax({
                    url: "{{ route('recharge.check') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        $('#seri-error').empty();
                        $('#pin-error').empty();
                        $('#messages-fail').css('display', 'none');
                        if (res.status == 200) {
                            if (res.type_pay == "CardInputGate") {
                                openBox();
                            } else {
                                confirm();
                            }
                        } else {
                            if (res.message_validate) {
                                $('#messages-fail').html(res.message_validate);
                                $('#messages-fail').css('display', 'block');
                            } else {
                                $('#messages-fail').css('display', 'none');
                                if (res.message.seri) {
                                    $('#seri-error').html(res.message.seri[0]);
                                } else {
                                    $('#seri-error').empty();
                                }
                                if (res.message.pin) {
                                    $('#pin-error').html(res.message.pin[0]);
                                } else {
                                    $('#pin-error').empty();
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
