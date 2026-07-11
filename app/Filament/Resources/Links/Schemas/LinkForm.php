<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('名称')
                    ->required()
                    ->maxLength(255),

                TextInput::make('link')
                    ->label('链接')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => '请填写资源链接。',
                        'url' => '请填写有效的链接地址。',
                    ]),
            ]);
    }
}