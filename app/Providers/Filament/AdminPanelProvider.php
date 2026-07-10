<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Enums\ThemeMode;
use Filament\View\PanelsRenderHook; // Import untuk menyisipkan kustomisasi HTML/CSS
use Illuminate\Support\Facades\Blade; // Import untuk merender elemen kustom
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // 1. TEMA UTAMA: Atur dark mode default
            ->defaultThemeMode(ThemeMode::Dark)

            // 2. MATIKAN BRAND LOGO: Mengosongkan brand name agar tulisan "Laravel" hilang dari sistem
            ->brandName('')

            ->colors([
                'primary' => Color::Cyan,
                'gray' => Color::Slate,
            ])

            // 3. NAVIGASI ATAS: Matikan sidebar kiri agar halaman lebih luas
            ->topNavigation()

            // 4. INJEKSI STYLE & SCRIPT CYBERPUNK (Render Hook HEAD)
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('
                    <!-- Memaksa dokumen mengaktifkan class dark mode Tailwind -->
                    <script>
                        document.documentElement.classList.add("dark");
                        document.documentElement.style.colorScheme = "dark";
                    </script>

                    <!-- LOGIKA NEURAL NETWORK CANVAS DI BACKGROUND ADMIN & HALAMAN LOGIN -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            // Deteksi jika canvas belum dibuat di body, buat baru secara otomatis
                            let canvas = document.getElementById("neural-canvas");
                            if (!canvas) {
                                canvas = document.createElement("canvas");
                                canvas.id = "neural-canvas";
                                canvas.style.position = "fixed";
                                canvas.style.top = "0";
                                canvas.style.left = "0";
                                canvas.style.width = "100vw";
                                canvas.style.height = "100vh";
                                canvas.style.zIndex = "-1"; // Ditempatkan di dasar paling belakang
                                canvas.style.pointerEvents = "none"; // Tidak memblokir klik kursor ke form
                                document.body.prepend(canvas);
                            }

                            const ctx = canvas.getContext("2d");

                            function setCanvasSize() {
                                canvas.width = window.innerWidth;
                                canvas.height = window.innerHeight;
                            }
                            setCanvasSize();
                            window.addEventListener("resize", setCanvasSize);

                            const particles = [];
                            const particleCount = 75; // Jumlah titik saraf
                            const mouseRadius = 150; // Radius tarikan kursor
                            const mouse = { x: null, y: null };

                            window.addEventListener("mousemove", (e) => {
                                mouse.x = e.clientX;
                                mouse.y = e.clientY;
                            });

                            window.addEventListener("mouseout", () => {
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
                                    ctx.fillStyle = "rgba(6, 182, 212, 0.6)"; // Warna biru cyan bercahaya
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
                                    // Tarikan garis ke posisi mouse kursor
                                    const mouseDx = particles[a].x - mouse.x;
                                    const mouseDy = particles[a].y - mouse.y;
                                    const mouseDistance = Math.sqrt(mouseDx * mouseDx + mouseDy * mouseDy);

                                    if (mouse.x !== null && mouseDistance < mouseRadius) {
                                        opacity = 1 - (mouseDistance / mouseRadius);
                                        ctx.strokeStyle = `rgba(168, 85, 247, ${opacity})`; // Garis ungu ke kursor
                                        ctx.lineWidth = 1;
                                        ctx.beginPath();
                                        ctx.moveTo(particles[a].x, particles[a].y);
                                        ctx.lineTo(mouse.x, mouse.y);
                                        ctx.stroke();
                                    }

                                    // Garis penghubung antar titik terdekat
                                    for (let b = a; b < particles.length; b++) {
                                        const pdx = particles[a].x - particles[b].x;
                                        const pdy = particles[a].y - particles[b].y;
                                        const distance = Math.sqrt(pdx * pdx + pdy * pdy);

                                        if (distance < 80) {
                                            opacity = 1 - (distance / 80);
                                            ctx.strokeStyle = `rgba(168, 85, 247, ${opacity * 0.35})`;
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
                        });
                    </script>

                    <style>
                        /* 1. Latar Belakang Gelap Utama Hanya di Body */
                        body {
                            background-color: #0b071e !important;
                            background-image:
                                linear-gradient(rgba(18, 10, 50, 0.4) 1px, transparent 1px),
                                linear-gradient(90deg, rgba(18, 10, 50, 0.4) 1px, transparent 1px) !important;
                            background-size: 40px 40px !important;
                            color: #f1f5f9 !important; /* Warna teks default */
                        }

                        /* 2. PEMBERSIHAN LAYER CONTAINER: Dipaksa Transparan Agar Canvas Terlihat */
                        .fi-layout,
                        .fi-main,
                        .fi-topbar,
                        .fi-simple-layout,
                        .fi-simple-main,
                        main {
                            background: transparent !important;
                        }

                        /* 3. EFEK MELAYANG: Membuat Box Login / Box Form Menjadi Semi-Transparan Ber-blur */
                        .fi-card,
                        .fi-panel,
                        .fi-section {
                            background-color: rgba(19, 13, 49, 0.85) !important;
                            border: 1px solid rgba(168, 85, 247, 0.3) !important;
                            backdrop-filter: blur(8px) !important;
                            box-shadow: 0 0 35px rgba(168, 85, 247, 0.15) !important;
                        }

                        /* 4. PEMBERSIHAN ELEMEN BAWAAN: Sembunyikan Logo & Breadcrumbs Tanpa Merusak Container */
                        .fi-topbar-header-container > a,
                        .fi-topbar-brand,
                        .fi-topbar-brand-name,
                        .fi-logo,
                        .fi-breadcrumbs {
                            display: none !important;
                            visibility: hidden !important;
                            opacity: 0 !important;
                            width: 0 !important;
                            pointer-events: none !important;
                        }

                        /* Pastikan padding header seimbang dan rapi */
                        .fi-topbar-header-container {
                            padding-left: 1.5rem !important;
                            padding-right: 1.5rem !important;
                        }

                        /* 3. PAKSA SEMUA HEADER & LABEL BERWARNA CYAN TERANG */
                        h1, h2, h3, h4, h5, h6,
                        .fi-header-title,
                        .fi-ta-header-title,
                        .fi-section-header-title {
                            color: #22d3ee !important;
                            text-shadow: 0 0 10px rgba(34, 211, 238, 0.3) !important;
                        }

                        /* Memaksa warna label formulir di atas input agar terlihat jelas */
                        label,
                        .fi-fo-field-wrp-label span,
                        .fi-fo-placeholder,
                        span.text-sm {
                            color: #a78bfa !important; /* Warna ungu neon terang */
                            font-weight: 600 !important;
                        }

                        /* 4. MODIFIKASI EXTRA KETAT UNTUK KOLOM INPUT, TEXTAREA & RICH EDITOR */
                        input,
                        textarea,
                        select,
                        .fi-fo-rich-editor,
                        .choices__inner,
                        .fi-input-wrp {
                            background-color: rgba(19, 13, 49, 0.95) !important;
                            border: 1px solid rgba(168, 85, 247, 0.5) !important; /* Border Ungu Neon */
                            color: #22d3ee !important; /* Warna tulisan ketikan input dipaksa Cyan Terang */
                            border-radius: 0.75rem !important;
                        }

                        /* Memaksa text input di dalam pembungkus Filament agar berwarna Cyan */
                        .fi-input-wrp input,
                        .fi-input-wrp select,
                        .fi-input-wrp textarea,
                        .fi-input,
                        input[type="text"],
                        input[type="email"],
                        input[type="password"] {
                            color: #22d3ee !important;
                            -webkit-text-fill-color: #22d3ee !important; /* Paksa untuk Safari/Chrome */
                        }

                        /* Efek Fokus Input (Glow Cyan) */
                        input:focus,
                        textarea:focus,
                        .fi-fo-rich-editor:focus-within,
                        .fi-input-wrp:focus-within {
                            border-color: #22d3ee !important;
                            box-shadow: 0 0 15px rgba(6, 182, 212, 0.5) !important;
                        }

                        /* 5. SOLUSI TOTAL TULISAN TRIX WRITING CONTENT */
                        /* Memaksa tulisan ketikan di dalam area editor trix dan semua sub-elementnya berwarna putih terang */
                        trix-editor,
                        .trix-content,
                        .trix-editor *,
                        .trix-content *,
                        .fi-fo-rich-editor *,
                        .fi-fo-rich-editor-content,
                        .fi-fo-rich-editor-content * {
                            color: #f1f5f9 !important; /* Teks artikel yang diketik dipaksa abu-abu terang */
                            -webkit-text-fill-color: #f1f5f9 !important;
                        }

                        /* Mengubah kursor ketikan trix editor agar berwarna Cyan neon */
                        trix-editor {
                            caret-color: #22d3ee !important;
                        }

                        /* Memastikan warna tombol toolbar Rich Editor terlihat */
                        .trix-button-row button,
                        .trix-button-group button,
                        .fi-fo-rich-editor-toolbar button {
                            background-color: rgba(255, 255, 255, 0.05) !important;
                            color: #22d3ee !important;
                            border-color: rgba(168, 85, 247, 0.3) !important;
                        }

                        /* 6. Tombol Utama (Save / Create) dengan Gradasi Pink-Cyan */
                        .fi-btn-color-primary,
                        .fi-ac-action {
                            background-image: linear-gradient(to right, #ec4899, #8b5cf6, #06b6d4) !important;
                            color: #ffffff !important;
                            border: none !important;
                            font-weight: 700 !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.05em !important;
                            box-shadow: 0 0 15px rgba(139, 92, 246, 0.4) !important;
                            transition: all 0.3s ease !important;
                        }

                        .fi-btn-color-primary:hover,
                        .fi-ac-action:hover {
                            box-shadow: 0 0 25px rgba(6, 182, 212, 0.7) !important;
                            transform: translateY(-1px) !important;
                        }

                        /* Tombol Batal/Kembali */
                        .fi-btn-color-gray {
                            background-color: rgba(255, 255, 255, 0.05) !important;
                            border: 1px solid rgba(156, 163, 175, 0.3) !important;
                            color: #9ca3af !important;
                        }
                    </style>
                '),
            )

            // 5. INJEKSI TOMBOL KEMBALI SECARA PRESISI (Render Hook TOPBAR START)
            // Tombol ini ditempatkan di dalam jangkauan topbar di sebelah kiri, menggantikan logo Laravel secara bersih.
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): string => '<a href="/blog" class="flex items-center gap-2 px-5 py-2 bg-[#130d31]/90 border border-cyan-500/30 text-cyan-400 font-mono text-xs font-semibold rounded-xl hover:border-cyan-400 hover:text-cyan-300 hover:shadow-[0_0_20px_rgba(6,182,212,0.45)] transition-all duration-300 backdrop-blur-md">← RETURN TO MAIN BLOG</a>'
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Mengosongkan dashboard agar menu dashboard tidak terdaftar
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
