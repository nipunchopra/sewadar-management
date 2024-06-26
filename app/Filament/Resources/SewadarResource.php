<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SewadarResource\Pages;
use App\Filament\Resources\SewadarResource\RelationManagers;
use App\Models\Area;
use App\Models\Group;
use App\Models\Sewadar;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SewadarResource extends Resource
{
    protected static ?string $model = Sewadar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
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
                            })
                    ])->columns(3),



                TextInput::make('badge_number')->required()->numeric(),
                FileUpload::make('photo')->image()->directory('sewadars'),

                Fieldset::make('Personal Details')
                    ->schema([
                        TextInput::make('first_name')->required(),
                        TextInput::make('last_name')->required(),
                        TextInput::make('father_name')->required(),
                        DatePicker::make('dob')->maxDate(now()->today())->label('Date of Birth')->native(false)->required(),
                        TextInput::make('mobile')->label('Mobile No.')->required(),
                        TextInput::make('alt_mobile')->label('Alternate Mobile No.'),

                        TextInput::make('address')->columnSpan(2)->required(),
                        TextInput::make('city')->required(),

                        Select::make('blood_group')->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ])->required()->native(false),

                        TextInput::make('occupation')->required(),

                        Select::make('education')->options([
                            'Illiterate' => 'Illiterate',
                            'Primary' => 'Primary',
                            'Middle' => 'Middle',
                            'Matric' => 'Matric',
                            'Intermediate' => 'Intermediate',
                            'Graduate' => 'Graduate',
                            'Post Graduate' => 'Post Graduate',
                            'PhD' => 'PhD',
                        ])->required()->native(false),
                    ])->columns(3),


                Toggle::make('naamdan')->default(false)->columnSpanFull()->reactive()->required(),

                Fieldset::make('Naamdan Details')->schema([
                    DatePicker::make('date_of_naamdan')->maxDate(now()->today())->native(false),
                    TextInput::make('place_of_naamdan'),
                    TextInput::make('naamdan_by'),

                    Toggle::make('address_at_time_of_naamdan_same_as_present')
                        ->label('Same as present address')->default(true)->reactive(),

                    TextInput::make('address_at_time_of_naamdan')->visible(fn($get) => !$get('address_at_time_of_naamdan_same_as_present'))
                        ->columnSpan(2),
                ])->columns(3)->hidden(fn($get) => !$get('naamdan')),

                Fieldset::make('Permissions')->schema([
                    Toggle::make('mobile_permission')->default(false),
                    Toggle::make('car_permission')->reactive()->default(false)->columnSpan(2),

                    TextInput::make('car_number')->hidden(fn($get) => !$get('car_permission')),
                    TextInput::make('car_name')->hidden(fn($get) => !$get('car_permission')),
                    TextInput::make('car_seats')->hidden(fn($get) => !$get('car_permission')),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                ImageColumn::make('photo')->label('Photo'),
                TextColumn::make('badge_number')->searchable()->sortable(),
                TextColumn::make('first_name')->searchable()->sortable(),
                TextColumn::make('last_name')->searchable()->sortable(),
                TextColumn::make('father_name')->searchable()->sortable(),
                TextColumn::make('group.area.name')->searchable()->sortable()->label('Area'),
                TextColumn::make('group')->searchable()->getStateUsing(fn($record) => "{$record->group->sewadar->first_name} {$record->group->sewadar->last_name} ({$record->group->sewadar->badge_number})")->sortable()->label('Group'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListSewadars::route('/'),
            'create' => Pages\CreateSewadar::route('/create'),
            'edit' => Pages\EditSewadar::route('/{record}/edit'),
        ];
    }
}
