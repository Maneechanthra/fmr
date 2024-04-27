<?php

namespace App\Http\Controllers;

use App\Models\restaurant_reports;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    use SoftDeletes;

    public function insertReport(Request $request)
    {
        $report = new restaurant_reports;
        $report->restaurant_id = $request->input('restaurant_id');
        $report->title = $request->input('title');
        $report->descriptions = $request->input('descriptions');
        $report->report_by = $request->input('report_by');
        $report->save();
        return $report;
    }

    public function getReportByUserId($userId)
    {
        $reports = restaurant_reports::select(
            'restaurant_reports.title',
            'restaurant_reports.descriptions',
            DB::raw('COUNT(restaurant_reports.id) as report_count'),
            'restaurant_reports.id',
            'restaurants.restaurant_name'
        )
            ->join('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->join('users', 'restaurants.created_by', '=', 'users.id')
            ->where('restaurants.created_by', '=', $userId)
            ->groupBy(
                'restaurant_reports.id',
                'restaurant_reports.title',
                'restaurant_reports.descriptions',
                'restaurants.restaurant_name'
            )
            ->get();

        return $reports;
    }
}
