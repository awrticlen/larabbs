<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('标识（请慎重修改）')
                    ->helperText('修改权限标识会影响代码的调用，请不要轻易更改。')
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'permissions', column: 'name', ignoreRecord: true),

                Select::make('roles')
                    ->label('角色')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }
}