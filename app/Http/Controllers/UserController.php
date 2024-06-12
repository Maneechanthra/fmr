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
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');

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

    // logout
    public function logout(Request $request)
    {
        $cookie = \Cookie::forget('jwt');
        return response(['message' => ' logout success'])->withCookie($cookie);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// management of admin

    //all restaurant and user



    public function index(Request $request)
    {
        $userData = $request->session()->get('user_data');
        // $userData = $request->cookie('user_data');


        // Fetch user and restaurant information
        $information = DB::table('users')
            ->leftJoin('restaurants', 'users.id', '=', 'restaurants.created_by')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->whereNull('users.deleted_at')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'restaurants.id as restaurant_id',
                'restaurants.restaurant_name as restaurant_name'
            )
            ->get();

        // Count total users
        $totalUsers = DB::table('users')->whereNull('deleted_at')->count();
        // Count total restaurants
        $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();

        // Fetch all restaurants
        $topRestaurants = DB::table('restaurants')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count'),
                'restaurants.status',
                'restaurants.verified',
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN restaurant_favorites.deleted_at IS NULL THEN restaurant_favorites.id ELSE NULL END) as favorites_count'),
            )
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.status',
                'restaurants.verified'
            )->orderBy('view_count', 'desc')
            ->get();

        if ($userData) {
            return view('index', [
                'userData' => $userData,
                'data' => $information,
                'total_users' => $totalUsers,
                'restaurantsCount' => $restaurantsCount,
                'topRestaurants' => $topRestaurants,
            ]);
        } else {
            return view('login.login');
        }
    }


    // get user information 
    public function reportInfoUser()
    {
        $information = DB::table('users')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'users.role'
            )
            // ->where('users.role', '=', '0')
            ->whereNull('users.deleted_at')
            ->get();

        $totalUsers = DB::table('users')->whereNull('deleted_at')->count();

        return view('report.report_user', [
            'data' => $information,
            'users_count' => $totalUsers,
        ]);
    }

    public function reportInfoUserAndAdjustStatus()
    {
        $information = DB::table('users')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'users.role'
            )
            ->where('users.role', '=', '0')
            ->whereNull('users.deleted_at')
            ->get();

        $totalUsers = DB::table('users')->whereNull('deleted_at')->count();

        return view('management.user_management', [
            'user' => $information,
            'users_count' => $totalUsers,
        ]);
    }
    public function updateStatus($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(['role' => 1]);

        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function deleteUser($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Status updated successfully');
        } else {
            return response()->json(array('error' => Response::HTTP_FORBIDDEN, 'message' => null));
        }
    }

    // login user
    public function verified_login_for_admin(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(
                ['message' => 'Invalid Credentials', 'status' => 0],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = Auth::user();
        if ($user->role !== 1) {
            Auth::logout();
            return response(
                ['message' => 'Unauthorized', 'status' => 0],
                Response::HTTP_FORBIDDEN
            );
        }

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        $request->session()->put('user_data', [
            'email' => $request->email,
            'userId' => $user->id,
            'jwt_token' => $token,
            'name' => $user->name,
            'message' => 'Login Success',
            'status' => 1
        ]);

        return redirect('/')->withCookie($cookie);
    }


    public function logout_for_admin(Request $request)
    {
        $request->session()->forget('user_data');
        return redirect('login');
    }
}
