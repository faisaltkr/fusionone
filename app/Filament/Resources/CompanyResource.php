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

    protected static ?string $navigationLabel = 'Companies';

    protected static ?string $modelLabel = 'Company';

    protected static ?string $pluralModelLabel = 'Companies';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
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
                    ->sortable(),
                
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('contact_person')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('place')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('activation_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('allowed_devices')
                    ->numeric()
                    ->sortable(),
                
                TextColumn::make('active_devices')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'gray',
                        $state < 5 => 'success',
                        $state < 10 => 'warning',
                        default => 'danger',
                    }),
                
                IconColumn::make('status')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active companies')
                    ->falseLabel('Inactive companies')
                    ->native(false),
            ])
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