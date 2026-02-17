<?php

namespace App\Exports\Backups;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ActivityLogExport implements FromView
{
    public function __construct($user = null) {
        $this->user = $user;
    }

    public function view(): View
    {
        $activities = Activity::with(['causer', 'subject'])
            ->where('detail_id', $this->user)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gym-admin.excel.activity-log', ['activities' => $activities]);
    }
} 