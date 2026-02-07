<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use App\Models\Sale;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    
    
    protected function getStats(): array
    {
        $filters = $this->filters;
        $salesQuery = (Auth::user()->user_type == 'super_admin')
            ? Sale::query()
            : Sale::where('company_id', Auth::id());

        $purchaseQuery = (Auth::user()->user_type == 'super_admin')
            ? Purchase::query()
            : Purchase::where('company_id', Auth::id());

        // Apply date filters if provided
        if (!empty($filters['date_from'])) {
            $salesQuery->whereDate('tr_date', '>=', $filters['date_from']);
            $purchaseQuery->whereDate('tr_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_until'])) {
            $salesQuery->whereDate('tr_date', '<=', $filters['date_until']);
            $purchaseQuery->whereDate('tr_date', '<=', $filters['date_until']);
        }

        $sales = $salesQuery->sum('grand_amount');
        $purchase = $purchaseQuery->sum('grand_amount');
        $svat = $salesQuery->sum('vat_amount');
        $pvat = $purchaseQuery->sum('vat_amount');
        $vat = $svat - $pvat;

        return [
            Stat::make('Total Sales', "₹" . number_format($sales, 2)),
            Stat::make('Total Purchase', "₹" . number_format($purchase, 2)),
            Stat::make('Total VAT', "₹" . number_format($vat, 2)),
        ];
    }
}
