<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        /* Style tambahan agar gambar di dalam artikel tidak meluap & rapi */
        .prose img {
            border-radius: 12px;
            border: 1px solid rgba(168, 85, 247, 0.2);
            margin: 2rem auto;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="bg-[#0b071e] text-gray-200 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Glowing Cursor Effect (Sync with Home) -->
    <div id="cursor-glow" class="fixed top-0 left-0 w-[600px] h-[600px] bg-gradient-to-r from-cyan-500/10 to-purple-600/10 rounded-full blur-[130px] pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 ease-out opacity-0 md:opacity-100"></div>

    <!-- Toast Notification for "Copy Code" Success -->
    <div id="toast" class="fixed bottom-10 right-10 z-[100] transform translate-y-20 opacity-0 transition-all duration-300 bg-[#130d31] border border-cyan-400 text-cyan-300 px-6 py-3 rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.3)] font-mono text-sm flex items-center gap-3">
        <span class="text-emerald-400">✔</span> System: Code copied to clipboard!
    </div>

    <!-- NAVIGATION BAR -->
    <nav class="sticky top-0 z-50 bg-[#0b071e]/90 backdrop-blur-md border-b border-purple-500/30 shadow-[0_4px_20px_rgba(128,0,128,0.2)]">
        <div class="w-full md:pl-24 flex justify-between items-center py-5 px-8 gap-12 font-semibold tracking-wider text-base">
            <div class="flex gap-12">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-cyan-400 transition duration-300 flex items-center gap-2">
                    <span>←</span> BACK TO MAIN NODE
                </a>
            </div>
            <div class="text-xs uppercase tracking-widest text-purple-400 font-mono hidden sm:block">
                Node: Secure_Blog_Log
            </div>
        </div>
    </nav>

    <!-- CONTENT UTAMA -->
    <main class="w-full md:pl-24 pr-8 md:pr-16 py-12 flex-grow relative z-10 max-w-4xl mx-auto space-y-10">

        <!-- BACK TO LIST BUTTON -->
        <a href="{{ route('home') }}" class="text-gray-400 hover:text-cyan-400 font-mono text-sm transition flex items-center gap-2">
            ← BACK TO BERANDA
        </a>

        <!-- BANNER IMAGE -->
        <div class="w-full h-64 md:h-96 rounded-2xl overflow-hidden border border-purple-500/20 relative shadow-[0_0_40px_rgba(0,0,0,0.4)]">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
            @else
                <img src="https://placehold.co/1200x600/130d31/38bdf8?text=System+Logs" alt="Placeholder banner" class="w-full h-full object-cover">
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

            <h1 class="text-3xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-500 leading-tight">
                {{ $blog->title }}
            </h1>

            <div class="flex items-center gap-3 pt-2">
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

        <!-- ARTIKEL UTAMA (Mendukung HTML & Gambar dari Rich Editor) -->
        <article class="prose prose-invert max-w-none text-gray-300 text-lg leading-relaxed space-y-6">
            {!! $blog->content !!}
        </article>

        <!-- SUB-SEKSI: VIDEO DEMO (Hanya muncul jika di-input di Filament) -->
        @if($blog->video_url)
            <div class="space-y-4 pt-6">
                <h4 class="text-lg font-bold font-mono text-cyan-400">// VIDEO DEMONSTRASI LAB</h4>
                <div class="aspect-video bg-black/60 border border-cyan-500/30 rounded-xl overflow-hidden shadow-[0_0_20px_rgba(6,182,212,0.1)]">
                    @php
                        // Deteksi dan ubah link youtube biasa agar bisa di-embed sempurna di iframe
                        $embedUrl = str_replace(['watch?v=', 'youtu.be/'], ['embed/', 'youtube.com/embed/'], $blog->video_url);
                    @endphp
                    <iframe class="w-full h-full" src="{{ $embedUrl }}" title="Lab Video Demo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        @endif

        <!-- SUB-SEKSI: SUMBER REFERENSI / FILE DOWNLOAD -->
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
        <div class="w-full md:pl-24 pr-8 md:pr-16 flex flex-col sm:flex-row items-center justify-between gap-6">
            <p class="text-sm text-gray-500">&copy; 2026 Dandy. Built with Laravel on Linux Mint.</p>
            <div class="text-sm font-mono text-gray-600">STATUS: SECURE_BLOG_NODE_STABLE</div>
        </div>
    </footer>

    <!-- JAVASCRIPT: EFEK KURSOR GLOWING & DETEKTOR AUTO COPY-CODE -->
    <script>
        // --- 1. Logika Kursor Glowing ---
        const glow = document.getElementById('cursor-glow');
        window.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;
            glow.style.left = `${x}px`;
            glow.style.top = `${y}px`;
        });

        // --- 2. JAVASCRIPT DETEKTOR KOTAK KODE (Code Block) DAN TOMBOL COPY ---
        document.addEventListener("DOMContentLoaded", function() {
            // Deteksi semua elemen pre/code yang dihasilkan oleh Rich Editor
            document.querySelectorAll('article pre').forEach((preBlock, index) => {
                // Beri bungkus style cyberpunk di preBlock
                preBlock.className = "relative bg-[#060411] border border-purple-500/30 rounded-xl overflow-hidden my-6";

                const codeBlock = preBlock.querySelector('code');
                const idUnik = 'code-block-' + index;
                if(codeBlock) {
                    codeBlock.id = idUnik;
                    codeBlock.className = "font-mono text-sm text-green-400 block p-6 overflow-x-auto";
                }

                // Buat Header Kontrol Terminal Cyberpunk di atas kode
                const headerTerminal = document.createElement('div');
                headerTerminal.className = "bg-[#130d31]/80 px-4 py-2 border-b border-purple-500/20 flex justify-between items-center text-xs font-mono text-purple-400";
                headerTerminal.innerHTML = `
                    <span>TERMINAL // LOG_CODE_BLOCK_${index + 1}</span>
                    <button onclick="copyCodeAction('${idUnik}', this)" class="bg-purple-900/40 text-purple-300 px-3 py-1 rounded hover:bg-cyan-500 hover:text-[#0b071e] transition-all duration-200">COPY CODE</button>
                `;

                // Pasang header di atas element <pre>
                preBlock.parentNode.insertBefore(headerTerminal, preBlock);
            });
        });

        // --- 3. FUNGSI COPY-TO-CLIPBOARD NYATA ---
        function copyCodeAction(elementId, buttonElement) {
            const codeText = document.getElementById(elementId).innerText;

            // Menggunakan API Clipboard Browser Modern
            navigator.clipboard.writeText(codeText).then(() => {
                // Munculkan toast sukses di kanan bawah
                const toast = document.getElementById('toast');
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');

                // Animasi visual umpan balik tombol
                const originalText = buttonElement.innerText;
                buttonElement.innerText = "COPIED!";
                buttonElement.style.backgroundColor = "#10b981"; // Hijau Emerald
                buttonElement.style.color = "#070414";

                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0');

                    buttonElement.innerText = originalText;
                    buttonElement.style.backgroundColor = "";
                    buttonElement.style.color = "";
                }, 2500);
            });
        }
    </script>

</body>
</html>
