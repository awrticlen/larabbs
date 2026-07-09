<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('标识')
                    ->required()
                    ->maxLength(15)
                    ->unique(table: 'roles', column: 'name', ignoreRecord: true)
                    ->validationMessages([
                        'required' => '标识不能为空',
                        'unique' => '标识已存在',
                    ]),

                Select::make('permissions')
                    ->label('权限')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }
}