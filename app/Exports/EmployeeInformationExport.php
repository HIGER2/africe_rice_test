<?php

namespace App\Exports;

use App\Models\EmployeeInformation;
use App\Models\EmployeeInformaton;
use App\Models\StaffRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeInformationExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return StaffRequest::with('employees')
            ->orderBy('created_at', 'desc')
            ->get()->map(function ($info) {
                return [
                    'N°' => $info->id,
                    'Employee' => $info->employees->firstName . " " . $info->employees->lastName,
                    'Job Title' => $info->employees->jobTitle,
                    'Departure Date' => $info->depart_date,
                    'Date of Taking Up Office' => $info->taking_date,
                    'Status Request' => $info->status,
                    'Status Payment' => isset($info->payments)  ? $info->payments->status_payment : "N/A",
                    'Payment Date' => isset($info->payments) ?  $info->payments->date_payment : "N/A",
                    'Submission Date' => \Carbon\Carbon::parse($info->created_at)->format('d-m-Y H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'N°',
            'Employee',
            'Job Title',
            'Departure Date',
            'Date of Taking Up Office',
            'Status Request',
            'Status Payment',
            'Payment Date',
            'Submission Date',
        ];
    }
}