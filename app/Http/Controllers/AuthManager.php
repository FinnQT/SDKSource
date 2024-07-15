<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class AuthManager extends Controller
{
    public function login()
    {

        return view('clients/login');
    }
    public function register()
    {
        return view('clients/register');
    }

    public function loginPost(Request $request)
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
                if ($user->status == 0) {
                    return back()->with('fail', 'Tài khoản đã bị cấm');
                } else {
                    $request->session()->put('loginUsername', $user->username);
                    $ipAddress = $request->ip();
                    $user = User::where('username', $user->username)->update([
                        'last_login_ip' => $ipAddress
                    ]);
                }
                return redirect()->route('dashboard');
            } else {
                return back()->with('fail', 'Mật khẩu không chính xác');
            }
        } else {
            return back()->with('fail', 'Tài khoản không tồn tại!');
        }
    }
    public function registerPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|min:6|max:12|regex:/^[a-zA-Z0-9\s]+$/',
            'password' => 'required|min:6|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/',
            'cpassword' => 'required|same:password',
            'email' => 'required|email|unique:users',
            'protect_code' => 'required|min:6|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/'
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.min' => 'Tài khoản chứa tối thiểu 6 kí tự',
            'username.max' => 'Tài khoản chứa tối đa 12 kí tự',
            'username.regex' => 'Tài khoản không được chứa ký tự đặc biệt',
            'username.unique' => 'Tên tài khoản tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu chứa tối thiểu 6 kí tự',
            'password.max' => 'Mật khẩu chứa tối đa 12 kí tự',
            'password.regex' => 'Mật khẩu phải chứa chữ cái in hoa, không chứa ký tự đặc biệt',
            'cpassword.required' => 'Vui lòng nhập lại mật khẩu',
            'cpassword.same' => 'Mật khẩu không trùng khớp',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'protect_code.required' => 'Vui lòng nhập mã bảo vệ',
            'protect_code.min' => 'Mã bảo vệ chứa tối thiểu 6 kí tự',
            'protect_code.max' => 'Mã bảo vệ chứa tối đa 12 kí tự',
            'protect_code.regex' => 'Mã bảo vệ chứa chữ cái in hoa, không chứa ký tự đặc biệt',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 400,
                'message' => $validator->getMessageBag()
            ]);
        } else {
            $username = $request->username;
            $email = $request->email;
            $password = Hash::make($request->password);
            $protect_code = Hash::make($request->protect_code);
            $uuid = (string) Uuid::uuid4();
            $ipAddress = $request->ip();
            $user = User::create([
                'uuid' => $uuid,
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'protect_code' => $protect_code,
                'status' => 1,
                'link' => 1,
                'ip_address' => $ipAddress,
                'last_login_ip' => $ipAddress
            ]);
            if ($user) {
                // return back()->with('success','Bạn đăng ký thành công');
                return response()->json([
                    'status' => 200,
                    'messages' => 'Đăng ký thành công'
                ]);
            } else {
                // return back()->with('fail','Có lỗi đã xảy ra');
                return response()->json([
                    'status' => 400,
                    'messages' => 'Đăng ký lỗi'
                ]);
            }
        }
    }
    public function randomtranUsername()
    {
        $exists = true;
        $usernameTest = "";
        while ($exists) {
            $usernameTest = "Test" . rand(1, 100000000000); // Sinh một ID ngẫu nhiên trong khoảng từ 1 đến 100000000000
            $exists = User::where('username', $usernameTest)->exists(); // Kiểm tra xem ID đã tồn tại chưa
        }
        return $usernameTest;
    }
    public function registerTestAcc()
    {
        $username = $this->randomtranUsername();
        $uuid = (string) Uuid::uuid4();
        $user = User::create([
            'uuid' => $uuid,
            'username' => $username,
            'email' => $username . "@gmail.com",
            'password' => "",
            'protect_code' => "",
            'status' => 1,
            'link' => 0,
            'ip_address' => '',
            'last_login_ip' => ''
        ]);
        if ($user) {
            Session::put('loginUsername', $username);
            return redirect()->route('dashboard');
        } else {
            // return back()->with('fail','Có lỗi đã xảy ra');
            return response()->json([
                'status' => 400,
                'messages' => 'Đăng ký lỗi'
            ]);
        }
    }
    public function linkAccount($uuid)
    {
        return view('clients/link_account', compact('uuid'));
    }
    public function linkAccountPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|min:6|max:12|regex:/^[a-zA-Z0-9\s]+$/',
            'password' => 'required|min:6|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/',
            'cpassword' => 'required|same:password',
            'email' => 'required|email|unique:users',
            'protect_code' => 'required|min:6|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/'
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.min' => 'Tài khoản chứa tối thiểu 6 kí tự',
            'username.max' => 'Tài khoản chứa tối đa 12 kí tự',
            'username.regex' => 'Tài khoản không được chứa ký tự đặc biệt',
            'username.unique' => 'Tên tài khoản tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu chứa tối thiểu 6 kí tự',
            'password.max' => 'Mật khẩu chứa tối đa 12 kí tự',
            'password.regex' => 'Mật khẩu phải chứa chữ cái in hoa, không chứa ký tự đặc biệt',
            'cpassword.required' => 'Vui lòng nhập lại mật khẩu',
            'cpassword.same' => 'Mật khẩu không trùng khớp',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'protect_code.required' => 'Vui lòng nhập mã bảo vệ',
            'protect_code.min' => 'Mã bảo vệ chứa tối thiểu 6 kí tự',
            'protect_code.max' => 'Mã bảo vệ chứa tối đa 12 kí tự',
            'protect_code.regex' => 'Mã bảo vệ chứa chữ cái in hoa, không chứa ký tự đặc biệt',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->getMessageBag()
            ]);
        } else {
            $username = $request->username;
            $email = $request->email;
            $password = Hash::make($request->password);
            $protect_code = Hash::make($request->protect_code);
            $uuid = $request->hidden_uuid;
            $ipAddress = $request->ip();
            $user = User::where('uuid', $uuid)->update([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'protect_code' => $protect_code,
                'status' => 1,
                'link' => 1,
                'ip_address' => $ipAddress,
                'last_login_ip' => $ipAddress
            ]);
            if ($user) {
                // return back()->with('success','Bạn đăng ký thành công');
                Session::forget('loginUsername');
                return response()->json([
                    'status' => 200,
                    'messages' => 'Liên kết thành công'
                ]);
            } else {
                // return back()->with('fail','Có lỗi đã xảy ra');
                return response()->json([
                    'status' => 400,
                    'messages' => 'Liên kết lỗi'
                ]);
            }
        }
    }

    //go to dashboard
    public function dashboard()
    {
        $data = array();
        if (Session::has('loginUsername')) {
            $data = User::where('username', Session::get('loginUsername'))->first();
        }
        return view('clients/dashboard', compact('data'));
    }
    public function logout()
    {
        if (Session::has('loginUsername')) {
            $data = User::where('username', Session::get('loginUsername'))->first();
            Session::pull('loginUsername');
            if ($data->link == 0) {
                User::where('username', $data->username)->delete();
            }
            return redirect()->route('login');
        }
        return redirect()->route('login');
    }

    public function forgot()
    {
        return view('clients/forgot');
    }


    public function forgotPostTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'protect_code' => 'required'
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'protect_code.required' => 'Vui lòng nhập mã bảo vệ',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->getMessageBag()
            ]);
        } else {

            $user = User::where('username', $request->username)->first();
            if ($user) {

                if (Hash::check($request->protect_code, $user->protect_code)) {
                    $username = $user->username;
                    return response()->json([
                        'username' => $username,
                        'status' => 200,
                        'messages' => 'Xác nhận thành công',
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'messages' => 'Sai mã bảo vệ',
                        'message' => $validator->getMessageBag()
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'messages' => 'Không tồn tại user',
                    'message' => $validator->getMessageBag()

                ]);
            }
        }
    }
    public function forgotPostNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/',
            'cpassword' => 'required|same:password',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu chứa tối thiểu 6 kí tự',
            'password.max' => 'Mật khẩu chứa tối đa 12 kí tự',
            'password.regex' => 'Mật khẩu phải chứa chữ cái in hoa, không chứa ký tự đặc biệt',
            'cpassword.required' => 'Vui lòng nhập lại mật khẩu',
            'cpassword.same' => 'Mật khẩu không trùng khớp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->getMessageBag()
            ]);
        } else {
            $password = Hash::make($request->password);
            $affected = User::where('username', $request->hidden_value)
                ->update(['password' => $password]);
            if ($affected) {
                return response()->json([
                    'status' => 200,
                    'messages' => 'Đăng ký thành công'
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'messages' => 'Đăng ký lỗi'
                ]);
            }
        }
    }

    public function forgotprotectcode()
    {
        return view('clients/forgot_protect_code');
    }
    public function forgotprotectcodePost(Request $request)
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
        Mail::send("clients.forgot_protect_code_email", ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Protect Code");
        });
        return redirect()->to(route('forgotprotectcode'))->with("success", "Chúng tôi đã gửi link reset đến email của bạn");
    }

    public function resetprotectcode($token)
    {
        return view('clients/newprotectcode', compact('token'));
    }
    public function resetprotectcodePost(Request $request)
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
            'protect_code.max' => 'mã bảo vệ chứa tối đa 12 kí tự',
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
            return redirect()->to(route('forgotprotectcode'))->with('error', "Xác thực không chính xác");
        }
        $logold = User::where('email', $request->email)->value('log_protect_code');
        $time =Carbon::now();
        $logchange=$logold."Đã cập nhật mới protectcode: ".$time."-".$request->protect_code."|";
        $user = User::where('email', $request->email)->update([
            'protect_code' => Hash::make($request->protect_code),
            'log_protect_code'=>$logchange
        ]);
        DB::table('password_resets')->where(["email" => $request->email])->delete();
        return redirect()->to(route('login'))->with("success", "Thay đổi mã bảo vệ thành công");
    }
}
