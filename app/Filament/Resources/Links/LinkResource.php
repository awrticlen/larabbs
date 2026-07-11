<?php

namespace App\Filament\Resources\Links;

use App\Filament\Resources\Links\Pages\CreateLink;
use App\Filament\Resources\Links\Pages\EditLink;
use App\Filament\Resources\Links\Pages\ListLinks;
use App\Filament\Resources\Links\Schemas\LinkForm;
use App\Filament\Resources\Links\Tables\LinksTable;
use App\Models\Link;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static string|UnitEnum|null $navigationGroup = '站点管理';

    protected static ?string $navigationLabel = '资源推荐';

    protected static ?string $modelLabel = '资源推荐';

    protected static ?string $pluralModelLabel = '资源推荐';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        return static::isFounder();
    }

    public static function canCreate(): bool
    {
        return static::isFounder();
    }

    public static function canEdit($record): bool
    {
        return static::isFounder();
    }

    public static function canDelete($record): bool
    {
        return static::isFounder();
    }

    public static function canDeleteAny(): bool
    {
        return static::isFounder();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::isFounder();
    }

    public static function form(Schema $schema): Schema
    {
        return LinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'edit' => EditLink::route('/{record}/edit'),
        ];
    }

    private static function isFounder(): bool
    {
        return auth()->user()?->hasRole('Founder') ?? false;
    }
}