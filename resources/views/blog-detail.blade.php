<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta tag untuk mencegah browser menyimpan cache halaman ini (Sangat penting agar tombol edit langsung sinkron saat logout) -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>{{ $blog->title }} - Cyberpunk Log</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image:
                linear-gradient(rgba(18, 10, 50, 0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(18, 10, 50, 0.4) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* 📸 EFEK HOVER GLOWING & ZOOM UNTUK BANNER UTAMA */
        .banner-container {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .banner-container:hover {
            border-color: rgba(34, 211, 238, 0.8) !important; /* Cyan glow border */
            box-shadow: 0 0 35px rgba(6, 182, 212, 0.4) !important;
        }
        .banner-img {
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .banner-container:hover .banner-img {
            transform: scale(1.04); /* Efek zoom-in halus */
        }

        /* 📸 ATURAN UKURAN GAMBAR OTOMATIS (SINKRON 70% DI DESKTOP, 100% DI HP) */
        .prose img {
            border-radius: 12px;
            border: 1.5px solid rgba(168, 85, 247, 0.4);
            display: block;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        .prose img:hover {
            transform: translateY(-4px) scale(1.015); /* Melayang sedikit ke atas */
            border-color: rgba(6, 182, 212, 0.8) !important; /* Berubah jadi Cyan Glow */
            box-shadow: 0 12px 30px rgba(6, 182, 212, 0.35) !important;
        }

        /* Responsive Breakpoint: Memaksa gambar & figurnya selalu 70% di PC dan tengah halaman */
        @media (min-width: 768px) {
            .prose img {
                width: 70% !important;
                margin: 2.5rem auto !important;
            }
            /* Hanya menengahkan dan merubah ukuran figure yang memiliki gambar di dalamnya */
            .prose figure:has(img) {
                width: 70% !important;
                margin: 2.5rem auto !important;
                text-align: center !important;
            }
            /* Memaksa figure non-gambar (seperti link PDF/PPT) agar selebar 100% dan rata kiri */
            .prose figure:not(:has(img)) {
                width: 100% !important;
                margin: 2rem 0 !important;
                text-align: left !important;
            }
        }
        /* Mobile Breakpoint: Mengembang kembali 100% di layar HP agar jelas terbaca */
        @media (max-width: 767px) {
            .prose img {
                width: 100% !important;
                margin: 1.5rem auto !important;
            }
            .prose figure {
                width: 100% !important;
                margin: 1.5rem auto !important;
            }
        }

        /* 🚀 CAPTION GAMBAR SINKRONISASI PERSIS TENGAH DI BAWAH GAMBAR */
        .prose figcaption,
        .prose .attachment__caption {
            text-align: center !important;
            margin: 0.75rem auto 0 auto !important;
            color: #94a3b8 !important; /* Abu-abu siber terang */
            font-size: 0.875rem !important;
            font-style: italic !important;
            display: block !important;
            width: 100% !important;
        }

        /* Untuk link lampiran non-gambar, pastikan teks keterangannya tetap rata kiri */
        .prose figure:not(:has(img)) figcaption,
        .prose figure:not(:has(img)) .attachment__caption {
            text-align: left !important;
            margin-left: 0 !important;
        }

        /* 🚀 FORCE HEADING SIZES (Solusi Masalah Judul H1, H2, H3 yang Tidak Membesar & Berbeda Ukuran) */
        .prose h1, .prose h1 * {
            font-size: 2.25rem !important; /* text-4xl */
            color: #22d3ee !important;
            font-weight: 700 !important;
            margin-top: 2.5rem !important;
            margin-bottom: 1.25rem !important;
            text-shadow: 0 0 10px rgba(34, 211, 238, 0.3) !important;
        }

        .prose h2, .prose h2 * {
            font-size: 1.875rem !important; /* text-3xl */
            color: #22d3ee !important;
            font-weight: 700 !important;
            margin-top: 2rem !important;
            margin-bottom: 1rem !important;
            text-shadow: 0 0 10px rgba(34, 211, 238, 0.2) !important;
        }

        .prose h3, .prose h3 * {
            font-size: 1.5rem !important; /* text-2xl */
            color: #a78bfa !important;
            font-weight: 600 !important;
            margin-top: 1.75rem !important;
            margin-bottom: 0.75rem !important;
            text-shadow: 0 0 8px rgba(167, 139, 250, 0.2) !important;
        }

        /* 🚀 CYBERPUNK SYNTAX HIGHLIGHTING (Agar script/komentar // tidak berwarna hitam/invisible) */
        .prose pre {
            background-color: #060411 !important;
            border: 1px solid rgba(168, 85, 247, 0.4) !important;
            border-radius: 0.75rem !important;
            padding: 1.5rem !important; /* Padding langsung di dalam pre block agar bersih */
            margin: 1.5rem 0 !important;
        }

        .prose pre code {
            color: #e2e8f0 !important; /* Warna dasar teks kode (Putih Terang) */
            font-family: 'Fira Code', monospace !important;
        }

        /* Warna Komentar (Simbol // dan komentarnya dipaksa abu-abu agar terbaca jelas) */
        .prose pre code .hljs-comment,
        .prose pre code .hljs-quote {
            color: #94a3b8 !important; /* Slate gray terang, 100% terbaca di background gelap */
            font-style: italic !important;
        }

        /* Warna Keyword utama (php, composer, sudo, systemctl, dll) */
        .prose pre code .hljs-keyword,
        .prose pre code .hljs-selector-tag,
        .prose pre code .hljs-literal,
        .prose pre code .hljs-section,
        .prose pre code .hljs-link {
            color: #22d3ee !important; /* Cyan Neon */
            font-weight: bold !important;
        }

        /* Warna Atribut, Strings, dan Variabel */
        .prose pre code .hljs-string,
        .prose pre code .hljs-title,
        .prose pre code .hljs-name,
        .prose pre code .hljs-type,
        .prose pre code .hljs-attr,
        .prose pre code .hljs-attribute {
            color: #a78bfa !important; /* Ungu Neon */
        }

        /* Warna Angka dan Simbol */
        .prose pre code .hljs-number,
        .prose pre code .hljs-regexp,
        .prose pre code .hljs-symbol,
        .prose pre code .hljs-variable,
        .prose pre code .hljs-template-variable {
            color: #f472b6 !important; /* Pink Neon */
        }

        /* Warna Built-in */
        .prose pre code .hljs-built_in,
        .prose pre code .hljs-builtin-name {
            color: #38bdf8 !important; /* Biru Langit */
        }
    </style>
</head>
<body class="bg-[#0b071e] text-gray-200 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Glowing Cursor Effect -->
    <div id="cursor-glow" class="fixed top-0 left-0 w-[600px] h-[600px] bg-gradient-to-r from-cyan-500/10 to-purple-600/10 rounded-full blur-[130px] pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 ease-out opacity-0 md:opacity-100"></div>

    <!-- NAVIGATION BAR (Menggunakan model full-width tanpa batasan max-width) -->
    <nav class="sticky top-0 z-50 bg-[#0b071e]/90 backdrop-blur-md border-b border-purple-500/30 shadow-[0_4px_20px_rgba(128,0,128,0.2)]">
        <div class="w-full px-6 md:px-16 lg:px-24 flex justify-between items-center py-5 gap-12 font-semibold tracking-wider text-base">
            <div class="flex gap-12">
                <!-- Rute tombol dialihkan langsung ke rute list blog -->
                <a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-cyan-400 transition duration-300 flex items-center gap-2">
                    <span>←</span> BACK TO MAIN NODE
                </a>
            </div>
            <div class="text-xs uppercase tracking-widest text-purple-400 font-mono hidden sm:block">
                Node: Secure_Blog_Log
            </div>
        </div>
    </nav>

    <!-- CONTENT UTAMA (Di-set full-width menggunakan px-6 md:px-16 lg:px-24 untuk menghapus ruang kosong di monitor lebar) -->
    <main class="w-full px-6 md:px-16 lg:px-24 py-12 flex-grow relative z-10 space-y-10">

        <!-- BANNER IMAGE -->
        <div class="banner-container w-full h-64 md:h-[500px] rounded-2xl overflow-hidden border border-purple-500/20 relative shadow-[0_0_40px_rgba(0,0,0,0.4)] bg-[#130d31]/20">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="banner-img w-full h-full object-cover">
            @else
                <img src="https://placehold.co/1200x600/130d31/38bdf8?text=System+Logs" alt="Placeholder banner" class="banner-img w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b071e] via-transparent to-transparent"></div>
        </div>

        <!-- HEADER INFO -->
        <div class="space-y-4">
            <div class="flex flex-wrap gap-3 items-center">
                <span class="px-3 py-1 text-xs font-mono uppercase tracking-wider rounded border border-cyan-500/30 text-cyan-400 bg-cyan-950/20">
                    {{ $blog->category }}
                </span>
                <span class="text-xs font-mono text-gray-500">{{ $blog->created_at->format('d M Y') }}</span>
                <span class="text-xs font-mono text-gray-500">•</span>
                <span class="text-xs font-mono text-cyan-400">Views: {{ $blog->views }}</span>
            </div>

            <!-- 🚀 MASTERPIECE JUDUL UTAMA (1.4x LEBIH BESAR & GRADASI GRADIENT BARU ULTRA-Siber) -->
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 via-purple-500 to-cyan-400 leading-none tracking-tight pb-2">
                {{ $blog->title }}
            </h1>

            <div class="flex items-center gap-3 pt-4">
                <div class="w-10 h-10 rounded-full bg-[#130d31] border border-purple-500/30 flex items-center justify-center font-bold text-sm font-mono text-cyan-400">
                    DA
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-300">{{ $blog->author }}</p>
                    <p class="text-xs text-gray-500 font-mono">Network Engineer Admin</p>
                </div>
            </div>
        </div>

        <hr class="border-purple-500/20">

        <!-- ARTIKEL UTAMA -->
        <article class="prose prose-invert max-w-none text-gray-300 text-lg leading-relaxed space-y-6">
            {!! $blog->content !!}
        </article>

        <!-- SUB-SEKSI: VIDEO DEMONSTRASI (Dipusatkan secara simetris ke tengah halaman dengan mx-auto) -->
        @if($blog->video_url)
            <div class="pt-6 max-w-3xl mx-auto">
                <div class="aspect-video bg-black/60 border border-cyan-500/30 rounded-xl overflow-hidden shadow-[0_0_20px_rgba(6,182,212,0.1)]">
                    @php
                        $embedUrl = str_replace(['watch?v=', 'youtu.be/'], ['embed/', 'youtube.com/embed/'], $blog->video_url);
                    @endphp
                    <iframe class="w-full h-full" src="{{ $embedUrl }}" title="Lab Video Demo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        @endif

        <!-- SUB-SEKSI: DOKUMENTASI / REFERENSI DOWNLOAD -->
        @if($blog->source_link)
            <div class="bg-[#130d31]/40 border border-cyan-500/30 backdrop-blur-md rounded-xl p-6 shadow-[0_0_20px_rgba(6,182,212,0.05)] flex flex-col md:flex-row items-center justify-between gap-4 mt-8">
                <div class="space-y-1 text-center md:text-left">
                    <span class="text-xs font-mono uppercase text-cyan-400">// INTEGRATED_RESOURCES</span>
                    <h5 class="font-bold text-gray-200">Dokumentasi & Referensi Eksternal</h5>
                    <p class="text-xs text-gray-400 font-light">Gunakan tautan ini untuk mengunduh modul, file Packet Tracer (.pkt), atau membaca manual resmi.</p>
                </div>
                <a href="{{ $blog->source_link }}" target="_blank" class="px-5 py-3 bg-gradient-to-r from-purple-600 to-cyan-500 text-white font-mono text-xs rounded-lg shadow-[0_0_15px_rgba(168,85,247,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)] transform hover:-translate-y-0.5 transition duration-200">
                    BUKA TAUTAN REFERENSI
                </a>
            </div>
        @endif

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#070414]/90 backdrop-blur-md border-t border-purple-900/40 py-10 px-8 text-center relative z-10 mt-20">
        <div class="w-full px-6 md:px-16 lg:px-24 flex flex-col sm:flex-row items-center justify-between gap-6">
            <p class="text-sm text-gray-500">&copy; 2026 Dandy. Built with Laravel on Linux Mint.</p>
            <div class="text-sm font-mono text-gray-600">STATUS: SECURE_BLOG_NODE_STABLE</div>
        </div>
    </footer>

    <script>
        // --- 1. Logika Kursor Glowing ---
        const glow = document.getElementById('cursor-glow');
        window.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;
            glow.style.left = `${x}px`;
            glow.style.top = `${y}px`;
        });

        // --- 2. JAVASCRIPT PROTEKSI BACK BUTTON ANTI-CACHE ---
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

</body>
</html>
