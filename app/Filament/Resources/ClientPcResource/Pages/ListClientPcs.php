<?php

namespace App\Filament\Resources\ClientPcResource\Pages;

use App\Filament\Resources\ClientPcResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListClientPcs extends ListRecords
{
    protected static string $resource = ClientPcResource::class;

    /**
     * Columns exported to both PDF and Excel.
     *
     * @var array<string, string>
     */
    protected array $exportColumns = [
        'company' => 'Customer',
        'pc_name' => 'PC Name',
        'type' => 'Type',
        'app_id' => 'App',
        'hardware_id' => 'Hardware ID',
        'status' => 'Status',
        'activated_at' => 'Activated On',
        'created_at' => 'Added On',
    ];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportPdf')
                ->label('Export to PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(fn () => $this->exportToPdf()),

            Actions\Action::make('exportExcel')
                ->label('Export to Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
        ];
    }

    /**
     * Records matching the current search, filters and sort.
     */
    protected function getExportRecords()
    {
        return $this->getFilteredTableQuery()
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    protected function formatValue(string $key, $record): string
    {
        return match ($key) {
            'company' => (string) ($record->company?->name ?? ''),
            'type', 'status' => ucfirst((string) ($record->{$key} ?? '')),
            'activated_at', 'created_at' => $record->{$key} ? Carbon::parse($record->{$key})->format('d M Y H:i') : '',
            default => (string) ($record->{$key} ?? ''),
        };
    }

    protected function exportToPdf()
    {
        $records = $this->getExportRecords();
        $headings = array_values($this->exportColumns);

        $rows = $records->map(function ($record) {
            $row = [];
            foreach (array_keys($this->exportColumns) as $key) {
                $row[] = $this->formatValue($key, $record);
            }
            return $row;
        })->all();

        $pdf = Pdf::loadView('exports.client-pcs-pdf', [
            'headings' => $headings,
            'rows' => $rows,
            'generatedAt' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        $fileName = 'client-pcs-' . now()->format('Y-m-d-His') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
        );
    }

    protected function exportToExcel(): StreamedResponse
    {
        $records = $this->getExportRecords();
        $fileName = 'client-pcs-' . now()->format('Y-m-d-His') . '.xlsx';

        return response()->streamDownload(function () use ($records) {
            $writer = new Writer();
            $writer->openToFile('php://output');

            $headerStyle = (new Style())->setFontBold();
            $writer->addRow(Row::fromValues(array_values($this->exportColumns), $headerStyle));

            foreach ($records as $record) {
                $row = [];
                foreach (array_keys($this->exportColumns) as $key) {
                    $row[] = $this->formatValue($key, $record);
                }
                $writer->addRow(Row::fromValues($row));
            }

            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
