<?php

namespace App\Filament\Resources\Replies;

use App\Filament\Resources\Replies\Pages\CreateReply;
use App\Filament\Resources\Replies\Pages\EditReply;
use App\Filament\Resources\Replies\Pages\ListReplies;
use App\Filament\Resources\Replies\Schemas\ReplyForm;
use App\Filament\Resources\Replies\Tables\RepliesTable;
use App\Models\Reply;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use UnitEnum;

class ReplyResource extends Resource
{
    protected static ?string $model = Reply::class;

    protected static string|UnitEnum|null $navigationGroup = '内容管理';

    protected static ?string $navigationLabel = '回复管理';

    protected static ?string $modelLabel = '回复';

    protected static ?string $pluralModelLabel = '回复';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'content';

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
        return parent::getEloquentQuery()->with(['user', 'topic']);
    }

    public static function form(Schema $schema): Schema
    {
        return ReplyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RepliesTable::configure($table);
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
            'index' => ListReplies::route('/'),
            'create' => CreateReply::route('/create'),
            'edit' => EditReply::route('/{record}/edit'),
        ];
    }
}