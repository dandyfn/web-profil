<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Achievements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->lazy()
                            ->afterStateUpdated(function ($set, $state, $operation) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->placeholder('Network Engineering / Programming')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('issued_label')
                            ->label('Tanggal terbit')
                            ->placeholder('Issued: 4 September 2025')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('credential_url')
                            ->label('Link verifikasi sertifikat')
                            ->url()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('badge')
                            ->label('Gambar badge / sertifikat')
                            ->image()
                            ->disk('s3')
                            ->directory('achievement-badges')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('skills')
                            ->label('Core skills (tekan Enter setiap skill)')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('quote')
                            ->label('Kutipan')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('attachments')
                            ->label('Lampiran / dokumentasi (gambar)')
                            ->image()
                            ->multiple()
                            ->disk('s3')
                            ->directory('achievement-attachments')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan tampil (angka kecil tampil lebih dulu)')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('badge')->square(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->searchable(),
                Tables\Columns\TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
