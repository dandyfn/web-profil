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

                    <style>
                        /* 1. Latar Belakang Gelap dengan Efek Grid Cyberpunk */
                        body,
                        .fi-layout,
                        .fi-main,
                        .fi-topbar,
                        .fi-section,
                        .fi-card,
                        .fi-panel,
                        main {
                            background-color: #0b071e !important;
                            background-image:
                                linear-gradient(rgba(18, 10, 50, 0.4) 1px, transparent 1px),
                                linear-gradient(90deg, rgba(18, 10, 50, 0.4) 1px, transparent 1px) !important;
                            background-size: 40px 40px !important;
                            color: #f1f5f9 !important; /* Warna teks default abu-abu terang */
                        }

                        /* 2. PEMBERSIHAN ELEMEN BAWAAN: Sembunyikan Logo & Breadcrumbs Tanpa Merusak Container */
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
                        .fi-fo-rich-editor-content *,
                        .ProseMirror,
                        .ProseMirror * {
                            color: #f1f5f9 !important; /* Teks artikel yang diketik dipaksa abu-abu terang */
                            -webkit-text-fill-color: #f1f5f9 !important;
                        }

                        /* 🚀 MEMAKSA TINGGI AREA KETIK TIPTAP DAN BERI PADDING AGAR LEBIH LEGA */
                        .ProseMirror, .tiptap {
                            min-height: 100px !important;
                            padding: 1.5rem !important;
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
