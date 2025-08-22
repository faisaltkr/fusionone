<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use App\Models\Sale;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
        ];
    }
    protected function getStats(): array
    {
        $sales = (Auth::user()->user_type=='super_admin') ? Sale::sum('grand_amount') : Sale::where('company_id',Auth::id())->sum('grand_amount');

        $purchase = (Auth::user()->user_type=='super_admin') ? Purchase::sum('grand_amount') : Purchase::where('company_id',Auth::id())->sum('grand_amount');

        $svat = (Auth::user()->user_type=='super_admin') ? Sale::sum('vat_amount') : Sale::where('company_id',Auth::id())->sum('vat_amount');

        $pvat = (Auth::user()->user_type=='super_admin') ? Purchase::sum('vat_amount') : Purchase::where('company_id',Auth::id())->sum('vat_amount');
        $vat = $svat-$pvat;

        return [
            Stat::make('Total Sales', "₹$sales"),
            Stat::make('Total Purchase', "₹$purchase"),
            Stat::make('Total VAT', "₹$vat"),
        ];
    }
}
