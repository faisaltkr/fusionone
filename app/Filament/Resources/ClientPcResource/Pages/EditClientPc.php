<?php

namespace App\Filament\Resources\ClientPcResource\Pages;

use App\Filament\Resources\ClientPcResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientPc extends EditRecord
{
    protected static string $resource = ClientPcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
