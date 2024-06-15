<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class UserController extends Controller

{
    use SoftDeletes;

    public function reportInfoUser()
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

        $totalUsers = DB::table('users')->where('users.role', '=', '0')->whereNull('deleted_at')->count();

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

    public function getAdmin()
    {
        $admin = DB::table('users')
            ->where('role', '=', 1)
            ->get();

        return view('management.admin_management', [
            'dataAdmin' => $admin,
        ]);
    }
}
