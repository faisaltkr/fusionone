<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

   public static function canCreate(): bool
   {
      return false;
   }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entry_no')->label('Entry No'),
                TextColumn::make('sales_sale_return_no')->label('Sale/Return No'),
                TextColumn::make('customer_id')->label('Customer ID'),
                TextColumn::make('customer_name')->label('Customer Name'),
                TextColumn::make('transaction_type')
                    ->label('Transaction Type')
                    ->formatStateUsing(fn ($state) => $state ? (['Sales' => 'Sales', 'Sales Return' => 'Sales Return'][$state] ?? 'N/A') : 'N/A'),
                
                TextColumn::make('mode_of_transaction')
                    ->label('Mode of Transaction')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'CA' => 'Cash',
                        'CR' => 'Credit',
                        'BA' => 'Bank Transfer',
                        default => 'N/A',
                    }),
               
                TextColumn::make('gross_amount')->label('Gross Amount')->money('INR', true),
                TextColumn::make('discount')->label('Discount')->money('INR', true),
                TextColumn::make('net_amount')->label('Net Amount')->money('INR', true),
                TextColumn::make('vat_amount')->label('VAT Amount')->money('INR', true),
                TextColumn::make('grand_amount')->label('Grand Amount')->money('INR', true),
                
            ])
            // ->rowClasses(function ($record) {
            //     return match ($record->transaction_type) {
            //         'Sales'   => 'bg-gray-100 text-yellow-800',
            //         'Sales Return'  => 'bg-green-100 text-green-800',
            //         default     => '',
            //     };
            // })
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            //'create' => Pages\CreateSale::route('/create'),
            //'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
