@php
    // 🚀 BULLETPROOF FIX: Agar variable khali hai toh zabardasti DB se uthao
    if (empty($settings)) {
        $settings = \App\Models\Setting::first(); 
    }
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DriveElite Rentals') - Premium Car Rentals</title>
    
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/741/741407.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* 🚀 ACCESSIBILITY: Smooth Scrolling Enable */
        html { scroll-behavior: smooth; transition: font-size 0.3s ease; }

        .font-poppins { font-family: 'Poppins', sans-serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; overflow-x: hidden; }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #0f172a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #f97316; }

        .glass-nav { background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); box-shadow: 0 4px 20px -1px rgba(0, 0, 0, 0.08); }

        /* 🚀 ACCESSIBILITY: Visual Focus Glow when skipping */
        #main-content:focus {
            outline: none;
            box-shadow: 0 0 0 5px rgba(249, 115, 22, 0.4);
            border-radius: 20px;
            transition: box-shadow 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* 🚀 ACCESSIBILITY FEATURE 2: HIGH CONTRAST MASTER CSS */
        body.high-contrast {
            filter: invert(100%) hue-rotate(180deg) contrast(120%);
            background-color: #111;
        }
        body.high-contrast img, 
        body.high-contrast video,
        body.high-contrast #accessibility-widget {
            filter: invert(100%) hue-rotate(180deg);
        }

        /* 🚀 ACCESSIBILITY FEATURE 4: PAUSE ALL ANIMATIONS */
        body.pause-animations,
        body.pause-animations * {
            animation: none !important;
            transition: none !important;
            scroll-behavior: auto !important;
        }

        /* Custom SweetAlert Ultra-Premium Styles */
        .swal2-timer-progress-bar { background: linear-gradient(90deg, #f97316, #ea580c) !important; height: 4px !important; }
        .premium-swal-popup { border: 1px solid rgba(255,255,255,0.1) !important; box-shadow: 0 30px 60px rgba(0,0,0,0.9) !important; backdrop-filter: blur(20px) !important; }

        /* 🌟 DEVELOPER BRANDING ANIMATIONS 🌟 */
        @keyframes branding-glow {
            0%, 100% { text-shadow: 0 0 15px rgba(249, 115, 22, 0.3); opacity: 1; transform: scale(1); }
            50% { text-shadow: 0 0 30px rgba(249, 115, 22, 0.7); opacity: 0.8; transform: scale(1.02); }
        }
        .animate-branding { animation: branding-glow 4s ease-in-out infinite; }

        /* 🚀 PAGE UP/DOWN BUTTONS */
        .scroll-controls { position: fixed; right: 20px; top: 50%; transform: translateY(-50%); display: flex; flex-direction: column; gap: 10px; z-index: 100; }
        .scroll-btn { width: 40px; height: 40px; background: #0f172a; color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; cursor: pointer; transition: all 0.3s ease; border: 1px solid rgba(249, 115, 22, 0.3); opacity: 0.7; }
        .scroll-btn:hover { background: #f97316; opacity: 1; transform: translateX(-5px); box-shadow: 0 5px 15px rgba(249, 115, 22, 0.4); }

        /* 🚀 MOBILE MENU ANIMATION */
        #mobile-menu { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); transform: translateY(-100%); opacity: 0; pointer-events: none; }
        #mobile-menu.active { transform: translateY(0); opacity: 1; pointer-events: auto; }
    </style>
</head>
<body class="text-gray-800 antialiased selection:bg-orange-500 selection:text-white flex flex-col min-h-screen transition-all duration-500">

    <!-- 🚀 VIP ACCESSIBILITY WIDGET -->
    <div id="accessibility-widget" class="fixed left-0 top-[40%] z-[999999] group">
        <!-- Floating Button -->
        <button onclick="document.getElementById('access-panel').classList.toggle('hidden')" class="bg-blue-950 text-white w-12 h-12 flex items-center justify-center rounded-r-xl shadow-[0_4px_15px_rgba(0,0,0,0.3)] hover:w-14 hover:bg-orange-500 transition-all focus:outline-none focus:ring-4 focus:ring-orange-500" title="Accessibility Options">
            <i class="fa-solid fa-universal-access text-2xl"></i>
        </button>
        
        <!-- Dropdown Menu -->
        <div id="access-panel" class="hidden absolute top-0 left-14 bg-white rounded-xl shadow-2xl border border-gray-200 w-64 overflow-hidden origin-left transition-all duration-300">
            <div class="bg-blue-950 text-white py-3 px-4 font-poppins font-bold text-sm tracking-widest uppercase flex justify-between items-center">
                <span>Accessibility</span>
                <button onclick="document.getElementById('access-panel').classList.add('hidden')" class="hover:text-orange-500 focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <div class="p-4 space-y-5">
                <!-- 🚀 Feature 5: Voice Guide (Screen Reader) -->
                <div>
                    <p class="text-[10px] text-gray-500 font-bold mb-2 uppercase tracking-wider">Screen Reader</p>
                    <button id="toggle-tts-btn" onclick="toggleTTS()" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-800 py-2.5 px-3 rounded-lg flex items-center justify-between transition-colors focus:ring-2 focus:ring-orange-500 font-semibold text-sm">
                        <span id="tts-text">Enable Voice Guide</span>
                        <i id="tts-icon" class="fa-solid fa-volume-high text-orange-500"></i>
                    </button>
                </div>

                <!-- Feature 2: High Contrast -->
                <div>
                    <p class="text-[10px] text-gray-500 font-bold mb-2 uppercase tracking-wider">Vision Impairment</p>
                    <button onclick="toggleHighContrast()" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-800 py-2.5 px-3 rounded-lg flex items-center justify-between transition-colors focus:ring-2 focus:ring-orange-500 font-semibold text-sm">
                        <span>High Contrast</span>
                        <i class="fa-solid fa-circle-half-stroke text-orange-500"></i>
                    </button>
                </div>

                <!-- Feature 3: Text Size -->
                <div>
                    <p class="text-[10px] text-gray-500 font-bold mb-2 uppercase tracking-wider">Text Size</p>
                    <div class="flex gap-2">
                        <button onclick="changeTextSize('decrease')" class="flex-1 bg-gray-50 hover:bg-gray-200 border border-gray-200 py-1.5 rounded font-bold text-sm transition-colors text-blue-950 focus:ring-2 focus:ring-orange-500" title="Decrease Text Size">A-</button>
                        <button onclick="changeTextSize('reset')" class="flex-1 bg-gray-50 hover:bg-gray-200 border border-gray-200 py-1.5 rounded font-bold text-base transition-colors text-blue-950 focus:ring-2 focus:ring-orange-500" title="Reset Text Size">A</button>
                        <button onclick="changeTextSize('increase')" class="flex-1 bg-gray-50 hover:bg-gray-200 border border-gray-200 py-1.5 rounded font-bold text-lg transition-colors text-blue-950 focus:ring-2 focus:ring-orange-500" title="Increase Text Size">A+</button>
                    </div>
                </div>
                
                <!-- 🚀 Feature 4: Animations (ZINDA HO GAYA!) -->
                <div>
                    <p class="text-[10px] text-gray-500 font-bold mb-2 uppercase tracking-wider">Motion / Vestibular</p>
                    <button id="toggle-animations-btn" onclick="toggleAnimations()" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-800 py-2.5 px-3 rounded-lg flex items-center justify-between transition-colors focus:ring-2 focus:ring-orange-500 font-semibold text-sm">
                        <span id="animations-text">Pause Animations</span>
                        <i id="animations-icon" class="fa-solid fa-pause text-orange-500"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 🚀 VIP ACCESSIBILITY: Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-6 focus:left-6 focus:z-[99999] bg-orange-500 text-white px-8 py-4 rounded-xl shadow-[0_10px_40px_rgba(249,115,22,0.8)] font-black uppercase tracking-widest text-sm outline-none ring-4 ring-blue-950">
        Skip to Main Content
    </a>

    <div class="scroll-controls">
        <div class="scroll-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Go to Top"><i class="fa-solid fa-chevron-up"></i></div>
        <div class="scroll-btn" onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'})" title="Go to Bottom"><i class="fa-solid fa-chevron-down"></i></div>
    </div>

    <nav id="navbar" class="fixed top-0 w-full z-[100] border-b border-gray-100 transition-all duration-300 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center bg-white relative z-50">
            <a href="{{ route('home') }}" class="font-poppins text-2xl font-black text-blue-950 tracking-tight group flex items-center gap-2">
                <i class="fa-solid fa-car-side text-orange-500 group-hover:scale-110 transition-transform"></i>
                <div>Drive<span class="text-orange-500 group-hover:text-orange-600 transition-colors">Elite.</span></div>
            </a>
            
            <div class="hidden md:flex items-center gap-8 font-semibold text-sm text-gray-600 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors duration-300">Home</a>
                <a href="{{ route('fleet') }}" class="hover:text-orange-500 transition-colors duration-300">Our Fleet</a>
                <a href="{{ route('about') }}" class="hover:text-orange-500 transition-colors duration-300">About Us</a>
                <a href="{{ route('services') }}" class="hover:text-orange-500 transition-colors duration-300">Services</a>
                <a href="{{ route('contact') }}" class="hover:text-orange-500 transition-colors duration-300">Contact Us</a>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="font-poppins bg-blue-950 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-900 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            My Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 font-bold text-sm text-gray-600 hover:text-blue-950 transition-colors">
                            <i class="fa-regular fa-user text-lg"></i> Log In
                        </a>
                        <a href="{{ route('register') }}" class="font-poppins bg-orange-500 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-orange-600 transition-all shadow-[0_4px_15px_rgba(249,115,22,0.4)] transform hover:-translate-y-0.5">
                            Sign Up
                        </a>
                    @endauth
                </div>

                <button id="menu-toggle" class="md:hidden text-blue-950 text-2xl focus:outline-none p-2">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="absolute top-20 left-0 w-full bg-white z-40 flex flex-col p-6 space-y-6 md:hidden border-b border-gray-100 shadow-xl pb-10">
            <div class="flex flex-col gap-4 text-center">
                <a href="{{ route('home') }}" class="text-lg font-bold text-blue-950 py-3 border-b border-gray-50 uppercase">Home</a>
                <a href="{{ route('fleet') }}" class="text-lg font-bold text-blue-950 py-3 border-b border-gray-50 uppercase">Our Fleet</a>
                <a href="{{ route('about') }}" class="text-lg font-bold text-blue-950 py-3 border-b border-gray-50 uppercase">About Us</a>
                <a href="{{ route('services') }}" class="text-lg font-bold text-blue-950 py-3 border-b border-gray-50 uppercase">Services</a>
                <a href="{{ route('contact') }}" class="text-lg font-bold text-blue-950 py-3 border-b border-gray-50 uppercase">Contact Us</a>
            </div>
            
            <div class="flex flex-col gap-4 mt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-950 text-white py-4 rounded-xl text-center font-bold uppercase tracking-widest">My Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="border-2 border-blue-950 text-blue-950 py-4 rounded-xl text-center font-bold uppercase tracking-widest"><i class="fa-regular fa-user mr-2"></i> Log In</a>
                    <a href="{{ route('register') }}" class="bg-orange-500 text-white py-4 rounded-xl text-center font-bold shadow-lg uppercase tracking-widest">Sign Up Now</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 🚀 ACCESSIBILITY: ID added -->
    <main id="main-content" tabindex="-1" class="flex-grow pt-20 outline-none">
        @yield('content')
    </main>

    @if(isset($settings['whatsapp_link']) && $settings['whatsapp_link'] != '')
    <a href="{{ $settings['whatsapp_link'] }}" target="_blank" class="fixed bottom-6 right-6 z-50 bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center text-3xl shadow-[0_10px_20px_rgba(34,197,94,0.4)] hover:bg-green-600 hover:scale-110 transition-all duration-300 animate-bounce" title="Chat on WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
    @endif

    <footer class="bg-[#0b1120] text-gray-300 pt-20 pb-16 border-t-[4px] border-orange-500 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-900/20 rounded-full blur-3xl -mr-48 -mt-48 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20 relative z-10">
            <div class="lg:col-span-1">
                <h2 class="font-poppins text-3xl font-black tracking-tight mb-6 flex items-center gap-2">
                    <span class="text-white">Drive</span><span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Elite</span>
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Redefining luxury and comfort on the roads of Pakistan. Book your premium vehicle today and experience a journey like never before.
                </p>
                <div class="flex gap-4">
                    @if(isset($settings['facebook_link']) && $settings['facebook_link'] != '') 
                        <a href="{{ $settings['facebook_link'] }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#1877F2] hover:border-[#1877F2] hover:text-white transition-all duration-300"><i class="fa-brands fa-facebook-f"></i></a> 
                    @endif
                    @if(isset($settings['whatsapp_link']) && $settings['whatsapp_link'] != '') 
                        <a href="{{ $settings['whatsapp_link'] }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#25D366] hover:border-[#25D366] hover:text-white transition-all duration-300"><i class="fa-brands fa-whatsapp"></i></a> 
                    @endif
                    @if(isset($settings['instagram_link']) && $settings['instagram_link'] != '') 
                        <a href="{{ $settings['instagram_link'] }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-gradient-to-tr hover:from-[#f09433] hover:to-[#bc1888] hover:border-transparent hover:text-white transition-all duration-300"><i class="fa-brands fa-instagram"></i></a> 
                    @endif
                    @if(isset($settings['youtube_link']) && $settings['youtube_link'] != '') 
                        <a href="{{ $settings['youtube_link'] }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#FF0000] hover:border-[#FF0000] hover:text-white transition-all duration-300"><i class="fa-brands fa-youtube"></i></a> 
                    @endif
                </div>
            </div>
            
            <div>
                <h3 class="font-poppins text-lg font-bold mb-6 text-white uppercase tracking-wider text-sm">Company</h3>
                <ul class="space-y-4 text-sm text-gray-400 font-medium">
                    <li><a href="{{ route('about') }}" class="hover:text-orange-500 hover:translate-x-1 inline-block transition-all duration-300">About Us</a></li>
                    <li><a href="{{ route('fleet') }}" class="hover:text-orange-500 hover:translate-x-1 inline-block transition-all duration-300">Our Fleet</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-orange-500 hover:translate-x-1 inline-block transition-all duration-300">Services</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-orange-500 hover:translate-x-1 inline-block transition-all duration-300">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-poppins text-lg font-bold mb-6 text-white uppercase tracking-wider text-sm">Get In Touch</h3>
                <ul class="space-y-4 text-sm text-gray-400 font-medium">
                    <li class="flex items-start gap-3 group">
                        @php
                            $footer_map_link = !empty($settings['google_map_link']) 
                                ? $settings['google_map_link'] 
                                : "https://www.google.com/maps/search/" . urlencode($settings['company_address'] ?? 'Pakistan');
                        @endphp
                        <a href="{{ $footer_map_link }}" target="_blank" class="hover:text-orange-500 transition-all duration-300 transform group-hover:scale-110">
                            <i class="fa-solid fa-location-dot mt-1 text-orange-500"></i>
                        </a>
                        <span>{{ $settings['company_address'] ?? 'Drive Elite Headquarters, Pakistan' }}</span>
                    </li>
                    <li class="flex items-center gap-3 group">
                        <i class="fa-solid fa-phone text-orange-500"></i>
                        <span>{{ $settings['company_phone'] ?? '+92 300 0000000' }}</span>
                    </li>
                    <li class="flex items-center gap-3 group">
                        <i class="fa-solid fa-envelope text-orange-500"></i>
                        <a href="mailto:{{ $settings['company_email'] ?? 'info@driveelite.com' }}" class="hover:text-white transition-colors">{{ $settings['company_email'] ?? 'info@driveelite.com' }}</a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="font-poppins text-lg font-bold mb-6 text-white uppercase tracking-wider text-sm">Stay Updated</h3>
                <p class="text-gray-400 text-sm mb-4">Subscribe to our newsletter for exclusive rental discounts.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="relative">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email" required class="w-full bg-white/5 border border-white/10 rounded-lg py-3.5 pl-4 pr-12 text-sm text-white focus:outline-none focus:border-orange-500 focus:bg-white/10 transition-all">
                    <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-orange-500 text-white w-10 rounded-md hover:bg-orange-600 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-12 border-t border-white/5 flex flex-col items-center justify-center text-center relative z-10">
            
            <div class="mb-6" data-aos="zoom-in">
                <h2 class="font-poppins text-2xl md:text-4xl font-black tracking-[0.25em] uppercase italic transition-all duration-500 hover:tracking-[0.35em] inline-block animate-branding">
                    <span class="text-white">NN</span> <span class="text-orange-500">DEVELOPERS</span>
                </h2>
            </div>

            <div class="space-y-3" data-aos="fade-up" data-aos-delay="200">
                <p class="text-sm md:text-base font-bold text-gray-400 uppercase tracking-[0.2em] flex items-center justify-center gap-4">
                    <span class="hidden md:block w-12 h-[1px] bg-gradient-to-r from-transparent to-orange-500/50"></span>
                    © 2026 Drive Elite Rentals. All rights reserved.
                    <span class="hidden md:block w-12 h-[1px] bg-gradient-to-l from-transparent to-orange-500/50"></span>
                </p>
            </div>
        </div>
    </footer>

    <script>
        // 🚀 MOBILE MENU TOGGLE SCRIPT
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = menuToggle.querySelector('i');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            if(mobileMenu.classList.contains('active')) {
                menuIcon.classList.remove('fa-bars-staggered');
                menuIcon.classList.add('fa-xmark');
            } else {
                menuIcon.classList.remove('fa-xmark');
                menuIcon.classList.add('fa-bars-staggered');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({ duration: 1000, once: true, mirror: false, offset: 50 });

            @if(session('success'))
                Swal.fire({
                    html: `<div class="flex flex-col items-center p-4"><div class="w-20 h-20 rounded-full bg-gradient-to-tr from-orange-400 to-orange-600 flex items-center justify-center mb-6 shadow-[0_0_50px_rgba(249,115,22,0.6)] animate__animated animate__pulse animate__infinite"><i class="fa-solid fa-check text-white text-4xl"></i></div><h2 class="font-poppins text-2xl font-black text-white tracking-widest uppercase mb-3 drop-shadow-lg">Success</h2><p class="font-inter text-gray-300 text-base leading-relaxed text-center px-2">{!! session('success') !!}</p></div>`,
                    background: 'rgba(11, 17, 32, 0.85)', backdrop: `rgba(0,0,0,0.8) backdrop-filter: blur(10px)`, showConfirmButton: false, timer: 5000, timerProgressBar: true, customClass: { popup: 'premium-swal-popup rounded-[2rem]' }, showClass: { popup: 'animate__animated animate__fadeInDown' }, hideClass: { popup: 'animate__animated animate__zoomOut' }
                });
            @endif
        });

        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('glass-nav');
                navbar.classList.remove('bg-white');
            } else {
                navbar.classList.remove('glass-nav');
                navbar.classList.add('bg-white');
            }
        });

        // 🚀 1. THE REAL SKIP-TO-MAIN SCRIPT
        document.querySelector('a[href="#main-content"]').addEventListener('click', function(e) {
            e.preventDefault();
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
                window.scrollTo({ top: mainContent.offsetTop - 80, behavior: 'smooth' });
                const firstInput = mainContent.querySelector('input, select, textarea, button');
                if(firstInput) {
                    firstInput.focus();
                    firstInput.style.boxShadow = "0 0 0 4px #f97316";
                    setTimeout(() => { firstInput.style.boxShadow = ""; }, 2000);
                } else {
                    mainContent.setAttribute('tabindex', '-1');
                    mainContent.focus();
                }
            }
        });

        // 🚀 2. FEATURE 2: HIGH CONTRAST LOGIC
        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            if(document.body.classList.contains('high-contrast')) {
                localStorage.setItem('highContrast', 'enabled');
            } else {
                localStorage.setItem('highContrast', 'disabled');
            }
        }
        if(localStorage.getItem('highContrast') === 'enabled') {
            document.body.classList.add('high-contrast');
        }

        // 🚀 3. FEATURE 3: TEXT RESIZER LOGIC
        let currentZoom = localStorage.getItem('textZoom') ? parseInt(localStorage.getItem('textZoom')) : 100;
        function applyTextZoom() {
            document.documentElement.style.fontSize = currentZoom + '%';
            localStorage.setItem('textZoom', currentZoom);
        }
        applyTextZoom();

        function changeTextSize(action) {
            if (action === 'increase' && currentZoom < 130) {
                currentZoom += 10;
            } else if (action === 'decrease' && currentZoom > 80) {
                currentZoom -= 10;
            } else if (action === 'reset') {
                currentZoom = 100;
            }
            applyTextZoom();
        }

        // 🚀 4. FEATURE 4: PAUSE ANIMATIONS LOGIC
        function toggleAnimations() {
            document.body.classList.toggle('pause-animations');
            const isPaused = document.body.classList.contains('pause-animations');
            
            // UI Update
            document.getElementById('animations-text').innerText = isPaused ? 'Play Animations' : 'Pause Animations';
            document.getElementById('animations-icon').className = isPaused ? 'fa-solid fa-play text-orange-500' : 'fa-solid fa-pause text-orange-500';
            
            // Save settings
            localStorage.setItem('pauseAnimations', isPaused ? 'enabled' : 'disabled');
        }

        // On Page Load Check
        if(localStorage.getItem('pauseAnimations') === 'enabled') {
            document.body.classList.add('pause-animations');
            document.getElementById('animations-text').innerText = 'Play Animations';
            document.getElementById('animations-icon').className = 'fa-solid fa-play text-orange-500';
        }

        // 🚀 5. FEATURE 5: VOICE GUIDE (SCREEN READER) LOGIC 🗣️
        const synth = window.speechSynthesis;
        let lastText = "";

        function toggleTTS() {
            const isEnabled = localStorage.getItem('accessibility_tts') === 'true';
            const newState = !isEnabled;
            
            localStorage.setItem('accessibility_tts', newState);
            updateTTSUI(newState);
            
            if (newState) {
                speakText("Voice guide enabled. Move your cursor over text to hear it.");
            } else {
                synth.cancel(); // Awaz foran rok dega agar disable kiya gaya
            }
        }

        function updateTTSUI(isEnabled) {
            const ttsText = document.getElementById('tts-text');
            const ttsIcon = document.getElementById('tts-icon');
            if (ttsText && ttsIcon) {
                ttsText.innerText = isEnabled ? 'Disable Voice Guide' : 'Enable Voice Guide';
                ttsIcon.className = isEnabled ? 'fa-solid fa-volume-xmark text-orange-500' : 'fa-solid fa-volume-high text-orange-500';
            }
        }

        function speakText(text) {
            const isTtsEnabled = localStorage.getItem('accessibility_tts') === 'true';
            
            if (isTtsEnabled && text !== lastText && text.trim() !== "") {
                synth.cancel(); // Purani awaz ko rok kar nayi shuru karega
                const utterThis = new SpeechSynthesisUtterance(text);
                utterThis.rate = 1.0; // Bolne ki speed
                utterThis.pitch = 1.0; // Awaz ka bhaari pan
                synth.speak(utterThis);
                lastText = text; 
            }
        }

        // Jab user kisi aisi cheez par mouse laye jo parhne wali ho
        document.addEventListener('mouseover', (e) => {
            const isTtsEnabled = localStorage.getItem('accessibility_tts') === 'true';
            if (!isTtsEnabled) return;

            // Kin cheezon ko parhna hai: links, buttons, headings, paragraphs
            const target = e.target.closest('a, button, h1, h2, h3, h4, h5, h6, p, span, li, label, .car-card');
            
            if (target) {
                // Toggling ke waqt khud widget ko parhne se rokein taake disturb na ho
                if(target.closest('#accessibility-widget')) return;

                const textToSpeak = target.innerText || target.getAttribute('aria-label') || target.alt;
                if (textToSpeak) {
                    speakText(textToSpeak.trim());
                }
            }
        });

        // Mouse hatne par lastText clear karein taake wapis aane par dobara bolay
        document.addEventListener('mouseout', () => {
            lastText = "";
        });

        // Page load hone par Voice Guide ki state check karna
        if(localStorage.getItem('accessibility_tts') === 'true') {
            updateTTSUI(true);
        }
    </script>

    <!-- 🤖 CUSTOM CHATBOT UI (LEFT SIDE) START -->
    <!-- Chat Window (Hidden by default) -->
    <div id="custom-chat-widget" class="fixed bottom-40 left-6 z-50 flex flex-col items-start hidden shadow-2xl transition-all duration-300 origin-bottom-left">
        <div class="bg-white w-80 sm:w-96 rounded-2xl overflow-hidden border border-gray-200 flex flex-col shadow-2xl">
            <!-- Header -->
            <div class="bg-blue-950 text-white p-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-robot text-orange-500 text-xl"></i>
                    <span class="font-poppins font-bold text-sm tracking-wide">Drive Elite Bot</span>
                </div>
                <button onclick="toggleCustomChat()" class="text-white hover:text-orange-500 transition-colors focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <!-- Chat Messages Box -->
            <div id="custom-chat-box" class="h-72 p-4 overflow-y-auto bg-gray-50 flex flex-col gap-3 font-inter">
                <!-- Bot Welcome Message -->
                <div class="bg-gray-200 text-gray-800 p-3 rounded-tr-xl rounded-bl-xl rounded-br-xl max-w-[85%] self-start text-sm shadow-sm">
                    Hello! Welcome to Drive Elite. I am your smart assistant. Try asking about "rent", "location", or "documents".
                </div>
            </div>
            
            <!-- Input Field -->
            <div class="p-3 bg-white border-t border-gray-200 flex gap-2">
                <input type="text" id="custom-chat-input" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500 font-inter" placeholder="Type a message...">
                <button onclick="sendCustomMessage()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors focus:outline-none shadow-md">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Chat Floating Button -->
    <button onclick="toggleCustomChat()" class="fixed bottom-24 left-6 z-40 bg-blue-950 text-white w-14 h-14 rounded-full shadow-[0_10px_20px_rgba(23,37,84,0.4)] flex items-center justify-center text-2xl hover:bg-orange-500 transition-all duration-300 focus:outline-none border-2 border-white">
        <i class="fa-solid fa-robot"></i>
    </button>

    <!-- Chatbot Javascript Logic -->
    <script>
        function toggleCustomChat() {
            const chatWidget = document.getElementById('custom-chat-widget');
            chatWidget.classList.toggle('hidden');
        }

        async function sendCustomMessage() {
            const inputField = document.getElementById('custom-chat-input');
            const message = inputField.value.trim();
            if (!message) return; // Prevent empty messages

            const chatBox = document.getElementById('custom-chat-box');

            // 1. Show User Message in Chat
            chatBox.innerHTML += `
                <div class="bg-orange-500 text-white p-3 rounded-tl-xl rounded-bl-xl rounded-br-xl max-w-[85%] self-end text-sm shadow-sm">
                    ${message}
                </div>
            `;
            inputField.value = ''; // Clear input field
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom

            try {
                // 2. Send message to Laravel Backend via Fetch API
                const response = await fetch('{{ route("chatbot.reply") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                // 3. Show Bot Reply in Chat with a small delay for realism
                setTimeout(() => {
                    chatBox.innerHTML += `
                        <div class="bg-gray-200 text-gray-800 p-3 rounded-tr-xl rounded-bl-xl rounded-br-xl max-w-[85%] self-start text-sm shadow-sm">
                            ${data.reply}
                        </div>
                    `;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }, 400); // 400ms delay

            } catch (error) {
                console.error("Chatbot Error:", error);
                chatBox.innerHTML += `
                    <div class="bg-red-100 text-red-800 p-3 rounded-tr-xl rounded-bl-xl rounded-br-xl max-w-[85%] self-start text-sm shadow-sm">
                        Sorry, there was an error connecting to the server.
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        // Allow 'Enter' key to send message
        document.getElementById('custom-chat-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendCustomMessage();
            }
        });
    </script>
    <!-- 🤖 CUSTOM CHATBOT UI (END) -->

</body>
</html>