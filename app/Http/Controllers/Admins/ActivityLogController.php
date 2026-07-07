<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $logs = $this->activityLogService->getLogs($filters);

        return view('Admins.activity_logs.index', compact('logs'));
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        // Get all logs without pagination for export
        $logs = $this->activityLogService->getLogs($filters, 10000);

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            echo "\xEF\xBB\xBF";
            
            // Header row
            fputcsv($output, ['ID', 'Thời gian', 'Hành động', 'Chi tiết']);
            
            // Data rows
            foreach ($logs as $log) {
                // Convert to Vietnam timezone (+7)
                $vietnamTime = $log->created_at->setTimezone('Asia/Ho_Chi_Minh');
                
                fputcsv($output, [
                    $log->id,
                    $vietnamTime->format('d/m/Y H:i:s'),
                    $log->action,
                    $log->details ?? ''
                ]);
            }
            
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
