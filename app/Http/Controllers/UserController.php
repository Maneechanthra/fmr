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
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, DispatchesJobs, SoftDeletes;
    public function getUser()

    {
        $user = User::all();
        return response()->json($user);
    }

    //get user by id
    public function getUserbyID($id)
    {
        // $user = User::find($id);

        $user = DB::table('users')
            ->select(
                'users.name',
                'users.id',
                'users.email',
                'users.email_verified_at',
                'users.created_at',
                'users.updated_at',
                DB::raw('IFNULL(COUNT(DISTINCT restaurants.id), 0) as restaurant_count'),
                // DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN restaurant_favorites.deleted_at IS NULL THEN restaurant_favorites.id ELSE NULL END) as favorites_count'),

            )
            ->leftJoin('restaurants', 'users.id', '=', 'restaurants.created_by')
            ->leftJoin('restaurant_favorites', 'users.id', '=', 'restaurant_favorites.favorite_by')
            ->where('users.id', $id)->whereNull('restaurants.deleted_at')
            ->groupBy(
                'users.name',
                'users.id',
                'users.email',
                'users.email_verified_at',
                'users.created_at',
                'users.updated_at',

            )
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user);
    }

    // regsiter
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return $user;
    }

    // login
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

    // update for name
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

    //update for email
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

    // delete acctount
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

    // change password
    // public function changePassword($userId, Request $request)
    // {
    //     if (!isset($_POST['oldPassword'], $_POST['newPassword'])) {
    //         return false;
    //     }

    //     $oldPassword = $_POST['oldPassword'];
    //     $newPassword = $_POST['newPassword'];


    //     $user = User::find($userId);
    //     if (!$user) {

    //         return false;
    //     }
    //     if (!password_verify($oldPassword, $user->password)) {

    //         return false;
    //     }


    //     $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
    //     $user->save();

    //     return response()->json($user);
    // }
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');

        // Check if the current password and the new password are the same
        if (Hash::check($newPassword, $user->password)) {
            return response()->json(['message' => 'รหัสผ่านใหม่ต้องแตกต่างจากรหัสผ่านปัจจุบัน'], 400);
        }

        if (Hash::check($currentPassword, $user->password)) {
            $user->password = Hash::make($newPassword);
            $user->save();

            $updatedUser = User::find($user->id);

            return response()->json([
                'message' => 'รหัสผ่านถูกเปลี่ยนแล้ว',
                'user' => $updatedUser,
            ], 200);
        } else {
            return response()->json(['message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'], 400);
        }
    }

    // logput
    public function logout(Request $request)
    {
        $cookie = \Cookie::forget('jwt');
        return response(['message' => ' logout success'])->withCookie($cookie);
    }
}
