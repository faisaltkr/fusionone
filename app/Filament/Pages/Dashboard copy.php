<?php

namespace App\Filament\Pages;

namespace App\Filament\Pages;

use App\Models\Sale;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;

class Dashboard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

     protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public ?string $from_date = null;
    public ?string $to_date = null;

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('from_date')->label('From Date'),
            DatePicker::make('to_date')->label('To Date'),
        ];
    }

    public function getSalesTotal()
    {
        return Sale::whereBetween('created_at', [$this->from_date, $this->to_date])->sum('grand_amount');
    }

    public function getPurchaseTotal()
    {
        return Purchase::whereBetween('created_at', [$this->from_date, $this->to_date])->sum('grand_amount');
    }

    public function getVatTotal()
    {
        return Sale::whereBetween('created_at', [$this->from_date, $this->to_date])->sum('vat_amount');
    }

    public function getRecentTransactions()
    {
        
        if(!$this->from_date && !$this->to_date)
        {
            $this->from_date = Carbon::now()->startOf('day')->format('Y-m-d');
            $this->to_date = Carbon::now()->endOf('day')->format('Y-m-d');
        }
        return Sale::whereBetween('tr_date', [$this->from_date, $this->to_date])
            ->latest()
            ->take(5)
            ->get();
    }

    public function getZatcaSummary()
    {
        return [
            'invoice' => [
                'B2B' => ['success' => 10, 'pending' => 0, 'failed' => 0, 'total' => 0],
                'B2C' => ['success' => 0, 'pending' => 0, 'failed' => 0, 'total' => 0],
                'TOTAL' => ['success' => 0, 'pending' => 0, 'failed' => 0, 'total' => 0],
            ],
            // same for 'debit' and 'credit'
        ];
    }
}
