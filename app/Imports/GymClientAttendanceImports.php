<?php

namespace App\Imports;

use App\Models\GymClientAttendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class GymClientAttendanceImports implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            GymClientAttendance::create([
                'client_id' => $row['client_id'],
                'check_in' => $this->transformDateTime($row['check_in']),
                'check_out' => $this->transformDateTime($row['check_out']),
                'status' => $row['inout_status'],
            ]);
        }
    }

    private function transformDateTime($value)
    {
        try {
            if (is_numeric($value)) {
                // Excel date conversion
                $unixTimestamp = ($value - 25569) * 86400;
                return Carbon::createFromTimestamp($unixTimestamp)->toDateTimeString();
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
