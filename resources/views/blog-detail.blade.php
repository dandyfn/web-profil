@php
    // 1. Ambil URL IP/Domain aktif saat ini secara dinamis (contoh: http://localhost:8080 atau http://127.0.0.1:8080)
    $currentHost = request()->getSchemeAndHttpHost();

    // 2. Sapu bersih semua link lama yang masih mengunci port 8000 di database, ganti otomatis ke port aktif saat ini
    $cleanedContent = preg_replace(
        '/http:\/\/(127\.0\.0\.1|localhost):8000/',
        $currentHost,
        $blog->content,
    );

    // 🛠️ MANTRA AUTO-DETEKSI LINK MENTAH POSTIMAGES:
    // Jika kamu cuma paste text mentah seperti https://i.postimg.cc/... di editor,
    // script ini akan otomatis merubahnya menjadi tag <img> asli agar langsung muncul gambarnya!
    $cleanedContent = preg_replace(
        '/(?<!src=["\'])(https:\/\/i\.postimg\.cc\/[^\s<]+)/i',
        '<img src="$1" alt="Article Image">',
        $cleanedContent,
    );

    // 🛠️ TAMBAHAN FIX GAMBAR HANCUR DI KONTEN:
    // Jika di dalam isi konten artikel terdapat tag <img> yang link-nya mengarah ke lokal/storage padahal aslinya URL Postimages,
    // kita bersihkan otomatis agar langsung menembak URL asli Postimages (http:// atau https://).
    $cleanedContent = preg_replace_callback(
        '/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)/i',
        function ($matches) {
            $attributesBefore = $matches[1];
            $srcValue = $matches[2];
            $attributesAfter = $matches[3];

            // Jika di dalam string SRC mengandung link eksternal (Postimages) tapi terbungkus sub-path lokal, potong jalurnya!
            if (preg_replace('/.*(https?:\/\/postimg|.*postimages)/i', '$1', $srcValue) !== $srcValue) {
                $srcValue = preg_replace('/.*(https?:\/\/.*)/i', '$1', $srcValue);
            }

            return '<img' . $attributesBefore . 'src="' . $srcValue . '"' . $attributesAfter . '>';
        },
        $cleanedContent,
    );

    // 3. 🛡️ MANTRA BRUTAL SERVER-SIDE REGEX:
    // Kita langsung bedah tag <pre><code> lewat PHP di server!
    // Ini mematikan Javascript DOM Injector, sehingga DUPLIKASI TERMINAL DIJAMIN LENYAP SELAMANYA!
    // 3. 🛡️ MANTRA BRUTAL SERVER-SIDE REGEX (DENGAN AUTO LINE NUMBERING):
    // Memecah kode per baris secara dinamis agar baris sambungan bisa dibedakan dengan jelas!
    $cleanedContent = preg_replace_callback(
        '/<pre><code([^>]*)>(.*?)<\/code><\/pre>/s',
        function ($matches) {
            static $index = 0;
            $index++;
            $codeAttr = $matches[1];
            $codeContent = $matches[2];
            $idUnik = 'code-block-' . $index;

            // Pecah kode berdasarkan line break (\n)
            $lines = explode("\n", $codeContent);
            $formattedLines = '';
            $lineNumber = 1;

            foreach ($lines as $line) {
                // Abaikan baris kosong terakhir akibat explode
                if ($line === '' && end($lines) === $line) {
                    continue;
                }

                $formattedLines .= '<div class="code-line flex items-start" data-line="' . $lineNumber . '">';
                $formattedLines .= '<span class="line-number select-none text-purple-600/50 font-mono text-xs text-right pr-4 w-8 min-w-[2rem] block">' . $lineNumber . '</span>';
                $formattedLines .= '<span class="line-content flex-1">' . ($line === '' ? ' ' : $line) . '</span>';
                $formattedLines .= '</div>';
                $lineNumber++;
            }

            return '
<div class="relative bg-[#060411] border border-purple-500/30 rounded-xl overflow-hidden my-6">
<div class="terminal-header bg-[#130d31]/80 px-4 py-2 border-b border-purple-500/20 flex justify-between items-center text-xs font-mono text-purple-400 select-none">
<span>TERMINAL // LOG_CODE_BLOCK_' . $index . '</span>
<button type="button" onclick="copyCodeAction(\'' . $idUnik . '\', this)" class="bg-purple-900/40 text-purple-300 px-3 py-1 rounded hover:bg-cyan-500 hover:text-[#0b071e] transition-all duration-200 cursor-pointer relative z-50">COPY CODE</button>
</div>
<pre class="p-4 overflow-x-auto m-0 bg-transparent border-0"><code id="' . $idUnik . '"' . $codeAttr . ' class="block w-full">' . $formattedLines . '</code></pre>
</div>';
        },
        $cleanedContent,
    );
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta tag untuk mencegah browser menyimpan cache halaman ini (Sangat penting agar tombol edit langsung sinkron saat logout) -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>Dandy Al-Farisi Natanegara</title>
    <link rel="icon" type="image/png" href="{{ asset('images/fotologo.png') }}?v=2">
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

        /* 🚀 ATURAN UKURAN GAMBAR OTOMATIS & INTEGRASI CAPTION (SINKRON 70% DI DESKTOP, 100% DI HP) */
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

        /* 🔗 FIX TAMPILAN LINK PANJANG AGAR TIDAK MERUSAK LAYOUT PADA HP */
        .prose a {
            word-wrap: break-word !important;
            word-break: break-all !important;
            overflow-wrap: break-word !important;
        }

        /* Responsive Breakpoint: Aturan Penempatan Gambar & Lampiran Berkas secara presisi */
        @media (min-width: 768px) {
            /* 1. Gambar mandiri (tanpa figure / hasil Postimages mentah) dipaksa 70% dan pas di tengah */
            .prose img,
            .prose>img {
                width: 50% !important;
                margin: 2.5rem auto !important;
            }
            /* 2. Figure yang membungkus gambar (kita ciptakan wadah 70% di tengah) */
            .prose figure:has(img),
            .prose figure.attachment--preview {
                width: 70% !important;
                margin: 2.5rem auto !important;
                text-align: center !important;
            }
            /* 3. Maksa gambar di dalam figure agar berukuran penuh 100% dari wadah 70%-nya (supaya sinkron dengan caption) */
            .prose figure img {
                width: 100% !important;
                margin: 0 auto !important;
            }
            /* 4. Lampiran berkas non-gambar (seperti file PDF, Word, PPT) dipaksa tetap lebar 100% dan rata kiri */
            .prose figure:not(:has(img)) {
                width: 100% !important;
                margin: 2rem 0 !important;
                text-align: left !important;
            }
        }

        @media (max-width: 767px) {
            .prose img,
            .prose figure {
                width: 100% !important;
                margin: 1.5rem auto !important;
            }
        }

        /* 🎨 CAPTION / KETERANGAN GAMBAR (Dipaksa simetris tepat di tengah bawah gambar) */
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

        /* Teks keterangan lampiran non-gambar harus tetap mengikuti struktur rata kiri */
        .prose figure:not(:has(img)) figcaption,
        .prose figure:not(:has(img)) .attachment__caption {
            text-align: left !important;
            margin-left: 0 !important;
        }

        /* 🚀 PERBAIKAN UKURAN HEADING 1, 2, DAN 3 (DIPAKSA BERBEDA & TAMPIL TEBAL) */
        .prose h1,
        .prose h1 * {
            font-size: 2.25rem !important; /* text-4xl */
            color: #22d3ee !important; /* Cyan */
            font-weight: 700 !important;
            margin-top: 2.5rem !important;
            margin-bottom: 1.25rem !important;
            display: block !important;
            text-shadow: 0 0 10px rgba(34, 211, 238, 0.35) !important;
        }

        .prose h2,
        .prose h2 * {
            font-size: 1.875rem !important; /* text-3xl */
            color: #22d3ee !important; /* Cyan */
            font-weight: 700 !important;
            margin-top: 2rem !important;
            margin-bottom: 1rem !important;
            display: block !important;
            text-shadow: 0 0 8px rgba(34, 211, 238, 0.2) !important;
        }

        .prose h3,
        .prose h3 * {
            font-size: 1.5rem !important; /* text-2xl */
            color: #a78bfa !important; /* Ungu */
            font-weight: 600 !important;
            margin-top: 1.75rem !important;
            margin-bottom: 0.75rem !important;
            display: block !important;
            text-shadow: 0 0 8px rgba(167, 139, 250, 0.2) !important;
        }

        /* 🚀 CYBERPUNK SYNTAX HIGHLIGHTING & ANTI-BREAK LAYOUT SMARTPHONE */
        .prose pre {
            background-color: #060411 !important;
            border: 1px solid rgba(168, 85, 247, 0.4) !important;
            border-radius: 0.75rem !important;
            padding: 0 !important;
            /* Pastikan kontainer pre juga mengizinkan pembungkusan */
            white-space: pre-wrap !important;
        }

        /* Tembak langsung selector pre code di dalam struktur terminal buatan kita */
        .prose div pre code,
        .prose pre code[id^="code-block-"],
        .prose [id^="code-block-"] {
            color: #e2e8f0 !important;
            font-family: 'Fira Code', monospace !important;
            /* Paksa pembungkusan baris baru di level terdalam */
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-all !important;
            display: block !important;
            /* Trik Identasi Menggantung agar baris sambungan menjorok ke dalam */
            padding-left: 1.5rem !important;
            text-indent: -1.5rem !important;
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

    <!-- 🛡️ HANCURKAN PERISAI HANTU: Glow dipaksa ke dasar terdalam z-[-10] agar mustahil menghalangi klik jarimu! -->
    <div id="cursor-glow" class="fixed top-0 left-0 w-[600px] h-[600px] bg-gradient-to-r from-cyan-500/10 to-purple-600/10 rounded-full blur-[130px] pointer-events-none z-[-10] -translate-x-1/2 -translate-y-1/2 transition-all duration-300 ease-out opacity-0 md:opacity-100" style="z-index: -10 !important;"></div>

    <!-- Toast Notification for "Copy Code" Success -->
    <div id="toast" class="fixed bottom-10 right-10 z-[100] transform translate-y-20 opacity-0 transition-all duration-300 bg-[#130d31] border border-cyan-400 text-cyan-300 px-6 py-3 rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.3)] font-mono text-sm flex items-center gap-3">
        <span class="text-emerald-400">✔</span> System: Operation success!
    </div>

    <!-- 🛡️ POSISI NAVIGASI SANGAT STABIL DI ATAS LAYOUT -->
    <nav class="sticky top-0 z-[9999] bg-[#0b071e]/90 backdrop-blur-md border-b border-purple-500/30 shadow-[0_4px_20px_rgba(128,0,128,0.2)]" style="position: sticky; z-index: 9999 !important;">
        <div class="w-full px-6 md:px-16 lg:px-24 flex justify-between items-center py-5 gap-12 font-semibold tracking-wider text-base">
            <div class="flex gap-12">
                <!-- Tombol Kembali Murni Tanpa Javascript Pengganggu & Kebal Cache -->
                <a href="/blog" class="text-gray-400 hover:text-cyan-400 transition duration-300 flex items-center gap-2 cursor-pointer" style="position: relative; z-index: 99999 !important;">
                    <span>←</span> BACK TO MAIN NODE
                </a>
            </div>
            <div class="text-xs uppercase tracking-widest text-purple-400 font-mono hidden sm:block">
                Node: Secure_Blog_Log
            </div>
        </div>
    </nav>

    <!-- CONTENT UTAMA (Lebar Penuh Sesuai Induk Atasnya) -->
    <main class="w-full px-6 md:px-16 lg:px-24 py-12 flex-grow relative z-10 space-y-10">

        <!-- BANNER IMAGE -->
        <div class="banner-container w-full h-64 md:h-[500px] rounded-2xl overflow-hidden border border-purple-500/20 relative shadow-[0_0_40px_rgba(0,0,0,0.4)] bg-[#130d31]/20">
            @if ($blog->image)
                <img src="{{ Str::startsWith($blog->image, ['http://', 'https://']) ? $blog->image : asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="banner-img w-full h-full object-cover">
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
            {!! $cleanedContent !!}
        </article>

        <!-- VIDEO DEMONSTRASI -->
        @if ($blog->video_url)
            <div class="pt-6 max-w-3xl mx-auto">
                <div class="aspect-video bg-black/60 border border-cyan-500/30 rounded-xl overflow-hidden shadow-[0_0_20px_rgba(6,182,212,0.1)]">
                    @php
                        $embedUrl = str_replace(
                            ['watch?v=', 'youtu.be/'],
                            ['embed/', 'youtube.com/embed/'],
                            $blog->video_url,
                        );
                    @endphp
                    <iframe class="w-full h-full" src="{{ $embedUrl }}" title="Lab Video Demo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        @endif

        <!-- DOKUMENTASI / REFERENSI DOWNLOAD -->
        @if ($blog->source_link)
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

        <!-- 💬 ================= SEKSI KOMENTAR (FULL-WIDTH SEPANJANG STRUKTUR UTAMA) ================= -->
        <div class="pt-12 border-t border-purple-500/20 space-y-8 w-full">
            <div class="flex items-center gap-2">
                <span class="text-purple-400 font-mono">//</span>
                <h3 class="text-2xl font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">
                    FEEDBACK_LOGS ({{ $blog->comments->count() }})
                </h3>
            </div>

            <!-- FORM KIRIM KOMENTAR -->
            <form action="{{ route('blog.comment.store', $blog->id) }}" method="POST" id="commentForm" onsubmit="saveCommentOwnership(event)" class="bg-[#130d31]/30 border border-purple-500/30 rounded-xl p-6 space-y-4 shadow-[0_0_15px_rgba(168,85,247,0.05)] w-full">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-mono uppercase text-purple-400 mb-1">Handle / Nama:</label>
                        <input type="text" name="name" id="commentorName" required placeholder="Ex: anonymous_user" class="w-full bg-[#060411] border border-purple-500/30 rounded-lg px-4 py-2 text-sm text-gray-200 focus:outline-none focus:border-cyan-400 focus:shadow-[0_0_10px_rgba(34,211,238,0.2)] transition duration-200">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-purple-400 mb-1">Isi Pesan:</label>
                        <textarea name="comment" rows="4" required placeholder="Tulis komentar kamu di sini..." class="w-full bg-[#060411] border border-purple-500/30 rounded-lg px-4 py-2 text-sm text-gray-200 focus:outline-none focus:border-cyan-400 focus:shadow-[0_0_10px_rgba(34,211,238,0.2)] transition duration-200"></textarea>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600/80 to-cyan-500/80 hover:from-purple-600 hover:to-cyan-500 text-white font-mono text-xs rounded-lg shadow-[0_0_10px_rgba(168,85,247,0.2)] transition duration-200 cursor-pointer">
                        EXECUTE_COMMENT_SUBMIT 🚀
                    </button>
                </div>
            </form>

            <!-- LIST DAFTAR KOMENTAR -->
            <div class="space-y-4 w-full">
                @forelse($blog->comments as $comment)
                    <div class="bg-[#060411]/60 border-l-2 border-purple-500/50 rounded-r-xl p-4 space-y-2 shadow-[0_2px_10px_rgba(0,0,0,0.3)] relative w-full group" id="comment-box-{{ $comment->id }}">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-cyan-400 font-mono">⚡ {{ $comment->name }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 font-mono">{{ $comment->created_at->diffForHumans() }}</span>

                                <!-- ACTION MANAGEMENT (Akan Muncul Lewat JS jika ini milik user tersebut) -->
                                <div class="hidden gap-2 comment-actions" data-id="{{ $comment->id }}">
                                    <button onclick="enableEditComment({{ $comment->id }})" class="text-yellow-500 hover:text-yellow-400 font-mono text-[11px] cursor-pointer bg-yellow-950/20 px-2 py-0.5 rounded border border-yellow-500/30">EDIT</button>
                                    <form action="{{ route('blog.comment.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah anda yakin ingin menghapus data log komentar ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-mono text-[11px] cursor-pointer bg-red-950/20 px-2 py-0.5 rounded border border-red-500/30">DELETE</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- TEKS KOMENTAR NORMAL -->
                        <p class="text-sm text-gray-300 leading-relaxed font-light whitespace-pre-line comment-text" id="text-{{ $comment->id }}">
                            {{ $comment->comment }}
                        </p>

                        <!-- FORM EDIT TERSEMBUNYI (MUNCUL JIKA KLIK EDIT) -->
                        <form action="{{ route('blog.comment.update', $comment->id) }}" method="POST" class="hidden space-y-2 pt-2 edit-form" id="edit-form-{{ $comment->id }}">
                            @csrf @method('PUT')
                            <textarea name="comment" rows="3" required class="w-full bg-[#130d31]/50 border border-yellow-500/40 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-yellow-400">{{ $comment->comment }}</textarea>
                            <div class="flex gap-2 justify-end">
                                <button type="button" onclick="cancelEditComment({{ $comment->id }})" class="px-3 py-1 bg-gray-800 text-gray-400 font-mono text-xs rounded hover:bg-gray-700 cursor-pointer">CANCEL</button>
                                <button type="submit" class="px-3 py-1 bg-yellow-600 text-white font-mono text-xs rounded hover:bg-yellow-500 cursor-pointer">UPDATE_LOG</button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-6 border border-dashed border-purple-500/20 rounded-xl text-gray-500 font-mono text-xs w-full">
                        // NO_LOGS_FOUND. Belum ada komentar di artikel ini. Be the first!
                    </div>
                @endforelse
            </div>
        </div>
        <!-- 💬 ================= END SEKSI KOMENTAR ================= -->

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

        // --- 2. FUNGSI COPY-TO-CLIPBOARD MANDIRI & AMAN ---
        function copyCodeAction(elementId, buttonElement) {
            const targetElement = document.getElementById(elementId);
            if (!targetElement) return;

            const codeText = targetElement.innerText;
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = codeText;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);

            const toast = document.getElementById('toast');
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            const originalText = buttonElement.innerText;
            buttonElement.innerText = "COPIED!";
            buttonElement.style.backgroundColor = "#10b981";
            buttonElement.style.color = "#070414";

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-20', 'opacity-0');
                buttonElement.innerText = originalText;
                buttonElement.style.backgroundColor = "";
                buttonElement.style.color = "";
            }, 2500);
        }

        // --- 3. MANAJEMEN KEPEMILIKAN KOMENTAR (EDIT & DELETE SECARA ANONYMOUS) ---
        // --- 3. MANAJEMEN KEPEMILIKAN KOMENTAR (PRESISI PER ID KOMENTAR) ---
        function saveCommentOwnership(event) {
            // Simpan nama terakhir ke localStorage agar user tidak capek ketik nama lagi nanti
            localStorage.setItem('saved_handle_name', document.getElementById('commentorName').value);

            // Catat bahwa browser ini sedang mengirim komentar baru (kita beri flag sementara)
            localStorage.setItem('just_submitted_comment', 'true');
        }

        // Jalankan pengecekan hak akses tombol edit/hapus sesaat setelah halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            // Auto fill nama jika sebelumnya pernah mengetik
            if(localStorage.getItem('saved_handle_name')) {
                document.getElementById('commentorName').value = localStorage.getItem('saved_handle_name');
            }

            // Ambil daftar ID komentar yang benar-benar milik browser ini
            let myCommentIds = JSON.parse(localStorage.getItem('my_owned_comment_ids')) || [];

            // FIX OTOMATIS: Jika user baru saja submit komentar, ambil ID komentar paling atas (terbaru) untuk diamankan
            if (localStorage.getItem('just_submitted_comment') === 'true') {
                let firstActionBox = document.querySelector('.comment-actions');
                if (firstActionBox) {
                    let newestId = parseInt(firstActionBox.getAttribute('data-id'));
                    if (!myCommentIds.includes(newestId)) {
                        myCommentIds.push(newestId);
                        localStorage.setItem('my_owned_comment_ids', JSON.stringify(myCommentIds));
                    }
                }
                localStorage.removeItem('just_submitted_comment'); // Reset flag
            }

            // loop semua tombol aksi di halaman, hanya tampilkan jika ID-nya terdaftar di localStorage browser ini
            document.querySelectorAll('.comment-actions').forEach(el => {
                let currentCommentId = parseInt(el.getAttribute('data-id'));

                if (myCommentIds.includes(currentCommentId)) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.remove('flex');
                    el.classList.add('hidden'); // Amankan total dari jangkauan orang asing!
                }
            });
        });

        function enableEditComment(id) {
            document.getElementById('text-' + id).classList.add('hidden');
            document.getElementById('edit-form-' + id).classList.remove('hidden');
        }

        function cancelEditComment(id) {
            document.getElementById('text-' + id).classList.remove('hidden');
            document.getElementById('edit-form-' + id).classList.add('hidden');
        }

        // --- 4. JAVASCRIPT PROTEKSI BACK BUTTON ANTI-CACHE ---
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
