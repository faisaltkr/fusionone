<?php

namespace App\Filament\Pages;


use App\Models\Sale;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends PagesDashboard
{
    use HasFiltersForm;
   public function filterForm($form)
   {
          return $form->schema([
                DatePicker::make('startDate')->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                DatePicker::make('endDate')->minDate(fn (Get $get) => $get('startDate') ?: now())->maxDate(now()),
            ]);
   }
}
