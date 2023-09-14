<?php

namespace App\Filament\Resources\SewadarResource\Pages;

use App\Filament\Resources\SewadarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSewadars extends ListRecords
{
    protected static string $resource = SewadarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
