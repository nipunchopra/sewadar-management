<?php

namespace App\Filament\Resources\ListInChargeResource\Pages;

use App\Filament\Resources\ListInChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListInCharges extends ListRecords
{
    protected static string $resource = ListInChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
