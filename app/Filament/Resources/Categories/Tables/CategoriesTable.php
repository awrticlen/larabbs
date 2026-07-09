<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('名称')
                    ->searchable(),

                TextColumn::make('description')
                    ->label('描述')
                    ->limit(80)
                    ->wrap()
                    ->placeholder('N/A')
                    ->searchable(),

                TextColumn::make('post_count')
                    ->label('帖子数')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('id')
                    ->label('分类 ID')
                    ->schema([
                        TextInput::make('value')
                            ->label('分类 ID')
                            ->integer(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereKey($data['value'])
                        : $query),

                Filter::make('name')
                    ->label('名称')
                    ->schema([
                        TextInput::make('value')
                            ->label('名称'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('name', 'like', "%{$data['value']}%")
                        : $query),

                Filter::make('description')
                    ->label('描述')
                    ->schema([
                        TextInput::make('value')
                            ->label('描述'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('description', 'like', "%{$data['value']}%")
                        : $query),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('编辑'),

                DeleteAction::make()
                    ->label('删除')
                    ->visible(fn (): bool => auth()->user()?->hasRole('Founder') ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('删除所选')
                        ->visible(fn (): bool => auth()->user()?->hasRole('Founder') ?? false),
                ]),
            ]);
    }
}