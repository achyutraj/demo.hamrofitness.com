<?php

namespace App\Exports\Backups;

use App\Models\Income;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OtherIncomeExport implements FromView
{
    public function __construct($user = null) {
        $this->user = $user;
    }

    public function view(): View
    {
        $incomes = Income::where('detail_id', $this->user)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gym-admin.excel.income', ['incomes' => $incomes]);
    }
}
