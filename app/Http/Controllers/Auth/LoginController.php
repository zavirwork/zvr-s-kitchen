<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function index()
    {
        if ($user = Auth::user()) {
            if ($user->role == 'admin') {
                return view('admin.index');
            } elseif ($user->role == 'user') {
                return view('user.index');
            }
        }
    }

    public function prosesLogin(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'password' => 'required',
        ]);
        $kredensial = $request->only('name', 'password');

        if (Auth::attempt($kredensial)) {
            $user = Auth::user();
            if ($user->role == 'admin') {
                return view('admin.index');
            } elseif ($user->role = 'user') {
                return view('user.index');
            }
        }

        return redirect('login')->withInput()->withErrors(['failde_login' => 'these credentials do not match.']);
    }
}
