<?php

namespace App\Filament\Resources\ChatConversationResource\Pages;

use App\Filament\Resources\ChatConversationResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ViewChat extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ChatConversationResource::class;

    protected static string $view = 'filament.resources.chat-conversation-resource.pages.view-chat';

    public string $reply = '';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return 'Chat: ' . $this->record->visitor_name;
    }

    public function send(): void
    {
        $text = trim($this->reply);

        if ($text === '') {
            return;
        }

        $this->record->messages()->create([
            'sender' => 'admin',
            'body' => mb_substr($text, 0, 1000),
        ]);

        $this->record->update(['last_message_at' => now()]);
        $this->reply = '';
    }

    protected function getViewData(): array
    {
        // Pesan pengunjung yang sedang dibuka otomatis ditandai sudah dibaca.
        $this->record->messages()
            ->where('sender', 'visitor')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return [
            'messages' => $this->record->messages()->orderBy('id')->get(),
        ];
    }
}
