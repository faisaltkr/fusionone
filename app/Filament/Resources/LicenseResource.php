<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LicenseResource\Pages;
use App\Models\License;
use App\Models\NumberOfClientPC;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Licenses';

    protected static ?string $modelLabel = 'License';

    protected static ?string $pluralModelLabel = 'Licenses';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getModel()::where('status', 'pending')->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('License')
                    ->schema([
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Company'),

                        Select::make('license_type')
                            ->options(['demo' => 'Demo', 'full' => 'Full'])
                            ->required()
                            ->default('demo'),

                        Select::make('app_id')
                            ->label('App')
                            ->options([
                                'fusionOne' => 'FusionOne',
                                'R-Pos' => 'R-Pos',
                                'Pos' => 'Pos',
                            ])
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'expired' => 'Expired',
                                'revoked' => 'Revoked',
                            ])
                            ->required()
                            ->default('pending'),

                        TextInput::make('unique_register_id')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('hardware_id')
                            ->required()
                            ->maxLength(255),

                        DateTimePicker::make('expiry'),

                        DatePicker::make('support_expiry_date'),

                        TextInput::make('license_key')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('license_type')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'full' ? 'success' : 'info')
                    ->sortable(),

                TextColumn::make('app_id')
                    ->label('App')
                    ->badge()
                    ->sortable(),

                TextColumn::make('hardware_id')
                    ->searchable()
                    ->limit(20)
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'expired' => 'danger',
                        'revoked' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('license_key')
                    ->placeholder('— not generated —')
                    ->copyable()
                    ->toggleable()
                    ->wrap(),

                TextColumn::make('expiry')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('support_expiry_date')
                    ->date()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'revoked' => 'Revoked',
                    ]),
                Tables\Filters\SelectFilter::make('license_type')
                    ->options(['demo' => 'Demo', 'full' => 'Full']),
                Tables\Filters\SelectFilter::make('app_id')
                    ->label('App')
                    ->options([
                        'fusionOne' => 'FusionOne',
                        'R-Pos' => 'R-Pos',
                        'Pos' => 'Pos',
                    ]),
            ])
            ->actions([
                static::generateKeyTableAction(),
                static::revokeTableAction(),
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

    /**
     * "Generate License Key" row action — collects the support expiry date,
     * then builds and stores the license key and activates the license.
     */
    public static function generateKeyTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('generateKey')
            ->label('Generate License Key')
            ->icon('heroicon-o-key')
            ->color('primary')
            ->visible(fn (License $record): bool => blank($record->license_key))
            ->form([
                DatePicker::make('support_expiry_date')
                    ->label('Support Expiry Date')
                    ->required()
                    ->minDate(now()),
            ])
            ->action(function (License $record, array $data): void {
                static::issueLicenseKey($record, $data['support_expiry_date']);

                Notification::make()
                    ->title('License key generated')
                    ->body($record->license_key)
                    ->success()
                    ->send();
            });
    }

    /**
     * Shared logic to issue a key for a license record.
     */
    public static function issueLicenseKey(License $record, string $supportExpiryDate): void
    {
        $record->support_expiry_date = $supportExpiryDate;
        $record->license_key = $record->generateLicenseKey();
        $record->status = $record->isExpired() ? 'expired' : 'active';
        $record->save();

        // Stamp the matching device as activated now that its key is issued.
        NumberOfClientPC::where('hardware_id', $record->hardware_id)
            ->where('app_id', $record->app_id)
            ->update(['activated_at' => now()]);
    }

    /**
     * "Revoke" row action — disables an issued license. A revoked license
     * fails API verification even though its key/expiry are otherwise valid.
     */
    public static function revokeTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('revoke')
            ->label('Revoke')
            ->icon('heroicon-o-no-symbol')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Revoke license')
            ->modalDescription('The device will fail license verification immediately. This can be undone by editing the license status.')
            ->visible(fn (License $record): bool => $record->status !== 'revoked')
            ->action(function (License $record): void {
                $record->update(['status' => 'revoked']);

                Notification::make()
                    ->title('License revoked')
                    ->success()
                    ->send();
            });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Client Details')
                    ->schema([
                        TextEntry::make('company.name')->label('Company'),
                        TextEntry::make('company.email')->label('Email'),
                        TextEntry::make('company.contact_person')->label('Contact Person'),
                        TextEntry::make('company.phone')->label('Phone'),
                        TextEntry::make('unique_register_id')->label('Register ID')->copyable(),
                    ])
                    ->columns(2),

                InfoSection::make('Device & App')
                    ->schema([
                        TextEntry::make('app_id')->label('App')->badge(),
                        TextEntry::make('hardware_id')->copyable(),
                    ])
                    ->columns(2),

                InfoSection::make('License')
                    ->schema([
                        TextEntry::make('license_type')->badge(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'expired' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('expiry')->dateTime(),
                        TextEntry::make('support_expiry_date')->date()->placeholder('—'),
                        TextEntry::make('license_key')
                            ->placeholder('— not generated —')
                            ->copyable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'view' => Pages\ViewLicense::route('/{record}'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }
}
