<?php

namespace App\Exports;

use App\Models\EmployeePlantAssignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class EmployeePlantAssignmentsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $plantFilter;
    protected $search;

    public function __construct($plantFilter = null, $search = null)
    {
        $this->plantFilter = $plantFilter;
        $this->search = $search;
    }

    public function collection()
    {
        $query = EmployeePlantAssignment::with(['employee', 'plant'])
            ->where('is_deleted', 0);

        // plant filter
        if ($this->plantFilter) {
            $query->where('plant_id', $this->plantFilter);
        }

        // search filter
        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('employee_name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('plant', function ($q) {
                $q->where('plant_name', 'like', '%' . $this->search . '%');
            });
        }

        $data = $query->get();
        $sr = 1;

        return $data->map(function ($item) use (&$sr) {
            return [
                'Sr No'        => $sr++,
                'Employee'     => $item->employee->employee_name ?? '-',
                'Plant'        => $item->plant->plant_name ?? '-',
                'Departments'  => $item->departments_names,
                'Projects'     => $item->projects_names,
                'Created At'   => $item->created_at->format('d-m-Y h:i:s A'),
                'Status'       => $item->is_active ? 'Active' : 'Inactive',
            ];
        });
    }

    // ==============================
    // REQUIRED METHODS FIX
    // ==============================

    public function headings(): array
    {
        return [
            'Sr No',
            'Employee',
            'Plant',
            'Departments',
            'Projects',
            'Created At',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Border
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                            ]
                        ]
                    ]);

                // Header style
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Auto width
                foreach (range('A', $lastColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }

    public static function getFilteredData($plantFilter = null, $search = null)
    {
        $query = EmployeePlantAssignment::with(['employee', 'plant'])
            ->where('is_deleted', 0);

        if ($plantFilter) {
            $query->where('plant_id', $plantFilter);
        }

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('employee_name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('plant', function ($q) use ($search) {
                    $q->where('plant_name', 'like', '%' . $search . '%');
                });
        }

        return $query->get();
    }

}



    // public function headings(): array
    // {
    //     return ['Sr No', 'Employee', 'Plant', 'Departments', 'Projects', 'Created At', 'Status'];
    // }

    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //         1 => ['font' => ['bold' => true]]
    //     ];
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();
    //             $lastRow = $sheet->getHighestRow();
    //             $lastColumn = $sheet->getHighestColumn();

    //             // Apply borders
    //             $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
    //                 'borders' => [
    //                     'allBorders' => [
    //                         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
    //                     ]
    //                 ]
    //             ]);

    //             // Header styling
    //             $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
    //                 'fill' => [
    //                     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                     'startColor' => ['argb' => '952419']
    //                 ],
    //                 'font' => [
    //                     'bold' => true,
    //                     'color' => ['argb' => 'FFFFFF']
    //                 ],
    //                 'alignment' => [
    //                     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                     'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    //                 ]
    //             ]);

    //             // Auto-size columns
    //             foreach (range('A', $lastColumn) as $col) {
    //                 $sheet->getColumnDimension($col)->setAutoSize(true);
    //             }
    //         }
    //     ];
    // }
// }
