<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\License;
use App\Models\NumberOfClientPC;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $isSuperAdmin = $user->user_type === 'super_admin';

        // Companies registered.
        $companies = $isSuperAdmin
            ? Company::count()
            : Company::where('id', $user->company_id)->count();

        // Devices currently active.
        $activeDevices = NumberOfClientPC::where('status', 'active')
            ->when(! $isSuperAdmin, fn ($query) => $query->where('company_id', $user->company_id))
            ->count();

        // Licenses expiring within the next 10 days.
        $expiringSoon = License::whereNotNull('expiry')
            ->whereBetween('expiry', [now(), now()->addDays(10)])
            ->when(! $isSuperAdmin, fn ($query) => $query->where('company_id', $user->company_id))
            ->count();

        return [
            Stat::make('Total Customers Registered', $companies)
                ->icon('heroicon-o-building-office-2')
                ->color('primary'),
            Stat::make('Total Active Devices', $activeDevices)
                ->icon('heroicon-o-computer-desktop')
                ->color('success'),
            Stat::make('Expiring Soon (Within 10 days)', $expiringSoon)
                ->icon('heroicon-o-clock')
                ->color($expiringSoon > 0 ? 'danger' : 'gray'),
        ];
    }
}
