<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    //
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs, SoftDeletes;

    public function login()
    {
        return view('login.login');
    }



    // public function loginUser(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8|max:12'
    //     ]);

    //     $user = User::where('email', '=', $request->email)->first();
    //     if ($user) {
    //         if (Hash::check($request->password, $user->password)) {
    //             $request->session()->put('loginId', $user->id);
    //             return redirect()->route('/');
    //         } else {
    //             return back()->with('fail', 'Password not match!');
    //         }
    //     } else {
    //         return back()->with('fail', 'This email is not registered.');
    //     }
    // }

    //===========================================================================
    //============================ success function =============================
    // public function loginUser(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8|max:12'
    //     ]);

    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();
    //         $request->session()->put('loginId', $user->id);
    //         $request->session()->put('userData', $user);

    //         return redirect()->route('/');
    //     } else {
    //         return back()->with('fail', 'Invalid credentials.');
    //     }
    // }

    //===========================================================================
    //============================ success function =============================
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:12'
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role == 1) {
                $request->session()->put('loginId', $user->id);
                $request->session()->put('userData', $user);

                return redirect()->route('/');
            } else {
                Auth::logout();
                return back()->with('fail', 'You do not have permission to access.');
            }
        } else {
            return back()->with('fail', 'Invalid credentials.');
        }
    }
    //===========================================================================

    // public function index()
    // {
    //     $data = [];
    //     if (Session::has('loginId')) {
    //         $data = User::where('id', '=', Session::get('loginId'))->first();
    //     }
    //     return view('index', compact('data'));
    // }

    public function logout()
    {
        if (Session::has('loginId')) {
            Session::pull('loginId');
        }

        if (Session::has('userData')) {
            Session::pull('userData');
        }
        return redirect('login');
    }

    //===========================================================================
    //===========================================================================
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to reset password'], 400);
        }
    }
}
