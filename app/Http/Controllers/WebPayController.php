<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\RequestData;
use App\Models\RequestBanking;
use Illuminate\Support\Facades\Validator;
use App\Models\XMLSerializer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use nusoap_client;


class WebPayController extends Controller
{
    public function login()
    {
        return view('webpay/loginpay');
    }
    function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);
        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_admin == 1) {
                    $request->session()->put('admin', $user->username);
                    Session::pull('loginUsernamePay');
                    return redirect()->route('admin');
                } else {
                    if ($user->status == 0) {
                        return back()->with('fail', 'Tài khoản đã bị cấm');
                    } else {
                        $request->session()->put('loginUsernamePay', $user->username);
                        Session::pull('admin');
                        return redirect()->route('dashboardPay');
                    }
                }
            } else {
                return back()->with('fail', 'Mật khẩu không chính xác');
            }
        } else {
            return back()->with('fail', 'Tài khoản không tồn tại!');
        }
    }

    public function dashboard()
    {
        $data = array();
        if (Session::has('loginUsernamePay')) {
            $data = User::where('username', Session::get('loginUsernamePay'))->first();
        }
        return view('webpay/dashboard', compact('data'));
    }
    public function history()
    {
        $data = array();
        if (Session::has('loginUsernamePay')) {
            $data = User::where('username', Session::get('loginUsernamePay'))->first();
        }
        return view('webpay/history', compact('data'));
    }
    public function transactionWallet()
    {
        if (Session::has('loginUsernamePay')) {
            $transaction = DB::table('transactions')->where('username', Session::get('loginUsernamePay'))->orderBy('time', 'desc')->limit(30)->get();
        }
        return view('webpay/transactionWallet', compact('transaction'));
    }
    public function transactionGame()
    {
        return view('webpay/transactionGame');
    }
    public function account()
    {
        $data = array();
        if (Session::has('loginUsernamePay')) {
            $data = User::where('username', Session::get('loginUsernamePay'))->first();
        }
        return view('webpay/account', compact('data'));
    }
    public function exchange_rate()
    {
        $data = array();
        $data = DB::table('exchange_rate')->get()->first();
        // dd( $data );
        return view('webpay/exchangerate', compact('data'));

    }
    public function updateInfo(Request $request)
    {
        if (Session::has('loginUsernamePay')) {
            $time =Carbon::now();
            $logold = User::where('username',  Session::get('loginUsernamePay'))->value('log_change_inf');
            $logchange=$logold."Đã cập nhật mới thông tin: ".$time."-".$request->detail_name."-".$request->detail_location."-".$request->detail_CCCD."|";
            $affected = User::where('username', Session::get('loginUsernamePay'))
                ->update([
                    'name' => $request->detail_name,
                    'location' => $request->detail_location,
                    'CCCD' => $request->detail_CCCD,
                    'log_change_inf'=>$logchange
                ]);
            return response()->json([
                'status' => 200,
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Cập nhật thất bại'
            ]);
        }
    }
    public function logout()
    {
        if (Session::has('loginUsernamePay')) {
            Session::pull('loginUsernamePay');

            return redirect()->route('loginPay');
        } else {
            return redirect()->route('loginPay');
        }
    }
    public function recharge()
    {
        $data = array();
        if (Session::has('loginUsernamePay')) {
            $data = User::where('username', Session::get('loginUsernamePay'))->first();
        }
        return view('webpay/recharge', compact('data'));
    }
    //connect server with cardinput
    public function CardInput($typePay, $money, $seri, $pin)
    {
        $client = new nusoap_client('https://sandbox-ops.gate.vn:7001/Igate_WS/Route?wsdl', 'wsdl');
        $client->decode_utf8 = false;
        $err = $client->getError();
        if ($err) {
            return "fasle";
        }
        // Doc/lit parameters get wrapped
        $ServiceName = $typePay;
        $rq = new RequestData();
        $rq->MerchantID = 3485;
        $rq->Username = "chuquyetthang";
        $rq->CardSerial = $seri;
        $rq->CardPIN = $pin;
        $rq->FunctionName = "CardInput";
        $rq->TelcoServiceCode = "10034850002";
        $rq->PartnerTransactionID = $this->randomtranId();

        //$private_key = "1253.key";
        //$private_key_pass = "ce709fb4";
        //$secretkey = "17711e2261c0dd70ea2f4954c39d1dbe";  

        $private_key = base_path('app/Http/Controllers/3485.key');
        $private_key_pass = "f77ee102";
        $secretkey = "1348a8cd0bfb561841d9ec3654595d21";
        //var_dump($rq);
        $OriginalData = sprintf("%d%s%s%s%s%s", $rq->MerchantID, $rq->Username, $rq->CardSerial, $rq->CardPIN, $rq->TelcoServiceCode, $secretkey);

        if (openssl_sign($OriginalData, $Signature, array(file_get_contents($private_key), $private_key_pass))) {
            $Signature = base64_encode($Signature);
        }

        $rq->Signature = $Signature;

        $xmlSerializer = new XMLSerializer();
        $request = $xmlSerializer->generateValidXmlFromObj($rq, "RequestData");

        $param = array(
            'arg0' => $ServiceName,
            'arg1' => $request
        );

        $result = $client->call('ProcessRequest', $param, '', '', false, true);
        if ($client->fault) {
            return "false";
        } else {
            // Check for errors
            $err = $client->getError();
            if ($err) {
                return "false";
            } else {
                $values = explode('|', $result['return']);
                $data = [
                    'ErrorCode' => isset($values[0]) ? $values[0] : null,
                    'Description' => isset($values[1]) ? $values[1] : null,
                    'TransactionID' => isset($values[2]) ? $values[2] : null,
                    'PartnerTransactionID' => $rq->PartnerTransactionID,
                    'CardAmount' => isset($values[4]) ? $values[4] : null,
                    'VendorTransactionID' => isset($values[5]) ? $values[5] : null,
                ];
                return $data;
            }
        }
    }
    // test momo bank
    public function test()
    {
        return view('webpay/test');

    }
    public function randomtranId()
    {
        $exists = true;
        $uniqueId = "";
        while ($exists) {
            $uniqueId = "GGO" . rand(1, 100000000000); // Sinh một ID ngẫu nhiên trong khoảng từ 1 đến 100000000000
            $exists = DB::table('transactions')->where('transactionID', $uniqueId)->exists(); // Kiểm tra xem ID đã tồn tại chưa
        }
        return $uniqueId;
    }

    // public function momo_bank($amountInput, $typeMethod, $username)
    // {
    //     $rq = new RequestMomo();
    //     $rq->merchantId = 3485;
    //     $rq->transId = $this->randomtranId();
    //     $rq->storeId = 3485;
    //     $rq->amount = $amountInput;
    //     $rq->payMethod = $typeMethod;
    //     $rq->desc = "Thanh toán tiền game " . $typeMethod . " KH - " . $username;
    //     $rq->title = "Thanh toán tiền game " . $typeMethod . " KH - " . $username;
    //     $rq->ipnUrl = "http://127.0.0.1:8000/api/recharge/success";
    //     $rq->redirectUrl = "https://www.facebook.com/";
    //     $rq->failedUrl = "https://www.google.com/";
    //     // key
    //     $private_key = base_path('app/Http/Controllers/3485.key');
    //     $private_key_pass = "f77ee102";
    //     $secretkey = "1348a8cd0bfb561841d9ec3654595d21";
    //     //signature
    //     $OriginalData = sprintf("%d%s%d%d%s%s%s%s%s%s%s", $rq->merchantId, $rq->transId, $rq->storeId, $rq->amount, $rq->payMethod, $rq->desc, $rq->title, $rq->ipnUrl, $rq->redirectUrl, $rq->failedUrl, $secretkey);
    //     if (openssl_sign($OriginalData, $Signature, array(file_get_contents($private_key), $private_key_pass))) {
    //         $Signature = base64_encode($Signature);
    //     }
    //     $rq->signature = $Signature;
    //     $array = (array) $rq;
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json'
    //     ])->post('https://dev-api.gate.vn:17443/billing/payment/web/', $array);
    //     if ($response->successful()) {
    //         $dataResponse = $response->json();
    //         return $dataResponse;
    //     } else {
    //         return "false";
    //     }
    // }



    // validate form
    public function rechargecheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seri' => 'required',
            'pin' => 'required'

        ], [
            'seri.required' => 'Vui lòng nhập mã pin',
            'pin.required' => 'Vui lòng nhập seri'
        ]);

        switch ($request->type_pay) {
            case '':
                return response()->json([
                    'status' => 400,
                    'message_validate' => "Vui lòng chọn loại thẻ"
                ]);
            case 'CardInputGate':
                if (!$request->monney_pick) {
                    return response()->json([
                        "type_pay" => "CardInputGate",
                        'status' => 400,
                        'message_validate' => "Vui lòng chọn mệnh giá"
                    ]);
                } else {
                    if ($validator->fails()) {
                        return response()->json([
                            "type_pay" => "CardInputGate",
                            'status' => 400,
                            'message' => $validator->getMessageBag()
                        ]);
                    } else {
                        return response()->json([
                            "type_pay" => "CardInputGate",
                            'status' => 200,
                            'message' => 'success'
                        ]);
                    }
                }
            default:
                if (!$request->monney_pick) {
                    return response()->json([
                        "type_pay" => "CardInputGate",
                        'status' => 400,
                        'message_validate' => "Vui lòng chọn mệnh giá"
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'success'
                    ]);
                }
        }
    }
    // get response from server
    public function rechargePost(Request $request)
    {
        switch ($request->type_pay) {
            case 'CardInputGate':
                $result = $this->CardInput($request->type_pay, $request->monney_pick, $request->seri, $request->pin);
                // dd($result);
                if ($result == "false") {
                    $transactions = DB::table('transactions')->insert([
                        'username' => $request->usernameRq,
                        'transactionID' => $result['PartnerTransactionID'],
                        'type_pay' => $request->type_pay,
                        'serial' => $request->seri,
                        'amount' => $request->monney_pick,
                        'status' => -1,
                        'desc' => "Lỗi hệ thống - Giao dịch thất bại"
                    ]);
                    return response()->json([
                        "type_pay" => "CardInputGate",
                        "status" => 400,
                        'message_code' => "Có lỗi gì đó trong việc kết nối đến hệ thống server"
                    ]);
                } else if ($result['ErrorCode'] != "00") {
                    $transactions = DB::table('transactions')->insert([
                        'username' => $request->usernameRq,
                        'transactionID' => $result['PartnerTransactionID'],
                        'type_pay' => $request->type_pay,
                        'serial' => $request->seri,
                        'amount' => $request->monney_pick,
                        'status' => -1,
                        'desc' => $result['Description']
                    ]);
                    return response()->json([
                        "type_pay" => "CardInputGate",
                        "status" => 400,
                        "message_code" => $result['Description']
                    ]);
                } else {
                    $rate=DB::table('exchange_rate')->value('rate');
                    $amount = $result['CardAmount'] *  (1-$rate);
                    $user = User::where('username', $request->usernameRq)->first();
                    $monney = $user->balance + $amount;
                    $affected = User::where('username', $request->usernameRq)
                        ->update(['balance' => $monney]);

                    $transactions = DB::table('transactions')->insert([
                        'username' => $request->usernameRq,
                        'transactionID' => $result['PartnerTransactionID'],
                        'type_pay' => $request->type_pay,
                        'serial' => $request->seri,
                        'amount' => $result['CardAmount'],
                        'status' => 1,
                        'desc' => "Giao dịch thẻ thành công"
                    ]);
                    return response()->json([
                        "type_pay" => "CardInputGate",
                        'status' => 200,
                        'result' => $result
                    ]);
                }
            case 'QRCode':
                //ATM and momo - GATE
                // $result = $this->momo_bank($request->monney_pick, "ATMCARD", $request->usernameRq);
                // // dd($result);
                // if ($result == "false") {
                //     return response()->json([
                //         "type_pay" => "ATMCARD",
                //         "status" => 400,
                //         'message_code' => "Có lỗi gì đó trong việc kết nối đến hệ thống server"
                //     ]);
                // } else if ($result['Code'] != 0) {
                //     return response()->json([
                //         "type_pay" => "ATMCARD",
                //         "status" => 400,
                //         "message_code" => $result['Message']
                //     ]);
                // } else {
                //     return response()->json([
                //         "type_pay" => "ATMCARD",
                //         'status' => 200,
                //         'result' => $result
                //     ]);
                // }

                // ATM new
                $result = $this->banking($request->monney_pick);
                // dd($result);
                if ($result == "false") {
                    $transactions = DB::table('transactions')->insert([
                        'username' => $request->usernameRq,
                        'transactionID' => $result['request_id'],
                        'type_pay' => $request->type_pay,
                        'serial' => "",
                        'amount' => $request->monney_pick,
                        'status' => -1,
                        'desc' => "Lỗi hệ thống - Giao dịch QR thất bại"
                    ]);
                    return response()->json([
                        "type_pay" => "QRCODE",
                        "status" => 400,
                        'message_code' => "Có lỗi gì đó trong việc kết nối đến hệ thống server"
                    ]);

                } else if ($result['errorCode'] != 1) {
                    $transactions = DB::table('transactions')->insert([
                        'username' => $request->usernameRq,
                        'transactionID' => $result['request_id'],
                        'type_pay' => $request->type_pay,
                        'serial' => "",
                        'amount' => $request->monney_pick,
                        'status' => -1,
                        'desc' => "Giao QR dịch thất bại"
                    ]);
                    return response()->json([
                        "type_pay" => "QRCODE",
                        "status" => 400,
                        "message_code" => $result['message']
                    ]);

                } else {
                    $amount = $request->monney_pick * 80 / 100;
                    $user = User::where('username', $request->usernameRq)->first();
                    $transactions = DB::table('transactions')->insert([
                        'username' => $request->usernameRq,
                        'transactionID' => $result['request_id'],
                        'type_pay' => $request->type_pay,
                        'serial' => "",
                        'amount' => $request->monney_pick,
                        'status' => 0,
                        'desc' => "Đang xử lý thanh toán"
                    ]);
                    return response()->json([
                        "type_pay" => "QRCODE",
                        'status' => 200,
                        'result' => $result
                    ]);
                }
            default:
        }
    }
    public function banking($amountInput)
    {
        $rq = new RequestBanking();
        $rq->username = "VIEIAG1";
        $rq->password = "9fbonw67kd8qsavy2tge04z1jihp5xur";
        $rq->request_id = $this->randomtranId();
        $rq->bank_code = "ACB";
        $rq->money = $amountInput;
        $rq->url_callback = "http://ggosdk.mobi/api/recharge/success";
        $array = (array) $rq;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://tg.the247.top/api3/requestPayment', $array);
        if ($response->successful()) {
            $dataResponse = $response->json();
            $dataResponse['request_id'] = $rq->request_id;
            return $dataResponse;
        } else {
            return "false";
        }
    }
    public function rechargeSuccess(Request $request)
    {
        return response()->json([
            'method' => $request->method(),
            'url' => $request->url(),
            'data' => $request->all() // Trả về tất cả dữ liệu từ request
        ]);

    }
    public function CallBackRechargeSuccess(Request $request)
    {
        // $data = json_decode(json_encode($request->all()));
        $data = $request->all();
        if ($data['status'] == 1) {
            $transaction = DB::table('transactions')->where('transactionID', $data['request_id'])->first();
            if ($transaction) {
                $data['username'] = $transaction->username;
                Log::info('Received POST Request:', $data);  // Log dữ liệu vào Laravel log file
                $rate=DB::table('exchange_rate')->value('rate');
                $amount = $data['amount']  *  (1-$rate);
                $user = User::where('username', $transaction->username)->first();
                $monney = $user->balance + $amount;
                $affected = User::where('username', $transaction->username)
                    ->update(['balance' => $monney]);
                $affected2 = DB::table('transactions')
                    ->where('transactionID', $data['request_id'])
                    ->update([
                        'status' => 1,
                        'desc' => "Giao dịch QR thành công",
                        'amount' => $data['amount']
                    ]);
            }
        } else {
            $affected2 = DB::table('transactions')
                ->where('transactionID', $data['request_id'])
                ->update([
                    'status' => -1,
                    'desc' => "Giao dịch QR thất bại"
                ]);
        }
    }

    public function qrCode(Request $request)
    {
        $dataList = json_decode($request->input('dataList'));
        return view('webpay/qrcode', compact('dataList'));
    }
    public function transactionSuccess($transaction_id)
    {
        $transaction = DB::table('transactions')->where('transactionID', $transaction_id)->first();
        if ($transaction) {
            if ($transaction->status == 1) {
                return response()->json([
                    'status' => 200,
                    'message' => "Thanh toán QR hoàn tất"
                ]);
            } else if ($transaction->status == 0) {
                return response()->json([
                    'status' => 202,
                    'message' => "Đang xử lý giao dịch"
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "Giao QR dịch thất bại"
                ]);
            }
        }
    }
    public function timeouts($transaction_id)
    {
        $affected = DB::table('transactions')
            ->where('transactionID', $transaction_id)
            ->update([
                'status' => -1,
                'desc' => "Giao QR dịch thất bại"
            ]);
        if ($affected) {
            return response()->json([
                'status' => 200,
                'message' => "Hết thời gian - Giao dịch QR thất bại"
            ]);
        }
    }


    public function forgotprotectcode_WP()
    {
        return view('webpay/forgot_protect_code_wp');
    }
    public function forgotprotectcodePost_WP(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'email không hợp lệ',
            'email.exists' => 'Email không tồn tại',
        ]);
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        Mail::send("webpay.forgot_protect_code_email_wp", ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Protect Code");
        });
        return redirect()->to(route('forgotprotectcode_WP'))->with("success", "Chúng tôi đã gửi link reset đến email của bạn");
    }

    public function resetprotectcode_WP($token)
    {
        return view('webpay/newprotectcode_wp', compact('token'));
    }
    public function resetprotectcodePost_WP(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
            'protect_code' => 'required|min:6|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/',
            'cprotect_code' => 'required|same:protect_code'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'email không hợp lệ',
            'email.exists' => 'Email không tồn tại',
            'protect_code.required' => 'Vui lòng nhập mã bảo vệ',
            'protect_code.min' => 'Mã bảo vệ chứa tối thiểu 6 kí tự',
            'protect_code.max' => 'Mã bảo vệ chứa tối đa 12 kí tự',
            'protect_code.regex' => 'Mã bảo vệ phải chứa chữ cái in hoa, không chứa ký tự đặc biệt',
            'cprotect_code.required' => 'Vui lòng nhập lại mã bảo vệ',
            'cprotect_code.same' => 'Mã bảo vệ không trùng khớp',
        ]);
        $updateProtectCode = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])->first();

        if (!$updateProtectCode) {
            return redirect()->to(route('forgotprotectcodeWP'))->with('error', "Xác thực không chính xác");
        }
        $logold = User::where('email', $request->email)->value('log_protect_code');
        $time =Carbon::now();
        $logchange=$logold."Đã cập nhật mới protectcode: ".$time."-".$request->protect_code."|";
        $user = User::where('email', $request->email)->update([
            'protect_code' => Hash::make($request->protect_code),
            'log_protect_code'=>$logchange
        ]);
        DB::table('password_resets')->where(["email" => $request->email])->delete();
        return redirect()->to(route('account'))->with("success", "Thay đổi mã bảo vệ thành công");
    }
}
