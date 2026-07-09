<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('标识')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('角色')
                    ->badge()
                    ->separator(' | ')
                    ->placeholder('N/A'),
            ])
            ->filters([
                Filter::make('name')
                    ->label('标识')
                    ->schema([
                        TextInput::make('value')
                            ->label('标识'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('name', 'like', "%{$data['value']}%")
                        : $query),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('编辑'),
            ]);
    }
}