<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, DispatchesJobs, SoftDeletes;
    public function getUser()

    {
        $user = User::all();
        return response()->json($user);
    }
    public function getUserbyID($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return $user;
    }

    public function verifyLogin(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(
                ['message' => 'Invalid Credentials', 'status' => 0],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24); // 1 day
        return response([
            'email' => $request->email,
            'userId' => $user->id,
            'jwt_token' => $token,
            'name' => $user->name,
            'message' => 'Login Success',
            'status' => 1
        ])->withCookie($cookie);
    }

    public function updateName(Request $request)
    {
        $request->validate(
            [
                'id' => 'required',
                'name' => 'required',
            ]
        );

        $user = User::find($request->id);

        if ($user) {
            $user->name = $request->name;
            $user->save();
            return $user;
        }
    }

    public function updateEmail(Request $request)
    {
        $request->validate(
            [
                'id' => 'required',
                'email' => 'required|email',
            ]
        );

        $user = User::find($request->id);

        if ($user) {
            $user->email = $request->email;
            $user->save();
            return $user;
        }
    }

    public function deleteAccount($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $user->delete();
            return $user;
        } else {
            return response()->json(array('error' => Response::HTTP_FORBIDDEN, 'message' => null));
        }
    }

    public function changePassword()
    {
    }

    public function logout(Request $request)
    {
        $cookie = \Cookie::forget('jwt');
        return response(['message' => ' logout success'])->withCookie($cookie);
    }
}
