<?php

namespace App\Filament\Pages;


use App\Models\Sale;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class Dashboard extends PagesDashboard
{
    use HasFiltersForm;
    public function filtersForm(Form $form)
    {
        return $form->schema([
                Section::make()->schema([
                    DatePicker::make('startDate')->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                    DatePicker::make('endDate')->minDate(fn (Get $get) => $get('startDate') ?: now())->maxDate(now()),
                ])->columns(2)
            ]);
    }
}
