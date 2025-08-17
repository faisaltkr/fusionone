<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

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
                TextColumn::make('purchase_purchase_return_no')->label('Purchase/Return No'),
                TextColumn::make('supplier_id')->label('Supplier ID'),
                TextColumn::make('supplier_name')->label('Supplier Name'),
                TextColumn::make('transaction_type')
                    ->label('Transaction Type')
                    ->formatStateUsing(fn ($state) => $state ? (['purchase' => 'Purchase', 'purchase_return' => 'Purchase Return'][$state] ?? 'N/A') : 'N/A'),
                
                TextColumn::make('mode_of_transaction')
                    ->label('Mode of Transaction')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'cash' => 'Cash',
                        'credit' => 'Credit',
                        'bank' => 'Bank Transfer',
                        default => 'N/A',
                    }),
                TextColumn::make('gross_amount')->label('Gross Amount')->money('INR', true),
                TextColumn::make('discount')->label('Discount')->money('INR', true),
                TextColumn::make('net_amount')->label('Net Amount')->money('INR', true),
                TextColumn::make('vat_amount')->label('VAT Amount')->money('INR', true),
                TextColumn::make('grand_amount')->label('Grand Amount')->money('INR', true),
            ])
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
