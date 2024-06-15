<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getReportRestaurantForReportUser()
    {
        $reportCount = DB::table('restaurant_reports')
            ->leftJoin('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->whereNull('restaurants.deleted_at')
            ->count();

        $reportCountByRestaurant = DB::table('restaurant_reports')
            ->leftJoin('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'restaurants.id as restaurant_id',
                DB::raw('COUNT(*) as report_count')
            )
            ->groupBy('restaurants.id')
            ->pluck('report_count', 'restaurant_id');

        $reports = DB::table('restaurant_reports')
            ->leftJoin('users', 'restaurant_reports.report_by', '=', 'users.id')
            ->leftJoin('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.status', '=', '1')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'restaurant_reports.id as report_id',
                'restaurant_reports.restaurant_id',
                'restaurant_reports.title as report_title',
                'restaurant_reports.descriptions as report_description',
                'restaurant_reports.status as report_status',
                'users.id as user_id',
                'users.name as user_name',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.status',
                'restaurants.verified'
            )
            ->get();


        $groupedReports = [];
        foreach ($reports as $report) {
            if (!isset($groupedReports[$report->restaurant_id])) {
                $groupedReports[$report->restaurant_id] = [
                    'restaurant_id' => $report->restaurant_id,
                    'restaurant' => [
                        'restaurant_name' => $report->restaurant_name,
                        'address' => $report->address,
                        'telephone_1' => $report->telephone_1,
                        'telephone_2' => $report->telephone_2,
                        'status' => $report->status,
                        'verified' => $report->verified,
                    ],
                    'report_titles' => [],
                    'report_descriptions' => [],
                    'reportCount' => 0,
                ];
            }
            $groupedReports[$report->restaurant_id]['report_titles'][] = $report->report_title;
            $groupedReports[$report->restaurant_id]['report_descriptions'][] = $report->report_description;
            $groupedReports[$report->restaurant_id]['reportCount']++;
        }

        return view('report.report_restaurant_by_user', [
            'groupedReports' => $groupedReports,
            'reportCount' => $reportCount,
            'reportCountByRestaurant' => $reportCountByRestaurant
        ]);
    }



    public function updateStatusRestaurant($id, $userId)
    {
        DB::table('restaurants')
            ->where('id', $id)
            ->update(['status' => -1, 'updated_by' => $userId]);

        return redirect()->back()->with('success', 'verified updated successfully');
    }
}
