<?php

namespace App\Filament\Resources\Topics\Schemas;

use App\Models\Category;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('标题')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => '请填写标题',
                    ]),

                Select::make('user_id')
                    ->label('用户')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(fn (User $record): string => "{$record->id} {$record->name}")
                    ->searchable(['id', 'name'])
                    ->preload()
                    ->default(fn (): ?int => auth()->id())
                    ->required(),

                Select::make('category_id')
                    ->label('分类')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Category $record): string => "{$record->id} {$record->name}")
                    ->searchable(['id', 'name'])
                    ->preload()
                    ->required(),

                Textarea::make('body')
                    ->label('内容')
                    ->required()
                    ->rows(8)
                    ->columnSpanFull(),

                TextInput::make('reply_count')
                    ->label('评论')
                    ->integer()
                    ->minValue(0)
                    ->default(0),

                TextInput::make('view_count')
                    ->label('查看')
                    ->integer()
                    ->minValue(0)
                    ->default(0),
            ]);
    }
}