<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public $headerTitle = 'Auth';
    //

    public function login()
    {
        $data['header_title'] = 'Login';
        return view('auth.login', $data);
    }
    public function postLogin(Request $request)
    {
        try {
            $remember = !empty($request->remember) ? true : false;
            $credentials = [];

            if (!empty($request->email)) {
                $credentials['email'] = $request->email;
            } elseif (!empty($request->mobile_number)) {
                $credentials['mobile_number'] = $request->mobile_number;
            }

            $credentials['password'] = $request->password;

            if (Auth::attempt($credentials, $remember)) {
                $authUser = Auth::user();
                if (!empty($request->trip_login) && $request->trip_login == 'trip_login') {
                    return response()->json([
                        'status' => true,
                        'msg' => 'trip_login_successfull'
                    ]);
                }

                if ($authUser->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($authUser->hasRole('user')) {
                    return redirect()->route('user.dashboard');
                } elseif ($authUser->hasRole('customer')) {
                    return redirect()->route('customer.trips.list');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid credentials');
            }
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }
    public function register()
    {
        $data['header_title'] = 'Register';
        return view('auth.register', $data);
    }
    public function postRegister(Request $request)
    {
        DB::beginTransaction();
        try {
            request()->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->user_type = 3;
            $user->password = Hash::make($request->password);
            $user->mobile_number = !empty($request->mobile_number) ? $request->mobile_number : null;
            $user->save();
            $user->assignRole(3);
            DB::commit();
            if (!empty($request->trip_signup) && $request->trip_signup == 'trip_signup') {
                return response()->json([
                    'status' => true,
                    'resp' => 'login_now',
                    'msg' => 'Account created successfully'
                ]);
            } else {
                return redirect('/login')->with('success', 'Registered Successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
            // return $e->getMessage();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
