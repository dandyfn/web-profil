<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     * Ini akan menyelesaikan error MassAssignmentException secara permanen.
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'content',
        'image',
        'video_url',
        'source_link',
        'views',
        'author',
    ];

    public function comments() {
    return $this->hasMany(Comment::class, 'post_id')->latest(); // Otomatis urut dari yang terbaru
}

}
