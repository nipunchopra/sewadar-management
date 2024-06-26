<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Area;
use App\Models\Group;
use App\Models\Sewadar;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('area_id')->label('Area')
                    ->searchable()

                    ->options(fn() => Area::limit(5)->get(['name', 'id'])->pluck('name', 'id')->toArray())->native(false)
                    ->getSearchResultsUsing(fn(string $search): array => Area::where('name', 'like', "%{$search}%")->limit(10)->pluck('name', 'id')->toArray())->required(),
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
                TextColumn::make('id')->sortable(),
                TextColumn::make('area.name')->sortable(),
                TextColumn::make('sewadar.first_name')->searchable()->getStateUsing(fn($record) => $record->sewadar->first_name . " " . $record->sewadar->last_name . " ({$record->sewadar->badge_number})")->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
