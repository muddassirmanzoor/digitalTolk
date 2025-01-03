<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
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
                $operation['operation']['group_number'] ?? '',
                $operation['operation']['group_leader_number'] ?? '',
                $operation['section'] ?? '',
                $operation['travel_from'] ?? '',
                $operation['travel_to'] ?? '',
                $operation[date('d-m-Y', strtotime($operation[$operation['dateColumn']])) ] ?? '',
                $operation['flightNumber'] ?? '',
                $operation[$operation['timeColumn']] ?? '',
                $operation['terminal_name'] ?? '',
                $operation['transport_time'] ?? '',
                $operation['transport_company'] ?? '',
                $operation['driver_assignment']['driver']['name'] ?? '',
                $operation['driver_assignment']['driver']['phone'] ?? '',
                $operation['operation']['receiver'] ?? '',
                $operation['operation']['field_receiver'] ?? '',
                $operation['operation']['comments'][0]['comments'] ?? '',
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

    public function styles(Worksheet $sheet)
    {
        return [
            // Styling the headings
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set text direction to RTL
                $event->sheet->getDelegate()->setRightToLeft(true);
                // Set text direction to RTL
                $event->sheet->getDelegate()->getStyle('A1:U3')
                    ->getAlignment()->setHorizontal('right');
            },
        ];
    }
}
