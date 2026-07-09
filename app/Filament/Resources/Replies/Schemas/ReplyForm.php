<?php

namespace App\Filament\Resources\Replies\Schemas;

use App\Models\Topic;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('用户')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(fn (User $record): string => "{$record->id} {$record->name}")
                    ->searchable(['id', 'name'])
                    ->preload()
                    ->default(fn (): ?int => auth()->id())
                    ->required(),

                Select::make('topic_id')
                    ->label('话题')
                    ->relationship('topic', 'title')
                    ->getOptionLabelFromRecordUsing(fn (Topic $record): string => "{$record->id} {$record->title}")
                    ->searchable(['id', 'title'])
                    ->preload()
                    ->required(),

                Textarea::make('content')
                    ->label('回复内容')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull()
                    ->validationMessages([
                        'required' => '请填写回复内容',
                    ]),
            ]);
    }
}