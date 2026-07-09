<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|UnitEnum|null $navigationGroup = '内容管理';

    protected static ?string $navigationLabel = '分类管理';

    protected static ?string $modelLabel = '分类';

    protected static ?string $pluralModelLabel = '分类';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

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
        return (Gate::allows('manage_contents') ?? false)
            && (auth()->user()?->hasRole('Founder') ?? false);
    }

    public static function canDeleteAny(): bool
    {
        return (Gate::allows('manage_contents') ?? false)
            && (auth()->user()?->hasRole('Founder') ?? false);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('manage_contents') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}