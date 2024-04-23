<?php

namespace App\Http\Controllers;

use App\Models\restaurant_reports;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
