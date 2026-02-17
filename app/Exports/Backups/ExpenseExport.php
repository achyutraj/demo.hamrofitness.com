<?php

namespace App\Exports\Backups;

use App\Models\GymExpense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseExport implements FromView
{
    public function __construct($user = null) {
        $this->user = $user;
    }

    public function view(): View
    {
        $expenses = GymExpense::where('detail_id', $this->user)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gym-admin.excel.expense', ['expenses' => $expenses]);
    }
}
