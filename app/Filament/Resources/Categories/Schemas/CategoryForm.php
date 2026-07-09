<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('名称')
                    ->required()
                    ->minLength(1)
                    ->maxLength(255)
                    ->unique(table: 'categories', column: 'name', ignoreRecord: true)
                    ->validationMessages([
                        'required' => '请确保名字至少一个字符以上',
                        'unique' => '分类名在数据库里有重复，请选用其他名称。',
                    ]),

                Textarea::make('description')
                    ->label('描述')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}