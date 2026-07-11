<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Artisan;
use UnitEnum;

class SiteSettings extends Page
{
    protected static ?string $title = '站点设置';

    protected static string|UnitEnum|null $navigationGroup = '站点管理';

    protected static ?string $navigationLabel = '站点设置';

    protected static ?string $slug = 'settings/site';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $data = SiteSetting::current()->only([
            'site_name',
            'contact_email',
            'seo_description',
            'seo_keyword',
        ]);

        $data['site_name'] = $this->withoutPoweredBySuffix($data['site_name'] ?? '');

        $this->form->fill($data);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Founder') ?? false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model(SiteSetting::current())
            ->components([
                TextInput::make('site_name')
                    ->label('站点名称')
                    ->required()
                    ->maxLength(50)
                    ->validationMessages([
                        'required' => '请填写站点名称。',
                    ]),

                TextInput::make('contact_email')
                    ->label('联系人邮箱')
                    ->email()
                    ->maxLength(50)
                    ->validationMessages([
                        'email' => '请填写正确的联系人邮箱格式。',
                    ]),

                Textarea::make('seo_description')
                    ->label('SEO - Description')
                    ->maxLength(250)
                    ->rows(4)
                    ->columnSpanFull(),

                Textarea::make('seo_keyword')
                    ->label('SEO - Keywords')
                    ->maxLength(250)
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['site_name'] = $this->withPoweredBySuffix($data['site_name']);

        SiteSetting::current()->update($data);

        Notification::make()
            ->success()
            ->title('站点设置已保存')
            ->send();
    }

    public function clearCache(): void
    {
        Artisan::call('cache:clear');
        SiteSetting::forgetCache();

        Notification::make()
            ->success()
            ->title('系统缓存已更新')
            ->send();
    }

    private function withPoweredBySuffix(string $siteName): string
    {
        return str_contains($siteName, 'Powered by LaraBBS')
            ? $siteName
            : "{$siteName} - Powered by LaraBBS";
    }

    private function withoutPoweredBySuffix(string $siteName): string
    {
        return str_ends_with($siteName, ' - Powered by LaraBBS')
            ? substr($siteName, 0, -strlen(' - Powered by LaraBBS'))
            : $siteName;
    }
}