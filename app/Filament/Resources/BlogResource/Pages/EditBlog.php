<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $raw = $this->record->getRawOriginal('image');

        // Jika banner lama berupa URL (Postimages), kosongkan agar bisa diganti dengan upload baru.
        $data['image'] = ($raw && ! str_starts_with($raw, 'http')) ? $raw : null;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return route('blog.index');
    }

    protected function getCancelRedirectUrl(): string
    {
        return route('blog.index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successRedirectUrl(route('blog.index')),
        ];
    }
}
