<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor; // 🚀 1. IMPORT TIPTAP EDITOR DI ATAS SINI
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Menyembunyikan navigasi menu "Blogs" bawaan karena kita memakai tombol kustom
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->lazy()
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(255),

                        // 🌟 2. TIPTAP EDITOR SUDAH TERPASANG DI SINI (Menggantikan RichEditor lama)
                        TiptapEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->profile('default') // 🚀 DIUBAH KE 'default' AGAR TIDAK ERROR (Berisi H1-H6, Alignments, Strike, Tables, dll)
                            ->directory('blog-attachments'), // Folder upload gambar


                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('blog-banners')
                            ->nullable(),

                        Forms\Components\TextInput::make('video_url')
                            ->url()
                            ->placeholder('https://www.youtube.com/embed/...')
                            ->nullable(),

                        Forms\Components\TextInput::make('source_link')
                            ->url()
                            ->placeholder('Link referensi luar')
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category')->searchable(),
                Tables\Columns\TextColumn::make('views')->integer(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
