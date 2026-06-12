<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsReportExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithEvents, WithStyles, WithTitle
{
    private const HEADER_ROW = 5;

    public function __construct(
        private readonly string $reportTitle,
        private readonly Collection $rows,
        private readonly array $columns,
        private readonly array $appliedFilters,
    ) {
    }

    public function array(): array
    {
        $data = [
            [$this->reportTitle],
            ['Gerado em', now()->format('d/m/Y H:i'), 'Total de itens', $this->rows->count()],
            ['Filtros', $this->formattedFilters()],
            [],
            collect($this->columns)->pluck('label')->all(),
        ];

        foreach ($this->rows as $row) {
            $data[] = collect($this->columns)->map(function (array $column) use ($row) {
                $value = data_get($row, $column['key']);

                if ($column['type'] === 'money') {
                    return (float) ($value ?? 0);
                }

                if ($column['type'] === 'int') {
                    return (int) ($value ?? 0);
                }

                return $value ?? '-';
            })->all();
        }

        if ($this->rows->isEmpty()) {
            $data[] = ['Nenhum dado encontrado para os filtros selecionados.'];
        }

        return $data;
    }

    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->columns as $index => $column) {
            $letter = Coordinate::stringFromColumnIndex($index + 1);

            if ($column['type'] === 'money') {
                $formats[$letter] = '"R$" #,##0.00';
            }

            if ($column['type'] === 'int') {
                $formats[$letter] = '#,##0';
            }
        }

        return $formats;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = Coordinate::stringFromColumnIndex(max(count($this->columns), 1));
                $lastRow = self::HEADER_ROW + max($this->rows->count(), 1);

                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->freezePane('A6');
                $sheet->setAutoFilter("A" . self::HEADER_ROW . ":{$lastColumn}{$lastRow}");

                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle("A" . self::HEADER_ROW . ":{$lastColumn}" . self::HEADER_ROW)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2D6A35'],
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D4E8D6'],
                        ],
                    ],
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => '1A3D1F'],
                ],
            ],
            2 => [
                'font' => [
                    'color' => ['rgb' => '4A5C4C'],
                ],
            ],
            3 => [
                'font' => [
                    'color' => ['rgb' => '4A5C4C'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Relatorio';
    }

    private function formattedFilters(): string
    {
        return collect($this->appliedFilters)
            ->map(fn ($value, $label) => $label . ': ' . $value)
            ->implode(' | ');
    }
}
