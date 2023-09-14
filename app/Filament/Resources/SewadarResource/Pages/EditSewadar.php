<?php

namespace App\Filament\Resources\SewadarResource\Pages;

use App\Filament\Resources\SewadarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSewadar extends EditRecord
{
    protected static string $resource = SewadarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
