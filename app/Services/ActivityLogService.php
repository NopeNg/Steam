<?php

namespace App\Services;

use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityLogService
{
    /**
     * Log an admin activity.
     *
     * @param string $action Mô tả hành động (VD: 'Cập nhật đơn hàng #123', 'Xóa game #5')
     * @param string|null $details Chi tiết bổ sung (VD: 'Trạng thái: Pending -> Completed')
     * @return ActivityLog
     */
    public function log(string $action, ?string $details = null): ActivityLog
    {
        return ActivityLog::create([
            'action' => $action,
            'details' => $details,
        ]);
    }

    /**
     * Get paginated activity logs with optional filters.
     *
     * @param array $filters ['search', 'start_date', 'end_date']
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLogs(array $filters = [], int $perPage = 20)
    {
        $query = ActivityLog::query();

        // Search filter
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        // Date range filter (convert to UTC for database comparison)
        if (!empty($filters['start_date'])) {
            $startDate = Carbon::createFromFormat('Y-m-d', $filters['start_date'], 'Asia/Ho_Chi_Minh')
                ->startOfDay()
                ->setTimezone('UTC');
            $query->where('created_at', '>=', $startDate);
        }
        if (!empty($filters['end_date'])) {
            $endDate = Carbon::createFromFormat('Y-m-d', $filters['end_date'], 'Asia/Ho_Chi_Minh')
                ->endOfDay()
                ->setTimezone('UTC');
            $query->where('created_at', '<=', $endDate);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Delete old logs (older than given days).
     *
     * @param int $days
     * @return int Number of deleted records
     */
    public function cleanOldLogs(int $days = 90): int
    {
        return ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
    }
}