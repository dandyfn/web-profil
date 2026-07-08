<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Logs - Cyberpunk Network Blog</title>
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
    </style>
</head>
<body class="bg-[#0b071e] text-gray-200 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Glowing Cursor Effect (Sync with Home) -->
    <div id="cursor-glow" class="fixed top-0 left-0 w-[600px] h-[600px] bg-gradient-to-r from-cyan-500/10 to-purple-600/10 rounded-full blur-[130px] pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 ease-out opacity-0 md:opacity-100"></div>

    <!-- NAVIGATION BAR -->
    <nav class="sticky top-0 z-50 bg-[#0b071e]/90 backdrop-blur-md border-b border-purple-500/30 shadow-[0_4px_20px_rgba(128,0,128,0.2)]">
        <div class="w-full md:pl-24 flex justify-between items-center py-5 px-8 gap-12 font-semibold tracking-wider text-base">
            <div class="flex gap-12">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-cyan-400 transition duration-300 flex items-center gap-2">
                    <span>←</span> RETURN TO MAIN NODE
                </a>
            </div>
            <div class="text-xs uppercase tracking-widest text-purple-400 font-mono hidden sm:block">
                Node: Archive_Database_Log
            </div>
        </div>
    </nav>

    <!-- CONTENT UTAMA -->
    <main class="w-full md:pl-24 pr-8 md:pr-16 py-12 flex-grow relative z-10 space-y-10">

        <!-- HEADER KATEGORI & FILTER -->
         <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-purple-500/20 pb-8">
            <div class="space-y-2">
                <span class="text-xs font-mono uppercase tracking-widest text-cyan-400">// ARCHIVE TERMINAL</span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-500 tracking-wide uppercase leading-tight">
                    KNOWLEDGE BASE LOGS
                </h1>
                <p class="text-gray-400 text-sm font-light max-w-xl">
                    Semua dokumentasi lab siber, konfigurasi routing, otomatisasi python, dan catatanku selama bereksplorasi di dunia infrastruktur jaringan.
                </p>
            </div>

            <!-- LIVE SEARCH BAR -->
            <div class="w-full md:w-80 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400 font-mono text-xs">
                    CMD>
                </span>
                <input type="text" id="search-input" placeholder="Cari judul log..." class="w-full bg-[#130d31]/50 border border-purple-500/30 rounded-xl pl-12 pr-4 py-3 text-cyan-300 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:shadow-[0_0_15px_rgba(6,182,212,0.2)] transition text-sm font-mono uppercase tracking-wider">
            </div>
        </div>

        <!-- GRID DAFTAR BLOG -->
        <div id="blog-grid" class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">

            <!-- SLOT TAMBAH BLOG BARU (Cyberpunk Create Card Slot) -->
            <a href="/admin/blogs/create" class="w-full min-h-[420px] bg-[#130d31]/10 border-2 border-dashed border-purple-500/30 backdrop-blur-md rounded-xl hover:border-cyan-400 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] active:scale-[0.98] transform transition duration-300 flex flex-col items-center justify-center p-8 group relative overflow-hidden">
                <!-- Efek Grid Halus di Dalam Card -->
                <div class="absolute inset-0 bg-[linear-gradient(rgba(18,10,50,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(18,10,50,0.1)_1px,transparent_1px)] bg-[size:20px_20px] opacity-30 pointer-events-none"></div>

                <div class="flex flex-col items-center justify-center space-y-6 relative z-10 text-center">
                    <!-- Lingkaran Pulsing Plus Icon -->
                    <div class="w-20 h-20 rounded-full border border-purple-500/40 flex items-center justify-center bg-[#130d31]/40 group-hover:border-cyan-400 group-hover:bg-cyan-950/20 shadow-[0_0_15px_rgba(168,85,247,0.1)] group-hover:shadow-[0_0_25px_rgba(6,182,212,0.3)] transition-all duration-300 animate-pulse">
                        <span class="text-4xl font-light text-purple-400 group-hover:text-cyan-300 transition duration-300">+</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-widest text-purple-400 group-hover:text-cyan-300 transition duration-300">// DEPLOY_SLOT</span>
                        <h4 class="font-bold text-gray-400 group-hover:text-gray-100 text-lg transition duration-300 mt-1 uppercase tracking-wider">
                            Deploy New Node
                        </h4>
                        <p class="text-xs text-gray-500 font-mono mt-2 group-hover:text-gray-400 transition duration-300 max-w-[200px] mx-auto leading-relaxed">
                            Hubungkan modul log artikel baru langsung ke database
                        </p>
                    </div>
                </div>
            </a>

            @forelse($blogs as $blog)
                <!-- Kartu Blog Dinamis -->
                <div class="blog-card w-full bg-[#130d31]/40 border border-purple-500/20 backdrop-blur-md rounded-xl overflow-hidden hover:border-cyan-400/50 hover:shadow-[0_0_25px_rgba(6,182,212,0.2)] active:scale-[0.98] transform transition duration-300 cursor-pointer flex flex-col justify-between group" data-title="{{ strtolower($blog->title) }}">
                    <a href="{{ route('blog.detail', ['slug' => $blog->slug]) }}" class="flex flex-col h-full justify-between">

                        <!-- Gambar Banner -->
                        <div class="h-48 overflow-hidden relative">
                            @if($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <img src="https://placehold.co/800x450/130d31/38bdf8?text=Cyber+Log" alt="Placeholder" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @endif
                            <div class="absolute top-3 left-3 px-3 py-1 text-xs font-mono font-bold uppercase rounded border border-cyan-500/30 text-cyan-400 bg-cyan-950/50">
                                {{ $blog->category }}
                            </div>
                        </div>

                        <!-- Konten Kartu -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 text-xs text-gray-500 font-mono mb-3">
                                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $blog->author }}</span>
                                </div>
                                <h4 class="font-bold text-gray-200 text-xl group-hover:text-cyan-400 transition duration-300 leading-snug line-clamp-2">
                                    {{ $blog->title }}
                                </h4>
                                <p class="text-sm text-gray-400 mt-3 leading-relaxed line-clamp-3">
                                    {{ $blog->description }}
                                </p>
                            </div>

                            <!-- Footer Kartu -->
                            <div class="mt-6 pt-4 border-t border-purple-500/10 flex items-center justify-between text-xs text-gray-500 font-mono">
                                <span>Views: {{ $blog->views }}</span>
                                <span class="text-cyan-400 group-hover:translate-x-2 transition duration-200">Read Log →</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <!-- Tampilan Jika Blog Masih Kosong -->
                <div class="col-span-3 text-center py-20 border border-dashed border-purple-500/20 rounded-xl">
                    <p class="text-gray-500 font-mono">// ARCHIVE SYSTEM COLD_START: NO LOGS DEPLOYED YET</p>
                </div>
            @endforelse
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#070414]/90 backdrop-blur-md border-t border-purple-900/40 py-10 px-8 text-center relative z-10 mt-20">
        <div class="w-full md:pl-24 pr-8 md:pr-16 flex flex-col sm:flex-row items-center justify-between gap-6">
            <p class="text-sm text-gray-500">&copy; 2026 Dandy. Built with Laravel on Linux Mint.</p>
            <div id="on-site-status" class="text-sm font-mono text-cyan-400">STATUS: NETWORK_ARCHIVE_STABLE</div>
        </div>
    </footer>

    <!-- JAVASCRIPT: GLOW & SEARCH FILTER -->
    <script>
        // Logika Kursor Glowing
        const glow = document.getElementById('cursor-glow');
        window.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;
            glow.style.left = `${x}px`;
            glow.style.top = `${y}px`;
        });

        // Logika Live Search Filter Cyberpunk
        const searchInput = document.getElementById('search-input');
        const blogCards = document.querySelectorAll('.blog-card');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();

            blogCards.forEach(card => {
                const title = card.getAttribute('data-title');
                if (title.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Muat identitas user dari home
        window.onload = function() {
            let savedUser = localStorage.getItem('cyberpunk_user') || 'GUEST';
            document.getElementById('on-site-status').textContent = `ON SITE : ${savedUser.toUpperCase()}`;
        }
    </script>

</body>
</html>
