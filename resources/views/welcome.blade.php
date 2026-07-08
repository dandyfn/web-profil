<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Profil - Cyberpunk Network</title>
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
        /* Style Khusus untuk Neural Canvas */
        #neural-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0; /* Di belakang teks header */
            pointer-events: none; /* Tidak menghalangi interaksi teks/foto */
        }
    </style>
</head>
<body class="bg-[#0b071e] text-gray-200 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Efek Cahaya Neon Global Mengikuti Kursor -->
    <div id="cursor-glow" class="fixed top-0 left-0 w-[600px] h-[600px] bg-gradient-to-r from-cyan-500/10 to-purple-600/10 rounded-full blur-[130px] pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 ease-out opacity-0 md:opacity-100"></div>

    <!-- HEADER SECTION -->
    <header class="bg-gradient-to-b from-[#140e34]/80 to-[#0b071e]/95 pt-12 pb-10 px-8 border-b border-purple-900/30 backdrop-blur-sm relative z-10 overflow-hidden">

        <canvas id="neural-canvas"></canvas>

        <div class="w-full flex flex-col md:flex-row items-start justify-start gap-20 md:px-16 relative z-10">

            <div class="w-80 h-[26rem] flex-shrink-0 relative">
                <img src="{{ asset('images/foto profil.png') }}" alt="Foto Profil" class="w-full h-full object-cover rounded-none filter drop-shadow-[0_0_25px_rgba(0,234,255,0.45)]">
            </div>

            <div class="text-center md:text-left flex-grow w-full max-w-none pt-4">
                <h1 class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-500 tracking-wide uppercase leading-tight">
                    DANDY AL-FARISI NATANEGARA
                </h1>
                <h2 class="text-xl md:text-2xl font-semibold text-cyan-400 mt-4 tracking-widest uppercase">
                    NETWORK ENGINEERS, IT ENTHUSIASM
                </h2>
                <p class="text-gray-400 mt-6 text-lg md:text-xl leading-relaxed font-light text-justify md:text-left w-full max-w-none">
                    Network Enthusiast & Content Creator. Specializing in Network Infrastructures and Tech Education. Network Enthusiast & Content Creator. Specializing in Network Infrastructures and Tech Education. Network Enthusiast & Content Creator. Specializing in Network Infrastructures and Tech Education.
                </p>
            </div>

        </div>
    </header>

    <!-- NAVIGATION BAR -->
    <nav class="sticky top-0 z-50 bg-[#0b071e]/90 backdrop-blur-md border-b border-purple-500/30 shadow-[0_4px_20px_rgba(128,0,128,0.2)]">
        <div class="w-full md:pl-24 flex justify-start py-5 px-8 gap-12 font-semibold tracking-wider text-base">
            <a href="#" class="text-cyan-400 border-b-2 border-cyan-400 pb-1">HOME</a>
            <a href="#" class="text-gray-400 hover:text-purple-400 transition duration-300">BLOG</a>
        </div>
    </nav>

    <!-- CONTENT UTAMA -->
    <main class="w-full md:pl-24 pr-8 md:pr-16 py-16 flex-grow relative z-10">

        <!-- SEKSI: MY ACHIEVEMENT (Urut 4 Kolom di Layar PC, Klik Mengecil) -->
        <section class="mb-20 w-full">
            <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 mb-8 flex items-center gap-3">
                <span>🏆</span> My Achievement
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 w-full">
    @foreach(config('achievements') as $slug => $achievement)
        <!-- CARD DINAMIS (Otomatis me-looping semua isi config/achievements.php) -->
        <a href="{{ route('achievement.detail', ['slug' => $slug]) }}" class="w-full bg-[#130d31]/40 border border-purple-500/20 backdrop-blur-md rounded-xl p-6 hover:border-cyan-400/50 hover:shadow-[0_0_25px_rgba(6,182,212,0.2)] active:scale-95 transform transition duration-200 cursor-pointer flex flex-col justify-between group">
            <div>
                <!-- Mengambil Judul dari Config -->
                <h4 class="font-bold text-cyan-400 text-2xl group-hover:text-pink-400 transition duration-300">
                    {{ $achievement['title'] }}
                </h4>

                <!-- Mengambil Deskripsi dari Config (Diberi line-clamp agar tinggi box tetap rapi & seragam) -->
                <p class="text-lg text-gray-400 mt-3 leading-relaxed line-clamp-4">
                    {{ $achievement['desc_1'] }}
                </p>
            </div>
        </a>
    @endforeach
</div>
        </section>

        <!-- SEKSI: MY SKILLS & TOOLS (Dipecah per Sub-Kategori) -->
               <!-- SEKSI: MY SKILLS & TOOLS (Disesuaikan Warna Tiap Logo) -->
        <section class="mb-20">

            <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 mb-12">
                <span>⚙️</span> My Skills & Tools
            </h3>

            <!-- SUB-KATEGORI 1: COMPUTER NETWORKS -->
            <div class="mb-12">
                <h4 class="text-lg text-gray-400 uppercase tracking-widest mb-8">Computer Networks</h4>
                <div class="flex flex-wrap gap-5">

                    <!-- Cisco (Cyan) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-cyan-500/30 backdrop-blur-sm rounded-xl text-cyan-300 font-medium text-lg shadow-[0_0_15px_rgba(6,182,212,0.1)] hover:border-cyan-400 hover:shadow-[0_0_20px_rgba(6,182,212,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/cisco.png') }}" alt="Cisco" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/cisco.png';">
                        <span>Cisco</span>
                    </div>

                    <!-- Eve NG (Deep Pink) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-pink-500/30 backdrop-blur-sm rounded-xl text-pink-400 font-medium text-lg shadow-[0_0_15px_rgba(244,63,94,0.1)] hover:border-pink-400 hover:shadow-[0_0_20px_rgba(244,63,94,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/eveng.png') }}" alt="Eve NG" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(244,63,94,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/network.png';">
                        <span>Eve NG</span>
                    </div>

                    <!-- Wireshark (Sky Blue) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-blue-500/30 backdrop-blur-sm rounded-xl text-blue-300 font-medium text-lg shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:border-blue-400 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/wireshark.png') }}" alt="Wireshark" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(59,130,246,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/wireshark.png';">
                        <span>Wireshark</span>
                    </div>

                    <!-- GNS3 (Teal/Indigo) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-indigo-500/30 backdrop-blur-sm rounded-xl text-indigo-300 font-medium text-lg shadow-[0_0_15px_rgba(99,102,241,0.1)] hover:border-indigo-400 hover:shadow-[0_0_20px_rgba(99,102,241,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/gns3.png') }}" alt="GNS3" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(99,102,241,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/hub.png';">
                        <span>GNS3</span>
                    </div>

                    <!-- WinSCP (Slate Gray Theme) -->

                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-slate-500/30 backdrop-blur-sm rounded-xl text-slate-300 font-medium text-lg shadow-[0_0_15px_rgba(148,163,184,0.1)] hover:border-slate-400 hover:shadow-[0_0_20px_rgba(148,163,184,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/winscp.png') }}" alt="WinSCP" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(148,163,184,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/open-folder.png';">
                        <span>WinSCP</span>
                    </div>

                </div>
            </div>

            <!-- SUB-KATEGORI 2: TECH EDUCATION -->
            <div class="mb-12">
                <h4 class="text-lg text-gray-400 uppercase tracking-widest mb-8">Tech Education</h4>
                <div class="flex flex-wrap gap-5">

                    <!-- Moodle (Teal) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-teal-500/30 backdrop-blur-sm rounded-xl text-teal-300 font-medium text-lg shadow-[0_0_15px_rgba(20,184,166,0.1)] hover:border-teal-400 hover:shadow-[0_0_20px_rgba(20,184,166,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/moodle.png') }}" alt="Moodle" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(20,184,166,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/moodle.png';">
                        <span>Moodle</span>
                    </div>

                    <!-- Canva (Violet/Purple) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-violet-500/30 backdrop-blur-sm rounded-xl text-violet-300 font-medium text-lg shadow-[0_0_15px_rgba(139,92,246,0.1)] hover:border-violet-400 hover:shadow-[0_0_20px_rgba(139,92,246,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/canva.png') }}" alt="Canva" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/canva.png';">
                        <span>Canva</span>
                    </div>

                    <!-- GDevelop (Deep Purple) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-purple-500/30 backdrop-blur-sm rounded-xl text-purple-300 font-medium text-lg shadow-[0_0_15px_rgba(168,85,247,0.1)] hover:border-purple-400 hover:shadow-[0_0_20px_rgba(168,85,247,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/gdevelop.jpeg') }}" alt="GDevelop" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(168,85,247,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/google-play-game-services.png';">
                        <span>GDevelop</span>
                    </div>

                </div>
            </div>

            <!-- SUB-KATEGORI 3: PROGRAMMING & DATABASE -->
            <div class="mb-12">
                <h4 class="text-lg text-gray-400 uppercase tracking-widest mb-8">Programming & Database</h4>
                <div class="flex flex-wrap gap-5">

                    <!-- Java (Yellow/Gold) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-yellow-500/30 backdrop-blur-sm rounded-xl text-yellow-300 font-medium text-lg shadow-[0_0_15px_rgba(234,179,8,0.1)] hover:border-yellow-400 hover:shadow-[0_0_20px_rgba(234,179,8,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/java.png') }}" alt="Java" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(234,179,8,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/java-files.png';">
                        <span>Java</span>
                    </div>

                                        <!-- Laravel (Warm Coklat / Amber Theme) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-amber-600/30 backdrop-blur-sm rounded-xl text-amber-500 font-medium text-lg shadow-[0_0_15px_rgba(217,119,6,0.1)] hover:border-amber-500 hover:shadow-[0_0_20px_rgba(217,119,6,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/laravel.png') }}" alt="Laravel" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(217,119,6,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/fluency/48/laravel.png';">
                        <span>Laravel</span>
                    </div>

                    <!-- HTML CSS (Sky Blue) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-sky-400/30 backdrop-blur-sm rounded-xl text-sky-300 font-medium text-lg shadow-[0_0_15px_rgba(56,189,248,0.1)] hover:border-sky-400 hover:shadow-[0_0_20px_rgba(56,189,248,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/htmlcss.png') }}" alt="HTML CSS" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(56,189,248,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/html-5.png';">
                        <span>HTML CSS</span>
                    </div>

                    <!-- DBeaver (Indigo/Brown) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-indigo-400/30 backdrop-blur-sm rounded-xl text-indigo-300 font-medium text-lg shadow-[0_0_15px_rgba(99,102,241,0.1)] hover:border-indigo-400 hover:shadow-[0_0_20px_rgba(99,102,241,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/dbeaver.png') }}" alt="DBeaver" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(99,102,241,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/database.png';">
                        <span>DBeaver</span>
                    </div>

                    <!-- MySQL (Cyan) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-cyan-500/30 backdrop-blur-sm rounded-xl text-cyan-400 font-medium text-lg shadow-[0_0_15px_rgba(6,182,212,0.1)] hover:border-cyan-400 hover:shadow-[0_0_20px_rgba(6,182,212,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/mysql.png') }}" alt="MySQL" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/mysql.png';">
                        <span>MySQL</span>
                    </div>

                    <!-- VS Code (Blue) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-blue-500/30 backdrop-blur-sm rounded-xl text-blue-300 font-medium text-lg shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:border-blue-400 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/vscode.png') }}" alt="VS Code" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(59,130,246,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/visual-studio-code-2019.png';">
                        <span>VS Code</span>
                    </div>

                    <!-- Intellij IDEA (Purple/Violet) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-violet-500/30 backdrop-blur-sm rounded-xl text-violet-300 font-medium text-lg shadow-[0_0_15px_rgba(139,92,246,0.1)] hover:border-violet-400 hover:shadow-[0_0_20px_rgba(139,92,246,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/intelej.png') }}" alt="Intellij IDEA" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/intellij-idea.png';">
                        <span>Intelej</span>
                    </div>

                </div>
            </div>

            <!-- SUB-KATEGORI 4: OPERATING SYSTEM -->
            <div class="mb-12">
                <h4 class="text-lg text-gray-400 uppercase tracking-widest mb-8">Operating System</h4>
                <div class="flex flex-wrap gap-5">

                    <!-- Debian (Rose) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-rose-500/30 backdrop-blur-sm rounded-xl text-rose-300 font-medium text-lg shadow-[0_0_15px_rgba(244,63,94,0.1)] hover:border-rose-400 hover:shadow-[0_0_20px_rgba(244,63,94,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/debian.png') }}" alt="Debian" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(244,63,94,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/debian.png';">
                        <span>Debian</span>
                    </div>

                    <!-- Ubuntu (Orange) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-orange-500/30 backdrop-blur-sm rounded-xl text-orange-400 font-medium text-lg shadow-[0_0_15px_rgba(249,115,22,0.1)] hover:border-orange-400 hover:shadow-[0_0_20px_rgba(249,115,22,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/ubuntu.png') }}" alt="Ubuntu" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(249,115,22,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/ubuntu.png';">
                        <span>Ubuntu</span>
                    </div>

                    <!-- Linux Mint (Green) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-green-500/30 backdrop-blur-sm rounded-xl text-green-300 font-medium text-lg shadow-[0_0_15px_rgba(34,197,94,0.1)] hover:border-green-400 hover:shadow-[0_0_20px_rgba(34,197,94,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/linuxmint.png') }}" alt="Linux Mint" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(34,197,94,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/linuxmint.png';">
                        <span>Linux Mint</span>
                    </div>

                    <!-- VMware (Teal) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-teal-500/30 backdrop-blur-sm rounded-xl text-teal-300 font-medium text-lg shadow-[0_0_15px_rgba(20,184,166,0.1)] hover:border-teal-400 hover:shadow-[0_0_20px_rgba(20,184,166,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/vmware.png') }}" alt="VMware" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(20,184,166,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/vmware.png';">
                        <span>VM Ware</span>
                    </div>

                    <!-- Virtual Box (Emerald) -->
                    <div class="flex items-center gap-4 px-6 py-3 bg-[#130d31]/50 border border-emerald-500/30 backdrop-blur-sm rounded-xl text-emerald-300 font-medium text-lg shadow-[0_0_15px_rgba(16,185,129,0.1)] hover:border-emerald-400 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] transition duration-300">
                        <img src="{{ asset('images/logos/virtualbox.png') }}" alt="Virtual Box" class="w-8 h-8 object-contain filter drop-shadow-[0_0_5px_rgba(16,185,129,0.5)]" onerror="this.onerror=null; this.src='https://img.icons8.com/color/48/virtualbox.png';">
                        <span>Virtual Box</span>
                    </div>

                </div>
            </div>

        </section>

        <!-- ==================== SEKSI: RECENT BLOG (3 KOLOM) ==================== -->
        <section class="mb-20 w-full">
            <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 mb-8 flex items-center gap-3">
                <span>✍️</span> Recent Blog
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">

                <!-- Blog Card 1: Networking -->
                <a href="#" class="w-full bg-[#130d31]/40 border border-purple-500/20 backdrop-blur-md rounded-xl p-6 hover:border-cyan-400/50 hover:shadow-[0_0_25px_rgba(6,182,212,0.2)] active:scale-95 transform transition duration-200 cursor-pointer flex flex-col justify-between group">
                    <div>
                        <!-- Meta Info Tag -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-cyan-950/50 text-cyan-400 border border-cyan-500/30 text-xs font-mono rounded">NETWORKING</span>
                            <span class="text-xs text-gray-500 font-mono">July 7, 2026</span>
                        </div>
                        <h4 class="font-bold text-gray-200 text-xl group-hover:text-cyan-400 transition duration-300">Panduan Lengkap Konfigurasi OSPF Single Area</h4>
                        <p class="text-base text-gray-400 mt-3 leading-relaxed">Pelajari cara mudah melakukan konfigurasi protokol routing dinamis OSPF di router Cisco beserta langkah troubleshooting dasarnya.</p>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-purple-500/10 pt-4">
                        <span class="text-xs text-purple-400 font-mono">5 Min Read</span>
                        <span class="text-cyan-400 text-sm group-hover:translate-x-2 transition duration-200">Read More →</span>
                    </div>
                </a>

                <!-- Blog Card 2: OS / Linux -->
                <a href="#" class="w-full bg-[#130d31]/40 border border-purple-500/20 backdrop-blur-md rounded-xl p-6 hover:border-cyan-400/50 hover:shadow-[0_0_25px_rgba(6,182,212,0.2)] active:scale-95 transform transition duration-200 cursor-pointer flex flex-col justify-between group">
                    <div>
                        <!-- Meta Info Tag -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-purple-950/50 text-purple-400 border border-purple-500/30 text-xs font-mono rounded">LINUX MINT</span>
                            <span class="text-xs text-gray-500 font-mono">June 28, 2026</span>
                        </div>
                        <h4 class="font-bold text-gray-200 text-xl group-hover:text-purple-400 transition duration-300">Kenapa Network Engineer Wajib Pakai Linux Mint?</h4>
                        <p class="text-base text-gray-400 mt-3 leading-relaxed">Ulasan mendalam mengapa Linux Mint menjadi OS harian terbaik untuk menunjang aktivitas networking, administrasi server, hingga scripting Python.</p>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-purple-500/10 pt-4">
                        <span class="text-xs text-purple-400 font-mono">8 Min Read</span>
                        <span class="text-purple-400 text-sm group-hover:translate-x-2 transition duration-200">Read More →</span>
                    </div>
                </a>

                <!-- Blog Card 3: Security -->
                <a href="#" class="w-full bg-[#130d31]/40 border border-purple-500/20 backdrop-blur-md rounded-xl p-6 hover:border-cyan-400/50 hover:shadow-[0_0_25px_rgba(6,182,212,0.2)] active:scale-95 transform transition duration-200 cursor-pointer flex flex-col justify-between group">
                    <div>
                        <!-- Meta Info Tag -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-pink-950/50 text-pink-400 border border-pink-500/30 text-xs font-mono rounded">CYBER SECURITY</span>
                            <span class="text-xs text-gray-500 font-mono">June 15, 2026</span>
                        </div>
                        <h4 class="font-bold text-gray-200 text-xl group-hover:text-pink-400 transition duration-300">Mengamankan Switch Layer 2 dari Mac Flooding</h4>
                        <p class="text-base text-gray-400 mt-3 leading-relaxed">Cara praktis mengaktifkan fitur Port Security di switch Cisco untuk mencegah eksploitasi keamanan tabel MAC Address dari ancaman luar.</p>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-purple-500/10 pt-4">
                        <span class="text-xs text-purple-400 font-mono">6 Min Read</span>
                        <span class="text-pink-400 text-sm group-hover:translate-x-2 transition duration-200">Read More →</span>
                    </div>
                </a>

            </div>
        </section>
    </main>

    <!-- FOOTER -->
<!-- FOOTER (Seksi Baru: High-End Cyberpunk Grid, Glowing Contacts, SVGs) -->
    <footer class="bg-[#070414]/95 border-t border-purple-900/40 py-12 px-8 relative z-10 backdrop-blur-md">
        <div class="w-full md:pl-24 pr-8 md:pr-16 flex flex-col lg:flex-row items-center justify-between gap-10">

            <!-- SISI KIRI: Brand, Status, dan Copyright -->
            <div class="text-center lg:text-left space-y-4">
                <div class="flex items-center justify-center lg:justify-start gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_#10b981]"></span>
                    <span class="text-xs uppercase tracking-widest font-mono text-emerald-400">System Secure // Live Node</span>
                    <span class="text-xs text-purple-600/60 font-mono">|</span>
                    <!-- Format ON SITE dinamis sesuai permintaan -->
                    <span id="on-site-status" class="text-xs uppercase tracking-widest font-mono text-cyan-400 animate-pulse">ON SITE : GUEST</span>
                </div>
                <p class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 uppercase tracking-wider">
                    Dandy Al-Farisi Natanegara
                </p>
                <p class="text-sm text-gray-500 font-mono">
                    &copy; 2026 Dandy. Built with <span class="text-red-500/80 hover:text-red-500 transition">Laravel</span> on <span class="text-emerald-500/80 hover:text-emerald-500 transition">Linux Mint</span>.
                </p>
            </div>

            <!-- SISI KANAN: Terminal Sosial Media & Hubungi Saya (Pendaran Warna Khusus) -->
            <div class="flex flex-col items-center lg:items-end gap-5">
                <span class="text-xs font-mono uppercase tracking-widest text-purple-400">// SOCIAL_MEDIA_TERMINAL</span>
                <div class="flex flex-wrap justify-center lg:justify-end gap-4">

                    <!-- WHATSAPP (Green Neon Glow) -->
                    <a href="https://wa.me/6281234567890" target="_blank" title="WhatsApp"
                       class="p-3 bg-[#130d31]/50 border border-emerald-500/20 rounded-xl text-emerald-400 hover:text-emerald-300 hover:border-emerald-400 hover:shadow-[0_0_15px_rgba(16,185,129,0.4)] active:scale-95 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.503-5.734-1.46L0 24zm6.59-4.846l.397.235c1.558.925 3.35 1.411 5.183 1.412 5.675 0 10.293-4.613 10.296-10.29C22.527 7.55 21.47 4.98 19.55 3.06c-1.92-1.92-4.493-2.975-7.539-2.976-5.676 0-10.292 4.613-10.296 10.29-.001 1.89.493 3.731 1.432 5.34l.258.443L2.445 20.53l4.202-1.376zm11.168-5.75c-.328-.164-1.938-.955-2.23-1.062-.294-.107-.508-.16-.723.164-.214.32-.83.106-1.018 1.258-.188.152-.375.115-.7.049-.328-.066-1.385-.51-2.637-1.63-.974-.871-1.632-1.947-1.823-2.275-.19-.328-.02-.505.144-.669.148-.148.328-.383.492-.574.164-.192.218-.328.328-.547.11-.219.055-.41-.027-.574-.082-.164-.723-1.74-.991-2.385-.262-.63-.53-.545-.723-.555-.188-.01-.403-.012-.617-.012-.215 0-.566.08-.863.407-.297.324-1.137 1.113-1.137 2.717 0 1.604 1.168 3.154 1.328 3.37.16.216 2.3 3.511 5.572 4.92.778.335 1.386.535 1.86.686.782.249 1.494.213 2.057.129.627-.093 1.938-.792 2.212-1.558.275-.766.275-1.422.193-1.558-.081-.137-.296-.219-.624-.383z"/>
                        </svg>
                    </a>

                    <!-- GMAIL (Red Neon Glow) -->
                    <a href="mailto:dandy@yourdomain.com" target="_blank" title="Gmail"
                       class="p-3 bg-[#130d31]/50 border border-red-500/20 rounded-xl text-red-400 hover:text-red-300 hover:border-red-400 hover:shadow-[0_0_15px_rgba(239,68,68,0.4)] active:scale-95 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </a>

                    <!-- INSTAGRAM (Pink Neon Glow) -->
                    <a href="https://instagram.com/yourusername" target="_blank" title="Instagram"
                       class="p-3 bg-[#130d31]/50 border border-pink-500/20 rounded-xl text-pink-400 hover:text-pink-300 hover:border-pink-400 hover:shadow-[0_0_15px_rgba(236,72,153,0.4)] active:scale-95 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.75 2h8.5C19.42 2 22 4.58 22 7.75v8.5c0 3.17-2.58 5.75-5.75 5.75h-8.5C4.58 22 2 19.42 2 16.25v-8.5C2 4.58 4.58 2 7.75 2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zM17.5 6.5h.01"></path>
                        </svg>
                    </a>

                    <!-- LINKEDIN (Blue Neon Glow) -->
                    <a href="https://linkedin.com/in/yourusername" target="_blank" title="LinkedIn"
                       class="p-3 bg-[#130d31]/50 border border-blue-500/20 rounded-xl text-blue-400 hover:text-blue-300 hover:border-blue-400 hover:shadow-[0_0_15px_rgba(59,130,246,0.4)] active:scale-95 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <!-- LINE (Light Green Neon Glow) -->
                    <a href="https://line.me/ti/p/~yourid" target="_blank" title="LINE"
                       class="p-3 bg-[#130d31]/50 border border-emerald-400/20 rounded-xl text-emerald-400 hover:text-emerald-300 hover:border-emerald-400 hover:shadow-[0_0_15px_rgba(52,211,153,0.4)] active:scale-95 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 10.3c0-5.2-5.4-9.3-12-9.3S0 5.1 0 10.3c0 4.6 4.3 8.5 10.1 9.2.4.1.9.3 1 .6l.1 1.4c0 .3-.2.6-.5.7-.3.1-1 .3-1 .3-.3 0-.6.1-.8.4a1 1 0 00-.2.8c.1.5.7 1.3 1.2 1.8 1.4 1.2 3.1.9 3.7.6.6-.3 1.5-1 2.3-1.8l1.4-1.4c.3-.3.6-.3.9-.2 3.5 1.1 5.8-1.5 5.8-4.3zm-15.1 3.5H6.5c-.3 0-.6-.3-.6-.6V8.3c0-.3.3-.6.6-.6h.4c.3 0 .6.3.6.6v4.3h1.4c.3 0 .6.3.6.6v.4c0 .3-.3.6-.6.6zm3.3 0h-.4c-.3 0-.6-.3-.6-.6V8.3c0-.3.3-.6.6-.6h.4c.3 0 .6.3.6.6v4.9c0 .3-.3.6-.6.6zm7.2 0h-2.1c-.3 0-.6-.3-.6-.6V8.3c0-.3.3-.6.6-.6h2.1c.3 0 .6.3.6.6v.4c0 .3-.3.6-.6.6h-1.5v1.2h1.5c.3 0 .6.3.6.6v.4c0 .3-.3.6-.6.6h-1.5v1.2h1.5c.3 0 .6.3.6.6v.4c0 .3-.3.6-.6.6zm-3.6-2.9l-1.3-1.8c-.1-.2-.2-.2-.4-.2H14c-.3 0-.6.3-.6.6v4.3c0 .3.3.6.6.6h.4c.3 0 .6-.3.6-.6v-2.3l1.3 1.8c.1.2.3.2.4.2h.8c.3 0 .6-.3.6-.6V8.3c0-.3-.3-.6-.6-.6H16c-.3 0-.5.2-.6.4l.4 1.2z"/>
                        </svg>
                    </a>

                    <!-- GITHUB (Slate/White Neon Glow) -->
                    <a href="https://github.com/yourusername" target="_blank" title="GitHub"
                       class="p-3 bg-[#130d31]/50 border border-slate-400/20 rounded-xl text-slate-300 hover:text-white hover:border-white hover:shadow-[0_0_15px_rgba(255,255,255,0.25)] active:scale-95 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                    </a>

                </div>
            </div>

        </div>
    </footer>

    <!-- JAVASCRIPT ANIMASI NEURAL DAN GLOW -->
    <script>
        // --- 1. Logika Kursor Glowing ---
        const glow = document.getElementById('cursor-glow');
        window.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;
            glow.style.left = `${x}px`;
            glow.style.top = `${y}px`;
        });

        // --- 2. Logika Neural Network Canvas ---
        const canvas = document.getElementById('neural-canvas');
        const ctx = canvas.getContext('2d');
        const header = document.querySelector('header');

        function setCanvasSize() {
            canvas.width = header.offsetWidth;
            canvas.height = header.offsetHeight;
        }
        setCanvasSize();
        window.addEventListener('resize', setCanvasSize);

        const particles = [];
        const particleCount = 70;
        const mouseRadius = 150;
        const mouse = { x: null, y: null };

        header.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
        });

        header.addEventListener('mouseout', () => {
            mouse.x = null;
            mouse.y = null;
        });

        class Particle {
            constructor(x, y, dx, dy, size) {
                this.x = x; this.y = y; this.dx = dx; this.dy = dy; this.size = size;
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
                ctx.fillStyle = 'rgba(6, 182, 212, 0.6)';
                ctx.fill();
            }
            update() {
                if (this.x > canvas.width || this.x < 0) this.dx = -this.dx;
                if (this.y > canvas.height || this.y < 0) this.dy = -this.dy;
                this.x += this.dx;
                this.y += this.dy;
                this.draw();
            }
        }

        function initParticles() {
            particles.length = 0;
            for (let i = 0; i < particleCount; i++) {
                const size = Math.random() * 2 + 1;
                const x = Math.random() * canvas.width;
                const y = Math.random() * canvas.height;
                const dx = (Math.random() - 0.5) * 0.4;
                const dy = (Math.random() - 0.5) * 0.4;
                particles.push(new Particle(x, y, dx, dy, size));
            }
        }

        function connectParticles() {
            let opacity = 1;
            for (let a = 0; a < particles.length; a++) {
                const mouseDx = particles[a].x - mouse.x;
                const mouseDy = particles[a].y - mouse.y;
                const mouseDistance = Math.sqrt(mouseDx * mouseDx + mouseDy * mouseDy);

                if (mouse.x !== null && mouseDistance < mouseRadius) {
                    opacity = 1 - (mouseDistance / mouseRadius);
                    ctx.strokeStyle = `rgba(168, 85, 247, ${opacity})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(particles[a].x, particles[a].y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.stroke();
                }

                for (let b = a; b < particles.length; b++) {
                    const pdx = particles[a].x - particles[b].x;
                    const pdy = particles[a].y - particles[b].y;
                    const distance = Math.sqrt(pdx * pdx + pdy * pdy);

                    if (distance < 70) {
                        opacity = 1 - (distance / 70);
                        ctx.strokeStyle = `rgba(168, 85, 247, ${opacity * 0.3})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[a].x, particles[a].y);
                        ctx.lineTo(particles[b].x, particles[b].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => p.update());
            connectParticles();
            requestAnimationFrame(animate);
        }

        initParticles();
        animate();


         // --- 3. Logika Menyimpan Identitas Pengunjung (Local Storage) ---
        const onSiteStatus = document.getElementById('on-site-status');
        const userModal = document.getElementById('user-access-modal');
        const userInput = document.getElementById('user-access-input');
        const userSubmit = document.getElementById('user-access-submit');
        const userSkip = document.getElementById('user-access-skip');

        // Mengambil nama dari penyimpanan browser
        let savedUser = localStorage.getItem('cyberpunk_user');

        if (savedUser) {
            onSiteStatus.textContent = `ON SITE : ${savedUser}`;
        } else {
            // Tampilkan modal akses cyberpunk yang super estetik setelah 1.5 detik web terbuka
            setTimeout(() => {
                userModal.classList.remove('hidden');
                userModal.classList.add('flex');
            }, 1500);
        }

        // Ketika tombol Akses ditekan
        userSubmit.addEventListener('click', () => {
            const name = userInput.value.trim().toUpperCase() || 'GUEST';
            localStorage.setItem('cyberpunk_user', name);
            onSiteStatus.textContent = `ON SITE : ${name}`;
            closeModal();
        });

        // Ketika tombol Skip ditekan
        userSkip.addEventListener('click', () => {
            localStorage.setItem('cyberpunk_user', 'GUEST');
            onSiteStatus.textContent = `ON SITE : GUEST`;
            closeModal();
        });

        function closeModal() {
            userModal.classList.add('opacity-0');
            setTimeout(() => {
                userModal.classList.remove('flex');
                userModal.classList.add('hidden');
            }, 300);
        }
    </script>

</body>
</html>
