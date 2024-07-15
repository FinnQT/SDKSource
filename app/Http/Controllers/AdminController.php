<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function admin()
    {

        return view('admin/home');
    }
    public function logout()
    {
        if (Session::has('admin')) {
            $data = User::where('username', Session::get('admin'))->first();
            Session::pull('admin');
            return redirect()->route('loginPay');
        }
        return redirect()->route('loginPay');
    }

    public function managerUser()
    {
        $user = DB::table('users')->orderBy('created_at', 'desc')->get();


        return view('admin/manager_user', compact('user'));
    }

    public function updateInfoUser(Request $request)
    {

        // dd( $request);
        $validator = Validator::make($request->all(), [
            'detail_email' => 'required|email'
        ], [
            'detail_email.required' => 'Vui lòng nhập email',
            'detail_email.email' => 'Email không hợp lệ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->getMessageBag()
            ]);
        } else {
            $affected = User::where('username', $request->hidden_username)
                ->update([
                    'email' => $request->detail_email,
                    'name' => $request->detail_name,
                    'location' => $request->detail_location,
                    'CCCD' => $request->detail_CCCD,
                    'status' => $request->status_pick,
                    'is_admin' => $request->role_pick,
                ]);
            if ($affected) {
                return response()->json([
                    'status' => 200,
                    'messages' => 'Cập nhật thành công'
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'messages' => 'Cập nhật lỗi'
                ]);
            }
        }
    }

    public function delete(Request $request)
    {
        $affected = User::where('username', $request->hidden_username_dl)
            ->delete();
        $affected2 =  DB::table('transactions')->where('username', $request->hidden_username_dl)
            ->delete();
        if ($affected) {
            return response()->json([
                'status' => 200,
                'message' => 'Xóa người dùng thành công'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Xóa người dùng lỗi'
            ]);
        }
    }

    public function historyUser()
    {
        if (Session::has('admin')) {
            $transaction = DB::table('transactions')->orderBy('time', 'desc')->get();
        }
        return view('admin/history', compact('transaction'));
    }

    public function exchange_rate_money()
    {
        $data = array();
        $data = DB::table('exchange_rate')->get()->first();
        // dd( $data );
        return view('admin/exchange_rate', compact('data'));
    }

    public function exchange_rate_money_post(Request $request)
    {


        $request->validate([
            'exchange_rate' => 'required|numeric'
        ], [
            'exchange_rate.required' => 'Vui lòng nhập phần trăm',
            'exchange_rate.numeric' => 'Phần trăm phải là số'
        ]);
        $newRate = $request->exchange_rate / 100;
        $affected = DB::table('exchange_rate')->update([
            'rate' => $newRate,
        ]);
        if ($affected) {
            return back()->with('success', 'Cập nhật thành công');
        } else {
            return back()->with('fail', 'Cập nhật không thành công');
        }
    }
    public function findUser(Request $request)
    {
        $username = $request->username_search;
        $user = DB::table('users')->where('username', 'LIKE', "%$username%")->orderBy('created_at', 'desc')->get();
        return view('admin/manager_user', compact('user'));
    }
}
