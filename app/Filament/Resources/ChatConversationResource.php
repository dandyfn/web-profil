<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatConversationResource\Pages;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChatConversationResource extends Resource
{
    protected static ?string $model = ChatConversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Chat';

    protected static ?string $modelLabel = 'Chat';

    protected static ?string $pluralModelLabel = 'Chat';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ChatMessage::where('sender', 'visitor')->whereNull('read_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount([
                'messages as unread_count' => fn ($q) => $q->where('sender', 'visitor')->whereNull('read_at'),
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('visitor_name')
                    ->label('Pengunjung')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unread_count')
                    ->label('Belum dibaca')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('last_message_at')
                    ->label('Pesan terakhir')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('last_message_at', 'desc')
            ->recordUrl(fn ($record) => static::getUrl('chat', ['record' => $record]))
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Buka')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn ($record) => static::getUrl('chat', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChatConversations::route('/'),
            'chat' => Pages\ViewChat::route('/{record}/chat'),
        ];
    }
}
