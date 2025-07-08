<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return auth()->user()->user_type=='super_admin' ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 TextInput::make('name')->required()->maxLength(60),
                 TextInput::make('email')->required()->maxLength(60)->email(),
                 TextInput::make('password')->password()->required()->minLength(8)->maxLength(60)->confirmed()->dehydrateStateUsing(fn($state) => Hash::make($state))->dehydrated(fn($state) => filled($state))->visibleOn('create'),
                 TextInput::make('password_confirmation')->label('Confirm Password')->password()->required()->visibleOn('create')->dehydrated(false), // <-- Add this line
                 TextInput::make('company_name')->required()->maxLength(100),
                 TextInput::make('address')->required()->maxLength(255),
                 TextInput::make('place')->required()->maxLength(255),
                 TextInput::make('phone')->required()->maxLength(12),
                 TextInput::make('company_reg_id')->disabled(),
                 TextInput::make('status')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('company_name'),
                // TextColumn::make('address'),
                // TextColumn::make('phone'),
                // TextColumn::make('place'),
                ToggleColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
