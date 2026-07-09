<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('用户名')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('邮箱')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('密码')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),

                FileUpload::make('avatar')
                    ->label('用户头像')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('uploads/images/avatars')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(fn (string $file): ?array => blank($file) ? null : [
                        'name' => basename(parse_url($file, PHP_URL_PATH) ?: $file),
                        'size' => 0,
                        'type' => null,
                        'url' => Str::startsWith($file, ['http://', 'https://'])
                            ? $file
                            : rtrim(config('filesystems.disks.public.url'), '/') . '/' . ltrim($file, '/'),
                    ])
                    ->maxSize(2048),

                Textarea::make('introduction')
                    ->label('个人简介')
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('roles')
                    ->label('用户角色')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }
}
