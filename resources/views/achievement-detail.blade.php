@php
    // Helper untuk membersihkan path absolut lokal komputer agar bisa dibaca browser sebagai aset web
    $cleanPath = function($path) {
        if (empty($path)) return '';

        // Bersihkan prefix directory absolut Linux jika terdeteksi
        $absolutePublicPath = '/home/dandy/web-profil/public';
        if (str_starts_with($path, $absolutePublicPath)) {
            $path = str_replace($absolutePublicPath, '', $path);
        }

        // Jika jalurnya sudah diawali http/https, gunakan langsung. Jika tidak, gunakan helper asset() laravel
        return (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) ? $path : asset($path);
    };

    // Bersihkan URL badge utama
    $badgeUrl = $cleanPath($data['badge']);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dandy Al-Farisi Natanegara</title>
    <link rel="icon" type="image/png" href="{{ asset('images/fotologo.png') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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

    <div id="cursor-glow" class="fixed top-0 left-0 w-[600px] h-[600px] bg-gradient-to-r from-cyan-500/10 to-purple-600/10 rounded-full blur-[130px] pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 ease-out opacity-0 md:opacity-100"></div>

    <nav class="sticky top-0 z-50 bg-[#0b071e]/90 backdrop-blur-md border-b border-purple-500/30 shadow-[0_4px_20px_rgba(128,0,128,0.2)]">
        <div class="w-full md:pl-24 flex justify-between items-center py-5 px-8 gap-12 font-semibold tracking-wider text-base">
            <div class="flex gap-12">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-cyan-400 transition duration-300 flex items-center gap-2">
                    <span>←</span> BACK TO HOME
                </a>
            </div>
            <div class="text-xs uppercase tracking-widest text-purple-400 font-mono hidden sm:block">
                Node: Achievement_Detail_Log
            </div>
        </div>
    </nav>

    <main class="w-full px-6 md:pl-24 md:pr-16 py-12 flex-grow relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 w-full items-start">

            <!-- LEFT CONTAINER: Badge Preview, Button, and Documentations -->
            <div class="lg:col-span-5 flex flex-col gap-6 sticky top-24">

                <!-- Badge Certificate Cover -->
                <div class="bg-[#130d31]/40 border border-purple-500/30 backdrop-blur-md rounded-xl p-4 shadow-[0_0_30px_rgba(168,85,247,0.1)] group relative overflow-hidden">
                    <div class="absolute top-3 right-3 bg-cyan-500/20 text-cyan-300 font-mono text-xs px-3 py-1 rounded border border-cyan-500/30 z-10">
                        OFFICIAL BADGE
                    </div>
                    <!-- Membaca path gambar badge yang telah dibersihkan secara cerdas -->
                    <img src="{{ $badgeUrl }}" alt="{{ $data['title'] }} Badge" class="w-full h-auto rounded-lg object-cover filter drop-shadow-[0_0_15px_rgba(6,182,212,0.3)] group-hover:scale-[1.02] transition duration-300">
                </div>

                <!-- Credential Verification Button -->
                <a href="{{ $data['credential_url'] }}" target="_blank" class="w-full py-4 bg-gradient-to-r from-purple-600 to-cyan-500 text-white font-semibold text-center rounded-xl shadow-[0_0_20px_rgba(168,85,247,0.3)] hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transform hover:-translate-y-1 transition duration-200 tracking-wider flex items-center justify-center gap-3 group">
                    <span>🌐</span> VERIFY CREDENTIALS
                    <span class="text-xs text-cyan-200 group-hover:translate-x-1 transition duration-200">➔</span>
                </a>

                <!-- Attachment / Lab Documentation Gallery -->
                <div class="bg-[#130d31]/20 border border-purple-500/10 backdrop-blur-md rounded-xl p-5">
                    <h5 class="text-sm font-semibold tracking-widest text-purple-400 uppercase mb-4 font-mono">Attachment / Documentation</h5>
                    <div class="grid grid-cols-3 gap-3">
                        @if(!empty($data['attachments']))
                            @foreach($data['attachments'] as $attachment)
                                @php
                                    $attachmentUrl = $cleanPath($attachment['url']);
                                @endphp
                                <div class="aspect-video bg-[#1a1242]/50 rounded-lg border border-purple-500/20 overflow-hidden cursor-pointer hover:border-cyan-400 transition relative flex items-center justify-center">
                                    @if($attachment['type'] === 'video')
                                        <span class="absolute text-cyan-400 text-lg z-10">▶</span>
                                        <img src="https://via.placeholder.com/300x200/1a1242/000?text=LAB+DEMO" class="w-full h-full object-cover opacity-40" alt="Video Placeholder">
                                    @else
                                        <img src="{{ $attachmentUrl }}" class="w-full h-full object-cover" alt="Documentation image">
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-xs text-gray-500 col-span-3">No attachments logged for this node.</p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- RIGHT CONTAINER: Metadata info, Title, description, skills, quote -->
            <div class="lg:col-span-7 bg-[#130d31]/20 border border-purple-500/20 backdrop-blur-md rounded-2xl p-8 lg:p-10 shadow-[0_0_40px_rgba(0,0,0,0.3)]">

                <!-- Metadata Tags -->
                <div class="flex flex-wrap gap-3 items-center mb-6">
                    <span class="px-3 py-1 bg-purple-900/40 text-purple-300 text-xs font-mono uppercase tracking-wider rounded border border-purple-500/30">
                        {{ $data['category'] }}
                    </span>
                    <span class="px-3 py-1 bg-cyan-900/40 text-cyan-300 text-xs font-mono uppercase tracking-wider rounded border border-cyan-500/30">
                        {{ $data['date'] }}
                    </span>
                </div>

                <!-- Dinamic Title -->
                <h1 class="text-3xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-500 tracking-wide uppercase leading-tight mb-8">
                    {{ $data['title'] }}
                </h1>

                <hr class="border-purple-500/20 mb-8">

                <!-- Prose Content (Main Description) -->
                <div class="prose prose-invert max-w-none text-gray-400 text-lg leading-relaxed space-y-6">
                    <p class="text-justify">
                        {{ $data['desc_1'] }}
                    </p>

                    <!-- Dynamic validated skills -->
                    <h3 class="text-xl font-semibold text-purple-300 font-mono pt-4 flex items-center gap-2">
                        <span>▹</span> Core Skills Validated:
                    </h3>
                    <ul class="list-disc list-inside space-y-2 pl-4 text-base">
                        @if(!empty($data['skills']))
                            @foreach($data['skills'] as $skill)
                                <li>{{ $skill }}</li>
                            @endforeach
                        @endif
                    </ul>

                    <!-- Dynamic Quote Card -->
                    @if(!empty($data['quote']))
                        <p class="text-justify pt-4 text-base italic border-l-4 border-cyan-500/50 pl-4 bg-cyan-950/20 py-3 rounded-r-lg">
                            "{{ $data['quote'] }}"
                        </p>
                    @endif
                </div>

            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#070414]/90 backdrop-blur-md border-t border-purple-900/40 py-10 px-8 text-center relative z-10">
        <div class="w-full px-6 md:pl-24 md:pr-16 flex flex-col sm:flex-row items-center justify-between gap-6">
            <p class="text-sm text-gray-500">&copy; 2026 Dandy. Built with Laravel on Linux Mint.</p>
            <div class="text-sm font-mono text-gray-600">STATUS: SECURE_CONNECTION_ESTABLISHED</div>
        </div>
    </footer>

    <script>
        // Logika Kursor Glowing agar tetap sinkron dengan halaman depan
        const glow = document.getElementById('cursor-glow');
        window.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;
            glow.style.left = `${x}px`;
            glow.style.top = `${y}px`;
        });
    </script>

</body>
</html>
