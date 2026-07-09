<?php

namespace App\Filament\Resources\Topics;

use App\Filament\Resources\Topics\Pages\CreateTopic;
use App\Filament\Resources\Topics\Pages\EditTopic;
use App\Filament\Resources\Topics\Pages\ListTopics;
use App\Filament\Resources\Topics\Schemas\TopicForm;
use App\Filament\Resources\Topics\Tables\TopicsTable;
use App\Models\Topic;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use UnitEnum;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static string|UnitEnum|null $navigationGroup = '内容管理';

    protected static ?string $navigationLabel = '话题管理';

    protected static ?string $modelLabel = '话题';

    protected static ?string $pluralModelLabel = '话题';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function canCreate(): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'category']);
    }

    public static function form(Schema $schema): Schema
    {
        return TopicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TopicsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTopics::route('/'),
            'create' => CreateTopic::route('/create'),
            'edit' => EditTopic::route('/{record}/edit'),
        ];
    }
}