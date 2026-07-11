<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center gap-3">
            <x-filament::button type="submit">
                保存设置
            </x-filament::button>

            <x-filament::button type="button" color="gray" wire:click="clearCache" wire:loading.attr="disabled">
                更新系统缓存
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>