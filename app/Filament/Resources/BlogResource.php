<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                        ->lazy() // Otomatis membuat slug saat judul diketik
                        ->afterStateUpdated(fn ($set, $state) => $set('slug', \Illuminate\Support\Str::slug($state))),

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

                    // Rich Text Editor gokil untuk tulis konten, upload foto di posisi acak, buat link file, dll.
                    Forms\Components\RichEditor::make('content')
                        ->required()
                        ->columnSpanFull()
                        ->fileAttachmentsDirectory('blog-attachments'), // Folder tempat foto artikel disimpan

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('blog-banners') // Folder tempat banner utama disimpan
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
                //
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
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
