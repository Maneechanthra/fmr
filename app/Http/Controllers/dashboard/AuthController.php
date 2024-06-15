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

class AuthController extends Controller
{
    //
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs, SoftDeletes;
    // public function verified_login_for_admin(Request $request)
    // {
    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response(
    //             ['message' => 'Invalid Credentials', 'status' => 0],
    //             Response::HTTP_UNAUTHORIZED
    //         );
    //     }

    //     $user = Auth::user();
    //     if ($user->role !== 1) {
    //         Auth::logout();
    //         return response(
    //             ['message' => 'Unauthorized', 'status' => 0],
    //             Response::HTTP_FORBIDDEN
    //         );
    //     }

    //     $token = $user->createToken('token')->plainTextToken;
    //     $cookie = cookie('jwt', $token, 60 * 24);

    //     $request->session()->put('user_data', [
    //         'email' => $request->email,
    //         'userId' => $user->id,
    //         'jwt_token' => $token,
    //         'name' => $user->name,
    //         'message' => 'Login Success',
    //         'status' => 1
    //     ]);

    //     return redirect('/')->withCookie($cookie);
    // }

    // public function login()
    // {
    //     return view('login.login');
    // }

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

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:12'
        ]);

        $user = User::where('email', '=', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('loginId', $user);
                return redirect()->route('/');
            } else {
                return back()->with('fail', 'Password not match!');
            }
        } else {
            return back()->with('fail', 'This email is not registered.');
        }
    }


    public function index()
    {
        $data = [];
        if (Session::has('loginId')) {
            $data = User::where('id', '=', Session::get('loginId'))->first();
        }
        return view('index', compact('data'));
    }

    public function logout()
    {
        if (Session::has('loginId')) {
            Session::pull('loginId');
            return redirect('login');
        }
    }
}
