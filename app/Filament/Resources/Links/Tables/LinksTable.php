<?php

namespace App\Filament\Resources\Links\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('名称')
                    ->searchable(),

                TextColumn::make('link')
                    ->label('链接')
                    ->url(fn ($record): string => $record->link)
                    ->openUrlInNewTab()
                    ->copyable()
                    ->limit(80)
                    ->tooltip(fn ($record): string => $record->link),
            ])
            ->filters([
                Filter::make('id')
                    ->label('资源 ID')
                    ->schema([
                        TextInput::make('value')
                            ->label('资源 ID')
                            ->integer(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereKey($data['value'])
                        : $query),

                Filter::make('title')
                    ->label('名称')
                    ->schema([
                        TextInput::make('value')
                            ->label('名称'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('title', 'like', "%{$data['value']}%")
                        : $query),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('编辑'),

                DeleteAction::make()
                    ->label('删除'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('删除所选'),
                ]),
            ]);
    }
}