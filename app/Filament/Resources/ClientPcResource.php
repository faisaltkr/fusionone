<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientPcResource\Pages;
use App\Models\Company;
use App\Models\NumberOfClientPC;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientPcResource extends Resource
{
    protected static ?string $model = NumberOfClientPC::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationLabel = 'Client PCs';

    protected static ?string $modelLabel = 'Client PC';

    protected static ?string $pluralModelLabel = 'Client PCs';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Device Information')
                    ->schema([
                        Select::make('company_id')
                            ->label('Customer')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('pc_name')
                            ->label('PC Name')
                            ->maxLength(255),

                        Select::make('type')
                            ->options([
                                'server' => 'Server',
                                'client' => 'Client',
                            ])
                            ->required(),

                        Select::make('app_id')
                            ->label('App')
                            ->options([
                                'fusionOne' => 'FusionOne',
                                'R-Pos' => 'R-Pos',
                                'Pos' => 'Pos',
                            ])
                            ->required(),

                        TextInput::make('hardware_id')
                            ->label('Hardware ID')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'surrender' => 'Surrender',
                            ])
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('pc_name')
                    ->label('PC Name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->wrap(),

                TextColumn::make('type')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => $state === 'server' ? 'info' : 'gray')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('app_id')
                    ->label('App')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('hardware_id')
                    ->label('Hardware ID')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->wrap(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'surrender' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('activated_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Added On')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Customer')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'surrender' => 'Surrender',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'server' => 'Server',
                        'client' => 'Client',
                    ]),

                Tables\Filters\SelectFilter::make('app_id')
                    ->label('App')
                    ->options([
                        'fusionOne' => 'FusionOne',
                        'R-Pos' => 'R-Pos',
                        'Pos' => 'Pos',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->label('Added Date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Added from')
                            ->native(false)
                            ->closeOnDateSelection(),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Added until')
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
                            $indicators[] = Tables\Filters\Indicator::make('Added from ' . \Illuminate\Support\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Added until ' . \Illuminate\Support\Carbon::parse($data['created_until'])->toFormattedDateString())
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientPcs::route('/'),
            'edit' => Pages\EditClientPc::route('/{record}/edit'),
        ];
    }
}
