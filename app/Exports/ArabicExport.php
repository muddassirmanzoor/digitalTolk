<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ArabicExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithCustomStartCell
{
    protected $operations;

    public function __construct(array $operations)
    {
        $this->operations = $operations;
    }

    public function collection()
    {
        return collect($this->operations)->map(function ($operation) {
            return [
                $operation['operational_id'] ?? '',
                $operation['operation']['agent']['agent_name'] ?? '',
                $operation['operation']['nationality'] ?? '',
                $operation['operation']['pax'] ?? '',
                $operation['operation']['voucher_number'] ?? '',
                json_decode($operation['operation']['group_numbers']) ?? '',
                $operation['operation']['group_leader_number'] ?? '',
                $operation['section'] ?? '',
                $operation['travel_from'] ?? '',
                $operation['travel_to'] ?? '',
                date('d-m-Y', strtotime($operation[$operation['dateColumn']]))  ?? '',
                $operation['flightNumber'] ?? '',
                $operation[$operation['timeColumn']] ?? '',
                $operation['terminal_name'] ?? '',
                $operation['transport_time'] ?? '',
                $operation['transport_company'] ?? '',
//                $operation['driver_assignment']['driver']['name'] ?? '',
//                $operation['driver_assignment']['driver']['phone'] ?? '',
//                $operation['operation']['receiver'] ?? '',
//                $operation['operation']['field_receiver'] ?? '',
//                $operation['operation']['comments'][0]['comments'] ?? '',
            ];
        });
    }
//    public function collection()
//    {
//        return collect([
//            [
//                '12345', 'Agent Name', 'Nationality 1', '5', 'Voucher 001',
//                'Group 001', 'Head 001', 'Type 1', 'City A', 'City B',
//                '2025-01-01', 'Flight 001', '10:00', 'Terminal 1',
//                '11:00', 'Company A', 'Driver A', '123456789',
//                'Receiver A', 'Field A', 'No comments',
//            ],
//            [
//                '67890', 'Agent Name 2', 'Nationality 2', '3', 'Voucher 002',
//                'Group 002', 'Head 002', 'Type 2', 'City C', 'City D',
//                '2025-01-02', 'Flight 002', '14:00', 'Terminal 2',
//                '15:00', 'Company B', 'Driver B', '987654321',
//                'Receiver B', 'Field B', 'Some comments',
//            ],
//        ]);
//    }

    public function headings(): array
    {
        return [
            "رقم التشغيل\nOperation ID",
            "الوكيل\nAGENT",
            "الجنسية\nNationality",
            "PAX",
            "الفاوتشر\nالقروب\nVoucher No.",
            "رقم المجموعة\nGroup Number\nmultiple",
            "رقم المشرف\nGroup Head Number",
            "الحركة\nType of Operation",
            "من\nFrom",
            "الى\nTo",
            "التاريخ\nDate",
            "رقم الرحلة\nFlight no",
            "ساعة الهبوط او الإقلاع\nDeparture / Arrival Time",
            "الصاله\nTerminal",
            "وقت حضور الباص\nBus Arrival Time",
            "الشركه الناقله\nTransport Company",
            "اسم السائق\nDriver name",
            "رقم السائق\nDriver number",
            "مسلم الحالة\nAirport Receiver",
            "مستلم الحالة\nField Receiver",
            "ملاحظات\nComments",
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    /**
     * Apply styles to the sheet.
     *
     * @param Worksheet $sheet
     * @return array|null
     */
    public function styles(Worksheet $sheet)
    {
        // Set background color for heading
        $sheet->getStyle('A1:U1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '92D050', // Green background color (RGB: 146, 208, 80)
                ],
            ],
            'font' => [
                'bold' => true, // Bold text
                'color' => [
                    'rgb' => '000000', // White text color
                ],
            ],
        ]);

        // Increase row height for the header
        $sheet->getRowDimension(1)->setRowHeight(25); // Adjust row height as needed

        // Adjust column width (if auto-size isn't enough)
        foreach (range('A', 'U') as $column) {
            $sheet->getColumnDimension($column)->setWidth(10); // Set wider column width
        }

        return null;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply RTL alignment for the whole sheet
                $sheet->setRightToLeft(true);

                // Apply alignment for all cells
                $sheet->getStyle('A1:U' . ($sheet->getHighestRow()))->getAlignment()->setHorizontal('right');

                // Apply black border to all cells
                $sheet->getStyle('A1:U' . $sheet->getHighestRow())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'], // Black border
                            ],
                        ],
                    ]);
                // Apply background color for rows based on the 'section' value
                $rowCount = $sheet->getHighestRow();
                for ($row = 2; $row <= $rowCount; $row++) {
                    $section = $sheet->getCell('H' . $row)->getValue(); // Assuming 'section' is in column H

                    $color = match ($section) {
                        'arrival' => 'FFE6F0', // Light Pink
                        'departure' => 'E6F7FF', // Light Blue
                        'movement' => 'E6FFE6', // Light Green
                        'mzarat' => 'FFF4E6', // Light Orange
                        default => null,
                    };

                    if ($color) {
                        $sheet->getStyle("A$row:U$row")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $color],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
