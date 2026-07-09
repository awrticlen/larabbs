<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                ImageColumn::make('avatar')
                    ->label('头像')
                    ->circular()
                    ->size(40),

                TextColumn::make('name')
                    ->label('用户名')
                    ->url(fn ($record): string => route('users.show', $record))
                    ->openUrlInNewTab()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('用户角色')
                    ->badge()
                    ->separator(','),

                TextColumn::make('created_at')
                    ->label('注册时间')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('id')
                    ->label('用户 ID')
                    ->schema([
                        TextInput::make('value')
                            ->label('用户 ID')
                            ->integer(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereKey($data['value'])
                        : $query),

                Filter::make('name')
                    ->label('用户名')
                    ->schema([
                        TextInput::make('value')
                            ->label('用户名'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('name', 'like', "%{$data['value']}%")
                        : $query),

                Filter::make('email')
                    ->label('邮箱')
                    ->schema([
                        TextInput::make('value')
                            ->label('邮箱'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('email', 'like', "%{$data['value']}%")
                        : $query),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('编辑'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('删除所选'),
                ]),
            ]);
    }
}
