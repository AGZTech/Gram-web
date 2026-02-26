<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'ग्रामपंचायत आदर्शगाव')</title>
    <meta name="description" content="@yield('meta_description', 'ग्रामपंचायत आदर्शगाव ची अधिकृत वेबसाईट')">
    <meta name="keywords" content="@yield('meta_keywords', 'ग्रामपंचायत, आदर्शगाव, महाराष्ट्र')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts - Mukta (Marathi) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'mukta': ['Mukta', 'sans-serif'],
                    },
                    colors: {
                        'navy': '#0f172a',
                        'navy-light': '#1e293b',
                        'saffron': '#f97316',
                        'saffron-dark': '#ea580c',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            font-family: 'Mukta', sans-serif;
        }
        
        /* Notice Ticker */
        .ticker-wrap {
            overflow: hidden;
            background: linear-gradient(90deg, #f97316, #ea580c);
        }
        
        .ticker {
            display: inline-flex;
            animation: ticker 30s linear infinite;
        }
        
        .ticker:hover {
            animation-play-state: paused;
        }
        
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .ticker-item {
            white-space: nowrap;
            padding: 0 2rem;
        }
        
        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #0f172a;
            border-radius: 4px;
        }
        
        /* Hero Slider */
        .hero-slide {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-mukta">
    <!-- Top Bar -->
    <div class="bg-navy text-white py-2 text-sm">
        <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'grampanchayat@gov.in') }}" class="hover:text-saffron transition">
                    <i class="fas fa-envelope mr-1"></i>
                    {{ \App\Models\Setting::get('contact_email', 'grampanchayat@gov.in') }}
                </a>
                <a href="tel:{{ \App\Models\Setting::get('contact_phone', '') }}" class="hover:text-saffron transition hidden sm:inline">
                    <i class="fas fa-phone mr-1"></i>
                    {{ \App\Models\Setting::get('contact_phone', '02XX-XXXXXX') }}
                </a>
            </div>
            <div class="flex items-center gap-3">
                @if(\App\Models\Setting::get('facebook_url'))
                <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" class="hover:text-saffron transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                @endif
                @if(\App\Models\Setting::get('twitter_url'))
                <a href="{{ \App\Models\Setting::get('twitter_url') }}" target="_blank" class="hover:text-saffron transition">
                    <i class="fab fa-twitter"></i>
                </a>
                @endif
                <a href="{{ route('admin.login') }}" class="hover:text-saffron transition ml-2">
                    <i class="fas fa-user-shield mr-1"></i> प्रशासक
                </a>
            </div>
        </div>
    </div>
    
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-3">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-saffron rounded-full flex items-center justify-center">
                        <i class="fas fa-landmark text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-navy leading-tight">
                            {{ \App\Models\Setting::get('site_name', 'ग्रामपंचायत आदर्शगाव') }}
                        </h1>
                        <p class="text-xs text-gray-500">{{ \App\Models\Setting::get('site_tagline', 'स्वच्छ गाव, समृद्ध गाव') }}</p>
                    </div>
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-navy text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('home') ? 'text-saffron font-semibold' : '' }}">
                        मुखपृष्ठ
                    </a>
                    <div class="relative group">
                        <button class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition flex items-center gap-1">
                            ग्रामपंचायत <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute top-full left-0 bg-white shadow-lg rounded-lg py-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('about') }}" class="block px-4 py-2 hover:bg-gray-50 hover:text-saffron">आमच्याबद्दल</a>
                            <a href="{{ route('history') }}" class="block px-4 py-2 hover:bg-gray-50 hover:text-saffron">इतिहास</a>
                            <a href="{{ route('members') }}" class="block px-4 py-2 hover:bg-gray-50 hover:text-saffron">सदस्य</a>
                        </div>
                    </div>
                    <a href="{{ route('services.index') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('services.*') ? 'text-saffron font-semibold' : '' }}">
                        लोकसेवा
                    </a>
                    <a href="{{ route('schemes.index') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('schemes.*') ? 'text-saffron font-semibold' : '' }}">
                        योजना
                    </a>
                    <a href="{{ route('works.index') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('works.*') ? 'text-saffron font-semibold' : '' }}">
                        विकासकामे
                    </a>
                    <a href="{{ route('notices.index') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('notices.*') ? 'text-saffron font-semibold' : '' }}">
                        नोटीस
                    </a>
                    <a href="{{ route('news.index') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('news.*') ? 'text-saffron font-semibold' : '' }}">
                        बातम्या
                    </a>
                    <a href="{{ route('gallery.index') }}" class="px-4 py-2 text-navy hover:text-saffron hover:bg-gray-50 rounded transition {{ request()->routeIs('gallery.*') ? 'text-saffron font-semibold' : '' }}">
                        गॅलरी
                    </a>
                    <a href="{{ route('contact.index') }}" class="px-4 py-2 bg-saffron text-white rounded hover:bg-saffron-dark transition">
                        संपर्क
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <nav id="mobile-menu" class="lg:hidden hidden bg-white border-t">
            <div class="container mx-auto px-4 py-4">
                <a href="{{ route('home') }}" class="block py-2 text-navy hover:text-saffron">मुखपृष्ठ</a>
                <a href="{{ route('about') }}" class="block py-2 text-navy hover:text-saffron">आमच्याबद्दल</a>
                <a href="{{ route('history') }}" class="block py-2 text-navy hover:text-saffron">इतिहास</a>
                <a href="{{ route('members') }}" class="block py-2 text-navy hover:text-saffron">सदस्य</a>
                <a href="{{ route('services.index') }}" class="block py-2 text-navy hover:text-saffron">लोकसेवा</a>
                <a href="{{ route('schemes.index') }}" class="block py-2 text-navy hover:text-saffron">योजना</a>
                <a href="{{ route('works.index') }}" class="block py-2 text-navy hover:text-saffron">विकासकामे</a>
                <a href="{{ route('notices.index') }}" class="block py-2 text-navy hover:text-saffron">नोटीस</a>
                <a href="{{ route('news.index') }}" class="block py-2 text-navy hover:text-saffron">बातम्या</a>
                <a href="{{ route('gallery.index') }}" class="block py-2 text-navy hover:text-saffron">गॅलरी</a>
                <a href="{{ route('contact.index') }}" class="block py-2 text-saffron font-semibold">संपर्क</a>
            </div>
        </nav>
    </header>
    
    <!-- Notice Ticker -->
    @if(isset($notices) && $notices->count() > 0)
    <div class="ticker-wrap py-2">
        <div class="ticker text-white">
            @foreach($notices as $notice)
                @for($i = 0; $i < 2; $i++)
                <span class="ticker-item">
                    <i class="fas fa-bullhorn mr-2"></i>
                    <strong>{{ $notice->formatted_date }}:</strong> {{ $notice->title }}
                    @if($notice->is_important)
                    <span class="bg-white text-saffron px-2 py-0.5 rounded text-xs ml-2">महत्त्वाचे</span>
                    @endif
                </span>
                @endfor
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 container mx-auto mt-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 container mx-auto mt-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-navy text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-saffron rounded-full flex items-center justify-center">
                            <i class="fas fa-landmark text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Models\Setting::get('site_name', 'ग्रामपंचायत आदर्शगाव') }}</h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        {{ \App\Models\Setting::get('site_tagline', 'स्वच्छ गाव, समृद्ध गाव') }}
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-saffron">द्रुत दुवे</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('about') }}" class="hover:text-saffron transition"><i class="fas fa-angle-right mr-2"></i>आमच्याबद्दल</a></li>
                        <li><a href="{{ route('services.index') }}" class="hover:text-saffron transition"><i class="fas fa-angle-right mr-2"></i>लोकसेवा</a></li>
                        <li><a href="{{ route('schemes.index') }}" class="hover:text-saffron transition"><i class="fas fa-angle-right mr-2"></i>योजना</a></li>
                        <li><a href="{{ route('works.index') }}" class="hover:text-saffron transition"><i class="fas fa-angle-right mr-2"></i>विकासकामे</a></li>
                        <li><a href="{{ route('contact.index') }}" class="hover:text-saffron transition"><i class="fas fa-angle-right mr-2"></i>संपर्क</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-saffron">संपर्क</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-saffron"></i>
                            <span>{!! nl2br(e(\App\Models\Setting::get('contact_address', 'ग्रामपंचायत कार्यालय'))) !!}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-saffron"></i>
                            <span>{{ \App\Models\Setting::get('contact_phone', '02XX-XXXXXX') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-saffron"></i>
                            <span>{{ \App\Models\Setting::get('contact_email', 'grampanchayat@gov.in') }}</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Visitor Counter -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-saffron">भेटीगार संख्या</h4>
                    <div class="bg-navy-light rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-users text-2xl text-saffron"></i>
                            <div>
                                <p class="text-2xl font-bold">{{ number_format(\App\Models\Visitor::getTotalVisitors()) }}</p>
                                <p class="text-xs text-gray-400">एकूण भेटीगार</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400">
                            <p><i class="fas fa-calendar-day mr-2"></i>आज: {{ \App\Models\Visitor::getTodayVisitors() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 py-4">
            <div class="container mx-auto px-4 text-center text-gray-400 text-sm">
                <p>{{ \App\Models\Setting::get('footer_text', '© ' . date('Y') . ' ग्रामपंचायत आदर्शगाव. सर्व हक्क राखीव.') }}</p>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top -->
    <button id="back-to-top" class="fixed bottom-6 right-6 w-12 h-12 bg-saffron text-white rounded-full shadow-lg hidden hover:bg-saffron-dark transition">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // Back to Top
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.remove('hidden');
            } else {
                backToTop.classList.add('hidden');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
