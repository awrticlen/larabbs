<?php

namespace App\Filament\Resources\Replies\Tables;

use App\Filament\Resources\Topics\TopicResource;
use App\Models\Reply;
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

class RepliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('content')
                    ->label('内容')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),

                ImageColumn::make('user.avatar')
                    ->label('头像')
                    ->circular()
                    ->size(28),

                TextColumn::make('user.name')
                    ->label('作者')
                    ->placeholder('N/A')
                    ->url(fn (Reply $record): ?string => $record->user
                        ? route('users.show', $record->user)
                        : null)
                    ->openUrlInNewTab(),

                TextColumn::make('topic.title')
                    ->label('话题')
                    ->limit(42)
                    ->wrap()
                    ->placeholder('N/A')
                    ->url(fn (Reply $record): ?string => $record->topic
                        ? TopicResource::getUrl('edit', ['record' => $record->topic])
                        : null),
            ])
            ->filters([
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

                Filter::make('topic_id')
                    ->label('话题')
                    ->schema([
                        Select::make('value')
                            ->label('话题')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => self::topicSearchResults($search))
                            ->getOptionLabelUsing(fn ($value): ?string => self::topicOptionLabel($value)),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('topic_id', $data['value'])
                        : $query),

                Filter::make('content')
                    ->label('回复内容')
                    ->schema([
                        TextInput::make('value')
                            ->label('回复内容'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('content', 'like', "%{$data['value']}%")
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
                ->when(is_numeric($search), fn (Builder $query): Builder => $query->orWhereKey($search)))
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

    private static function topicSearchResults(string $search): array
    {
        return Topic::query()
            ->where(fn (Builder $query): Builder => $query
                ->where('title', 'like', "%{$search}%")
                ->when(is_numeric($search), fn (Builder $query): Builder => $query->orWhereKey($search)))
            ->limit(50)
            ->get()
            ->mapWithKeys(fn (Topic $topic): array => [$topic->id => "{$topic->id} {$topic->title}"])
            ->all();
    }

    private static function topicOptionLabel($value): ?string
    {
        $topic = Topic::query()->find($value);

        return $topic ? "{$topic->id} {$topic->title}" : null;
    }
}