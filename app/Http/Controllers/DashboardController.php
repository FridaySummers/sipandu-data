<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\Dinas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalDinas = Dinas::count();
        $totalDataSubmissions = DataSubmission::count();
        $pendingData = DataSubmission::where('status', 'pending')->count();
        $approvedData = DataSubmission::where('status', 'approved')->count();
        $targetGlobal = $totalDinas * 10; //target per dinas 10 data submission
        $progresspercent = $targetGlobal > 0 ? ($totalDataSubmissions / $targetGlobal) * 100 : 0;

        // Calculate complete data (approved submissions)
        $completeData = $approvedData;

        // Calculate average progress percentage
        $avgProgress = round($progresspercent, 0) . '%';

        // Calculate pending reviews (same as pending data submissions)
        $pendingReviews = $pendingData;

        return view('dashboard', compact(
            'totalDinas',
            'totalDataSubmissions',
            'pendingData',
            'approvedData',
            'progresspercent',
            'completeData',
            'avgProgress',
            'pendingReviews'
        ));
    }

    public function reports()
    {
        // Get report statistics
        $totalReports = 156; // This would typically come from a Report model if available
        $averageCompletion = 87; // Calculate from actual data if possible
        $dataPoints = DataSubmission::count() * 100; // Approximate data points
        $activeReports = 24; // Calculate active reports if available

        // Get dinas data for charts
        $dinas = \App\Models\Dinas::with('dataSubmissions')->get();
        $dinasData = $dinas->map(function($d) {
            $total = $d->dataSubmissions->count();
            $approved = $d->dataSubmissions->where('status', 'approved')->count();
            $progress = $d->dataSubmissions->whereIn('status', ['pending', 'in_review'])->count();
            $completionRate = $total > 0 ? round(($approved / $total) * 100, 0) : 0;

            return [
                'name' => $d->nama_dinas,
                'total' => $total,
                'approved' => $approved,
                'in_progress' => $progress,
                'completion_rate' => $completionRate,
                'status' => $completionRate >= 90 ? 'Complete' : ($completionRate >= 50 ? 'Progress' : 'Pending')
            ];
        })->toArray();

        return view('reports', [
            'totalReports' => $totalReports,
            'averageCompletion' => $averageCompletion . '%',
            'dataPoints' => number_format($dataPoints, 0, '.', ','),
            'activeReports' => $activeReports,
            'dinasData' => $dinasData
        ]);
    }

    public function calendar()
    {
        return view('calendar');
    }

    public function forum()
    {
        return view('forum');
    }

    public function settings()
    {
        return view('settings');
    }

    public function dinasStatus()
    {
        // Get dinas statistics
        $dinas = \App\Models\Dinas::with('dataSubmissions')->get();
        $totalDinas = $dinas->count();
        $completeDinas = 0;
        $progressDinas = 0;
        $pendingDinas = 0;

        foreach($dinas as $d) {
            $total = $d->dataSubmissions->count();
            $approved = $d->dataSubmissions->where('status', 'approved')->count();

            if($total > 0) {
                $completionRate = $approved / $total * 100;
                if($completionRate >= 90) {
                    $completeDinas++;
                } elseif($completionRate >= 30) {
                    $progressDinas++;
                } else {
                    $pendingDinas++;
                }
            } else {
                // If no data submissions, consider as pending
                $pendingDinas++;
            }
        }

        return view('dinas-status', [
            'totalDinas' => $totalDinas,
            'completeDinas' => $completeDinas,
            'progressDinas' => $progressDinas,
            'pendingDinas' => $pendingDinas,
            'dinasList' => $dinas
        ]);
    }
}
