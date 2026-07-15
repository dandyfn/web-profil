<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    // Mengikat resource ini ke Model Eloquent Blog
    protected static ?string $model = Blog::class;

    // Icon bawaan Filament (bisa diabaikan karena navigasi disembunyikan)
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    /**
     * 🛡️ MENYEMBUNYIKAN MENU "BLOGS" DI SIDEBAR KIRI FILAMENT
     * Kita return false karena kita memakai tombol navigasi manual [ DEPLOY NEW NODE ] dan [ EDIT NODE ]
     * di halaman publik agar pengalaman pengguna (UX) jauh lebih interaktif dan estetik.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    /**
     * ✍️ FORMULIR PENGISIAN DATA BLOG (ADMIN PANEL)
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        // Input Judul Artikel
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->lazy()
                            // Otomatis men-generate slug ramah SEO saat lu mengetik judul
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),

                        // Input Slug (URL unik artikel)
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        // Kategori Artikel (NETWORKING, LINUX MINT, CYBER SECURITY)
                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->maxLength(255),

                        // Deskripsi singkat untuk tampilan kartu di grid depan
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(255),

                        // 🚀 RICH EDITOR NATIVE FILAMENT (DENGAN DUKUNGAN LINK GAMBAR EKSTERNAL)
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            // Mengaktifkan attachFiles khusus untuk menyisipkan URL gambar pihak ketiga (Postimages)
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h1',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),

                        // 🌐 MENGGANTI FILE UPLOAD MENJADI TEXT INPUT URL (ANTI-GAGAL CLOUD STORAGE)
                        Forms\Components\TextInput::make('image')
                            ->label('Banner Image URL')
                            ->placeholder('Masukkan URL gambar langsung (misal: https://imgur.com/xyz.png atau dari Postimages)')
                            ->maxLength(255)
                            ->nullable(),

                        // Tautan Video Youtube Demonstrasi Lab (Jika ada)
                        Forms\Components\TextInput::make('video_url')
                            ->url()
                            ->placeholder('https://www.youtube.com/embed/...')
                            ->nullable(),

                        // Tautan Berkas Latihan / Download Modul / File .PKT Packet Tracer
                        Forms\Components\TextInput::make('source_link')
                            ->url()
                            ->placeholder('Link referensi luar (Drive, Github, dll)')
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    /**
     * 📊 TABEL REKAP DATA BLOG DI BELAKANG LAYAR (DATABASE STORAGE INDEX)
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('views')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
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

    /**
     * 🛣️ DAFTAR RUTE SUB-HALAMAN RESOURCE BLOG
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
