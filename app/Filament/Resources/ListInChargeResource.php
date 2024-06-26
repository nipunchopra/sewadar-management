<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListInChargeResource\Pages;
use App\Filament\Resources\ListInChargeResource\RelationManagers;
use App\Models\Group;
use App\Models\ListInCharge;
use App\Models\Sewadar;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListInChargeResource extends Resource
{
    protected static ?string $model = ListInCharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('group_id')->label('Group')->searchable()
                    ->options(function () {
                        $sewadar = [];

                        $groups = Group::with('sewadar')->limit(5)->get();

                        foreach ($groups as $group) {
                            $name = $group->sewadar->first_name . ' ' . $group->sewadar->last_name . ' (' . $group->sewadar->badge_number . ')';
                            $id = $group->id;
                            $sewadar[$id] = $name;
                        }
                        return $sewadar;

                    })->native(false)
                    ->getSearchResultsUsing(function ($search) {
                        $sewadar = [];

                        $groups = Group::with([
                            'sewadar' => function ($q) use ($search) {
                                return $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('badge_number', 'like', "%{$search}%");
                            }
                        ])->whereHas('sewadar', function ($q) use ($search) {
                            return $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('badge_number', 'like', "%{$search}%");
                        })->limit(5)->get();

                        foreach ($groups as $group) {
                            $name = $group->sewadar->first_name . ' ' . $group->sewadar->last_name . ' (' . $group->sewadar->badge_number . ')';
                            $id = $group->id;
                            $sewadar[$id] = $name;
                        }
                        return $sewadar;
                    }),


                Select::make('sewadar_id')->label('Sewadar')
                    ->searchable()
                    ->options(function () {
                        $sewadar = [];

                        $results = Sewadar::limit(5)->get(['first_name', 'last_name', 'id', 'badge_number']);

                        foreach ($results as $result) {
                            $name = $result->first_name . ' ' . $result->last_name . ' (' . $result->badge_number . ')';
                            $id = $result->id;
                            $sewadar[$id] = $name;
                        }
                        return $sewadar;
                    })->native(false)
                    ->getSearchResultsUsing(function ($search) {
                        $sewadar = [];

                        $results = Sewadar::where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('badge_number', 'like', "%{$search}%")->limit(5)->get(['first_name', 'last_name', 'id', 'badge_number']);

                        foreach ($results as $result) {
                            $name = $result->first_name . ' ' . $result->last_name . ' (' . $result->badge_number . ')';
                            $id = $result->id;
                            $sewadar[$id] = $name;
                        }
                        return $sewadar;
                    })->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListListInCharges::route('/'),
            'create' => Pages\CreateListInCharge::route('/create'),
            'edit' => Pages\EditListInCharge::route('/{record}/edit'),
        ];
    }
}
