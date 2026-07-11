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

                    <!-- 🛠️ TRIX INTERACTIVE IMAGE RESIZER ENGINE (CYBERPUNK FLOATING TOOLBAR - SINKRONISASI AKTIF) -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            document.body.addEventListener("click", function(e) {
                                if (e.target.tagName === "IMG" && e.target.closest("trix-editor")) {
                                    const img = e.target;

                                    let toolbar = document.getElementById("trix-img-resizer");
                                    if (!toolbar) {
                                        toolbar = document.createElement("div");
                                        toolbar.id = "trix-img-resizer";
                                        toolbar.style.position = "absolute";
                                        toolbar.style.zIndex = "99999";
                                        toolbar.style.display = "flex";
                                        toolbar.style.gap = "8px";
                                        toolbar.style.alignItems = "center";
                                        toolbar.style.background = "#130d31";
                                        toolbar.style.border = "1.5px solid #22d3ee";
                                        toolbar.style.padding = "6px 12px";
                                        toolbar.style.borderRadius = "10px";
                                        toolbar.style.boxShadow = "0 0 20px rgba(6, 182, 212, 0.45)";
                                        toolbar.style.fontFamily = "monospace";
                                        document.body.appendChild(toolbar);
                                    }

                                    const rect = img.getBoundingClientRect();
                                    toolbar.style.top = (window.scrollY + rect.top - 48) + "px";
                                    toolbar.style.left = (window.scrollX + rect.left + (rect.width / 2) - 180) + "px";
                                    toolbar.style.display = "flex";

                                    toolbar.innerHTML = "";

                                    // 🚀 KELIPATAN BERURUTAN PRESISI DARI 30% SAMPAI 100%
                                    const sizes = ["30%", "40%", "50%", "60%", "70%", "80%", "90%", "100%"];

                                    sizes.forEach(size => {
                                        const btn = document.createElement("button");
                                        btn.innerText = size;
                                        btn.style.background = "rgba(168, 85, 247, 0.2)";
                                        btn.style.border = "1px solid rgba(168, 85, 247, 0.5)";
                                        btn.style.color = "#22d3ee";
                                        btn.style.padding = "3px 8px";
                                        btn.style.fontSize = "11px";
                                        btn.style.fontWeight = "bold";
                                        btn.style.borderRadius = "4px";
                                        btn.style.cursor = "pointer";
                                        btn.style.transition = "all 0.2s";

                                        btn.onmouseover = () => {
                                            btn.style.background = "#06b6d4";
                                            btn.style.color = "#130d31";
                                        };
                                        btn.onmouseout = () => {
                                            btn.style.background = "rgba(168, 85, 247, 0.2)";
                                            btn.style.color = "#22d3ee";
                                        };

                                        btn.onclick = function(event) {
                                            event.preventDefault();
                                            event.stopPropagation();

                                            const trixEditor = img.closest("trix-editor");
                                            const figure = img.closest("figure");

                                            if (trixEditor && figure) {
                                                const trixId = parseInt(figure.getAttribute("data-trix-id"));
                                                // Ambil objek attachment resmi dari dokumen internal Trix
                                                const attachment = trixEditor.editor.getDocument().getAttachmentById(trixId);

                                                if (attachment) {
                                                    // Hitung lebar piksel target berdasarkan persentase
                                                    const percentage = parseInt(size) / 100;
                                                    const originalWidth = img.naturalWidth || 800; // default ke 800 jika belum ter-render
                                                    const targetWidth = Math.round(originalWidth * percentage);

                                                    // 🚀 UPDATE ATRIBUT INTERNAL TRIX (Agar diserialisasikan permanen ke database)
                                                    trixEditor.editor.setAttributesForAttachment({ width: targetWidth }, attachment);
                                                }
                                            }

                                            // Sembunyikan kembali toolbar setelah modifikasi sukses
                                            toolbar.style.display = "none";
                                        };
                                        toolbar.appendChild(btn);
                                    });

                                    const closeBtn = document.createElement("button");
                                    closeBtn.innerText = "✕";
                                    closeBtn.style.color = "#ef4444";
                                    closeBtn.style.background = "none";
                                    closeBtn.style.border = "none";
                                    closeBtn.style.marginLeft = "6px";
                                    closeBtn.style.fontWeight = "bold";
                                    closeBtn.style.cursor = "pointer";
                                    closeBtn.onclick = function(event) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                        toolbar.style.display = "none";
                                    };
                                    toolbar.appendChild(closeBtn);
                                } else {
                                    const toolbar = document.getElementById("trix-img-resizer");
                                    if (toolbar && !e.target.closest("#trix-img-resizer")) {
                                        toolbar.style.display = "none";
                                    }
                                }
                            });
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

                        /* 5. SOLUSI TOTAL TULISAN WRITING CONTENT (Trix & TipTap Editor) & NEON SYNTAX HIGHLIGHTING */
                        trix-editor,
                        .trix-content,
                        .trix-editor *,
                        .trix-content *,
                        .fi-fo-rich-editor *,
                        .fi-fo-rich-editor-content,
                        .fi-fo-rich-editor-content *,
                        .ProseMirror,
                        .ProseMirror *,
                        .tiptap,
                        .tiptap * {
                            color: #f1f5f9 !important; /* Teks artikel default dipaksa abu-abu terang */
                            -webkit-text-fill-color: #f1f5f9 !important;
                        }

                        /* 🚀 MEMAKSA TINGGI AREA KETIK TIPTAP DAN BERI PADDING AGAR LEBIH LEGA */
                        .ProseMirror, .tiptap {
                            min-height: 500px !important;
                            padding: 1.5rem !important;
                        }

                        /* Mengubah kursor ketikan trix editor agar berwarna Cyan neon */
                        trix-editor {
                            caret-color: #22d3ee !important;
                        }

                        /* 🚀 CYBERPUNK NEON SYNTAX HIGHLIGHTING (Mencegah Kode Menjadi Invisible) */
                        .ProseMirror pre, .tiptap pre, .prose pre {
                            background-color: #060411 !important;
                            border: 1px solid rgba(168, 85, 247, 0.4) !important;
                            border-radius: 0.75rem !important;
                        }

                        .ProseMirror pre code, .tiptap pre code, .prose pre code {
                            color: #e2e8f0 !important; /* Warna teks dasar di dalam kode (Putih Terang) */
                        }

                        /* Warna Komentar (Simbol // dan komentarnya dipaksa abu-abu agar terbaca jelas) */
                        .ProseMirror pre code .hljs-comment,
                        .ProseMirror pre code .hljs-quote,
                        .tiptap pre code .hljs-comment,
                        .tiptap pre code .hljs-quote,
                        .hljs-comment,
                        .hljs-quote {
                            color: #94a3b8 !important; /* Slate gray terang, 100% terbaca di background gelap */
                            font-style: italic !important;
                        }

                        /* Warna Keyword utama (php, composer, sudo, systemctl, dll) */
                        .ProseMirror pre code .hljs-keyword,
                        .ProseMirror pre code .hljs-selector-tag,
                        .ProseMirror pre code .hljs-literal,
                        .ProseMirror pre code .hljs-section,
                        .ProseMirror pre code .hljs-link,
                        .tiptap pre code .hljs-keyword,
                        .tiptap pre code .hljs-selector-tag,
                        .hljs-keyword,
                        .hljs-selector-tag {
                            color: #22d3ee !important; /* Cyan Neon */
                            font-weight: bold !important;
                        }

                        /* Warna Atribut, Strings, dan Variabel */
                        .ProseMirror pre code .hljs-string,
                        .ProseMirror pre code .hljs-title,
                        .ProseMirror pre code .hljs-name,
                        .ProseMirror pre code .hljs-type,
                        .ProseMirror pre code .hljs-attr,
                        .ProseMirror pre code .hljs-attribute,
                        .tiptap pre code .hljs-string,
                        .tiptap pre code .hljs-title,
                        .hljs-string,
                        .hljs-title,
                        .hljs-name {
                            color: #a78bfa !important; /* Ungu Neon */
                        }

                        /* Warna Angka dan Simbol */
                        .ProseMirror pre code .hljs-number,
                        .ProseMirror pre code .hljs-regexp,
                        .ProseMirror pre code .hljs-symbol,
                        .ProseMirror pre code .hljs-variable,
                        .ProseMirror pre code .hljs-template-variable,
                        .tiptap pre code .hljs-number,
                        .hljs-number,
                        .hljs-symbol {
                            color: #f472b6 !important; /* Pink Neon */
                        }

                        /* Warna Built-in Command */
                        .ProseMirror pre code .hljs-built_in,
                        .ProseMirror pre code .hljs-builtin-name,
                        .tiptap pre code .hljs-built_in,
                        .hljs-built_in {
                            color: #38bdf8 !important; // Biru Langit
                        }

                        /* Memasukkan pendaran tombol editor TipTap */
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
