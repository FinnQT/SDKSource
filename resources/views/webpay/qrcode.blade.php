@extends('clients/layout')
@section('title')
    QR code
@endsection
@section('link')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center ">
            <div class="col-md-5 p-3 shadow-sm" style="margin-top: 20px;">
                <div class="text-center">
                    <h3>NẠP THẺ QR</h2>
                        <div id="countdown" style="color:red;"></div>
                        <div style="color:red;">Thoát giao dịch - Hủy giao dịch</div>
                </div>
                <img src="{{ $dataList !== null ? $dataList->redirectLink : '' }}" alt="" class="img-fluid">
                <table class="table ">
                    <tr>
                        <th>NGƯỜI NHẬN</th>
                        <td>{{ $dataList !== null ? $dataList->bankname : '' }}</td>
                    </tr>
                    <tr>
                        <th>NỘI DUNG CHUYỂN TIỀN</th>
                        <td>{{ $dataList !== null ? $dataList->content : '' }}</td>
                    </tr>
                    <tr>
                        <th>SỐ TIỀN</th>
                        <td id="amount"></td>
                    </tr>
                    <tr class="text-center">
                        <th style="color:red;" colspan="2">VUI LÒNG KHÔNG THAY ĐỔI MỆNH GIÁ VÀ NỘI DUNG CHUYỂN TIỀN</th>
                    </tr>
                </table>
                <div> </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        const url = '{{ $dataList !== null ? $dataList->redirectLink : '' }}';
        const urlParams = new URLSearchParams(new URL(url).search);
        const amount = urlParams.get('amount');
        var amount_display = document.getElementById("amount");
        amount_display.innerHTML = amount;
        // xử lý thời gian
        // Thời gian ban đầu là 10 phút (600 giây)7
        var countdownTime = 600;
        // Lấy phần tử HTML để hiển thị thời gian
        var countdownElement = document.getElementById("countdown");
        // Cập nhật bộ đếm ngược mỗi giây
        var isCountingDown = true;
        // Xử lý thao tác thoát giao dịch người dùng
        var countdownInterval = setInterval(function() {
            // Giảm thời gian còn lại mỗi giây
            countdownTime--;
            // Tính toán số phút và giây còn lại
            var minutes = Math.floor(countdownTime / 60);
            var seconds = countdownTime % 60;
            // Format thời gian thành chuỗi hiển thị
            var countdownString = minutes.toString().padStart(2, '0') + ":" + seconds.toString().padStart(2, '0');

            // Hiển thị thời gian còn lại trên HTML
            countdownElement.innerHTML = "Thời gian còn lại: " + countdownString;
            // Kiểm tra nếu thời gian còn lại là 0
            if (countdownTime <= 0) {
                // Dừng bộ đếm ngược
                clearInterval(countdownInterval);
                clearInterval(checksuccessbanking);
                // Hiển thị thông báo khi hết thời gian
                countdownElement.innerHTML = "Hết thời gian!";
                // lưu giao dịch thấy bại khi hết thời gian
                var transaction_id = "{{ $dataList->request_id }}"
                url_check = "{{ route('timeouts', ['id' => ':id']) }}";
                url_check = url_check.replace(':id', transaction_id);
                $.ajax({
                    url: url_check,
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            alert(res.message);
                        }
                    }
                });
                isCountingDown = false;
                window.location.href = "{{ route('loginPay') }}";
            }
        }, 1000); // Cập nhật mỗi giây

        var checksuccessbanking = setInterval(function() {
            var transaction_id = "{{ $dataList->request_id }}"
            url_check = "{{ route('transactionSuccess', ['id' => ':id']) }}";
            url_check = url_check.replace(':id', transaction_id);
            $.ajax({
                url: url_check,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status == 200) {
                        clearInterval(countdownInterval);
                        clearInterval(checksuccessbanking);
                        alert(res.message);
                        window.location.href = "{{ route('loginPay') }}";
                    } else if (res.status == 202) {} else {
                        clearInterval(countdownInterval);
                        clearInterval(checksuccessbanking);
                        alert(res.message);
                        window.location.href = "{{ route('loginPay') }}";
                    }
                }
            });
        }, 3000);


        window.addEventListener('unload', function() {
            if (isCountingDown) {
                clearInterval(countdownInterval);
                clearInterval(checksuccessbanking);
                var transaction_id = "{{ $dataList->request_id }}"
                url_check = "{{ route('timeouts', ['id' => ':id']) }}";
                url_check = url_check.replace(':id', transaction_id);
                $.ajax({
                    url: url_check,
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {}
                    }
                });
            }
        });
    </script>
@endsection
