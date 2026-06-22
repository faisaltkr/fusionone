<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Customers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Hidden::make('unique_register_id')
                            ->default(fn () => \Illuminate\Support\Str::uuid()->toString()),
                        
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(12)
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        TextInput::make('contact_person')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('place')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('address')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Device Management')
                    ->schema([
                        TextInput::make('activation_count')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0),
                        
                        TextInput::make('allowed_devices')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        
                        TextInput::make('active_devices')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->disabled()
                            ->dehydrated(),
                        
                        Toggle::make('status')
                            ->label('Active')
                            ->required()
                            ->default(true),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->wrap(),
                
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable()
                    ->wrap(),
                
                TextColumn::make('contact_person')
                    ->searchable()
                    ->toggleable()
                    ->wrap(),
                
                TextColumn::make('place')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->wrap(),
                
                TextColumn::make('activation_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable()
                    ->hiddenFrom('md'),
                
                TextColumn::make('allowed_devices')
                    ->numeric()
                    ->sortable()
                    ->hiddenFrom('md'),
                
                TextColumn::make('active_devices')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'gray',
                        $state < 5 => 'success',
                        $state < 10 => 'warning',
                        default => 'danger',
                    })
                    ->hiddenFrom('md'),
                
                IconColumn::make('status')
                    ->boolean()
                    ->sortable()
                    ->toggleable()
                    ->hiddenFrom('md'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hiddenFrom('lg'),
                
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hiddenFrom('lg'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active customers')
                    ->falseLabel('Inactive customers')
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->label('Registration Date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Registered from')
                            ->native(false)
                            ->closeOnDateSelection(),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Registered until')
                            ->native(false)
                            ->closeOnDateSelection(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Registered from ' . \Illuminate\Support\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Registered until ' . \Illuminate\Support\Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
            ])
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->label('Filters')
                    ->icon('heroicon-m-funnel'),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
            //'view' => Pages\ViewCompany::route('/{record}'),
        ];
    }
}