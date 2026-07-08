<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique(); // Untuk URL ramah SEO, misal: panduan-ospf
        $table->text('description'); // Deskripsi singkat kartu
        $table->longText('content'); // Isi artikel lengkap (HTML dari Rich Editor)
        $table->string('category');
        $table->string('image')->nullable(); // Alamat file gambar banner
        $table->string('video_url')->nullable(); // Link video youtube (jika ada)
        $table->string('source_link')->nullable(); // Link referensi luar
        $table->unsignedInteger('views')->default(0); // Counter pembaca otomatis
        $table->string('author')->default('Dandy Al-Farisi');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
