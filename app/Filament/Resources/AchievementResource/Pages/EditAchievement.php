<?php

namespace App\Filament\Resources\AchievementResource\Pages;

use App\Filament\Resources\AchievementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAchievement extends EditRecord
{
    protected static string $resource = AchievementResource::class;

    // Badge lama (folder public) tidak ada di Supabase, jadi dikosongkan di form saja.
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $raw = $this->record->getRawOriginal('badge');

        $data['badge'] = ($raw && ! str_starts_with($raw, 'http') && ! str_starts_with($raw, 'images/'))
            ? $raw
            : null;

        return $data;
    }

    // Jika badge tidak diganti, pertahankan badge yang lama.
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['badge'])) {
            $data['badge'] = $this->record->getRawOriginal('badge');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
