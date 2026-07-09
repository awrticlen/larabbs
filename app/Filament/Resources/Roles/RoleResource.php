<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Identification;

    protected static string|UnitEnum|null $navigationGroup = '用户与权限';

    protected static ?string $navigationLabel = '角色管理';

    protected static ?string $modelLabel = '角色';

    protected static ?string $pluralModelLabel = '角色';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return Gate::allows('manage_users') ?? false;
    }

    public static function canCreate(): bool
    {
        return Gate::allows('manage_users') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('manage_users') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('manage_users') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Gate::allows('manage_users') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('manage_users') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}