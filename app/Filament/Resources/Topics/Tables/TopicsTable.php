<?php

namespace App\Filament\Resources\Topics\Tables;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('话题')
                    ->limit(42)
                    ->wrap()
                    ->searchable()
                    ->url(fn (Topic $record): ?string => $record->exists ? $record->link() : null)
                    ->openUrlInNewTab(),

                ImageColumn::make('user.avatar')
                    ->label('头像')
                    ->circular()
                    ->size(28),

                TextColumn::make('user.name')
                    ->label('作者')
                    ->placeholder('N/A')
                    ->url(fn (Topic $record): ?string => $record->user
                        ? route('users.show', $record->user)
                        : null)
                    ->openUrlInNewTab(),

                TextColumn::make('category.name')
                    ->label('分类')
                    ->placeholder('N/A')
                    ->url(fn (Topic $record): ?string => $record->category
                        ? CategoryResource::getUrl('edit', ['record' => $record->category])
                        : null),

                TextColumn::make('reply_count')
                    ->label('评论')
                    ->sortable(),

                TextColumn::make('view_count')
                    ->label('查看')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('id')
                    ->label('内容 ID')
                    ->schema([
                        TextInput::make('value')
                            ->label('内容 ID')
                            ->integer(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereKey($data['value'])
                        : $query),

                Filter::make('user_id')
                    ->label('用户')
                    ->schema([
                        Select::make('value')
                            ->label('用户')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => self::userSearchResults($search))
                            ->getOptionLabelUsing(fn ($value): ?string => self::userOptionLabel($value)),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('user_id', $data['value'])
                        : $query),

                Filter::make('category_id')
                    ->label('分类')
                    ->schema([
                        Select::make('value')
                            ->label('分类')
                            ->searchable()
                            ->options(fn (): array => self::categoryOptions()),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('category_id', $data['value'])
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

    private static function userSearchResults(string $search): array
    {
        return User::query()
            ->where(fn (Builder $query): Builder => $query
                ->where('name', 'like', "%{$search}%")
                ->when(is_numeric($search), fn (Builder $query): Builder => $query->orWhere('id', $search)))
            ->limit(50)
            ->get()
            ->mapWithKeys(fn (User $user): array => [$user->id => "{$user->id} {$user->name}"])
            ->all();
    }

    private static function userOptionLabel($value): ?string
    {
        $user = User::query()->find($value);

        return $user ? "{$user->id} {$user->name}" : null;
    }

    private static function categoryOptions(): array
    {
        return Category::query()
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn (Category $category): array => [$category->id => "{$category->id} {$category->name}"])
            ->all();
    }
}