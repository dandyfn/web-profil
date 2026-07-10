<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor; // 🚀 Import TipTap Editor untuk Rich Content (Alignments, Headings H1-H6, Tables, Coret Tengah)
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    // Mengikat resource ini ke Model Eloquent Blog
    protected static ?string $model = Blog::class;

    // Icon bawaan Filament (bisa diabaikan karena navigasi disembunyikan)
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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

                        // 🌟 TIPTAP EDITOR: Kanvas utama penulisan artikel berukuran luas (500px+)
                        TiptapEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->profile('default') // Menggunakan setelan default lengkap (H1-H6, alignment teks, dll)
                            ->directory('blog-attachments'), // Folder penyimpanan upload gambar/file pendukung

                        // Upload Gambar Banner Utama Artikel
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('blog-banners')
                            ->nullable(),

                        // Tautan Video Youtube Demonstrasi Lab (Jika ada)
                        Forms\Components\TextInput::make('video_url')
                            ->url()
                            ->placeholder('https://www.youtube.com/embed/...')
                            ->nullable(),

                        // Tautan Berkas Latihan / Download Modul / File .PKT Packet Tracer
                        Forms\Components\TextInput::make('source_link')
                            ->url()
                            ->placeholder('Link referensi luar')
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
