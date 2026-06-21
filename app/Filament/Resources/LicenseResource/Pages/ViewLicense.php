<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use App\Filament\Resources\LicenseResource;
use App\Models\License;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLicense extends ViewRecord
{
    protected static string $resource = LicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateKey')
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
                    LicenseResource::issueLicenseKey($record, $data['support_expiry_date']);

                    Notification::make()
                        ->title('License key generated')
                        ->body($record->license_key)
                        ->success()
                        ->send();
                }),
            Actions\Action::make('revoke')
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
                }),
            Actions\EditAction::make(),
        ];
    }
}
