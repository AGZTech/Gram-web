<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel') | {{ \App\Models\Setting::get('site_name', 'ग्रामपंचायत') }}</title>
    
    <!-- Google Fonts - Mukta -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
        * { font-family: 'Mukta', sans-serif; }
        .sidebar-link.active { background-color: #f97316; color: white; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-mukta">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-navy text-white fixed h-full overflow-y-auto z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
            <!-- Logo -->
            <div class="p-4 border-b border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-saffron rounded-lg flex items-center justify-center">
                        <i class="fas fa-landmark text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight">ग्रामपंचायत</h1>
                        <p class="text-xs text-gray-400">प्रशासन पॅनेल</p>
                    </div>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>डॅशबोर्ड</span>
                </a>
                
                <p class="text-xs text-gray-500 uppercase tracking-wider pt-4 pb-2">सामग्री व्यवस्थापन</p>
                
                <a href="{{ route('admin.pages.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>पेजेस</span>
                </a>
                
                <a href="{{ route('admin.notices.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.notices*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn w-5"></i>
                    <span>नोटीस</span>
                </a>
                
                <a href="{{ route('admin.news.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper w-5"></i>
                    <span>बातम्या</span>
                </a>
                
                <a href="{{ route('admin.schemes.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.schemes*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd w-5"></i>
                    <span>योजना</span>
                </a>
                
                <a href="{{ route('admin.works.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.works*') ? 'active' : '' }}">
                    <i class="fas fa-hard-hat w-5"></i>
                    <span>विकासकामे</span>
                </a>
                
                <a href="{{ route('admin.services.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog w-5"></i>
                    <span>लोकसेवा</span>
                </a>
                
                <a href="{{ route('admin.gallery.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                    <i class="fas fa-images w-5"></i>
                    <span>गॅलरी</span>
                </a>
                
                <a href="{{ route('admin.members.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie w-5"></i>
                    <span>सदस्य</span>
                </a>
                
                <p class="text-xs text-gray-500 uppercase tracking-wider pt-4 pb-2">चौकशी</p>
                
                <a href="{{ route('admin.inquiries.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.inquiries*') ? 'active' : '' }}">
                    <i class="fas fa-envelope w-5"></i>
                    <span>संपर्क चौकशी</span>
                    @php $newInquiries = \App\Models\ContactInquiry::where('status', 'new')->count(); @endphp
                    @if($newInquiries > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $newInquiries }}</span>
                    @endif
                </a>
                
                <p class="text-xs text-gray-500 uppercase tracking-wider pt-4 pb-2">सेटिंग्ज</p>
                
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span>सेटिंग्ज</span>
                </a>
                
                @if(Auth::guard('admin')->user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>वापरकर्ते</span>
                </a>
                
                <a href="{{ route('admin.backup.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition {{ request()->routeIs('admin.backup*') ? 'active' : '' }}">
                    <i class="fas fa-database w-5"></i>
                    <span>बॅकअप</span>
                </a>
                @endif
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm sticky top-0 z-20">
                <div class="flex items-center justify-between px-4 py-3">
                    <button id="sidebar-toggle" class="lg:hidden text-gray-600 text-xl">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" target="_blank" class="text-gray-600 hover:text-saffron transition" title="वेबसाईट पहा">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-gray-700">
                                <div class="w-8 h-8 bg-saffron rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="hidden sm:inline">{{ Auth::guard('admin')->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 top-full mt-1 bg-white shadow-lg rounded-lg py-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <p class="px-4 py-2 text-sm text-gray-500 border-b">
                                    {{ Auth::guard('admin')->user()->isSuperAdmin() ? 'Super Admin' : 'Editor' }}
                                </p>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                        <i class="fas fa-sign-out-alt mr-2"></i>लॉगआउट
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <div class="px-6 pt-4">
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                
                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            
            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>
    
    <script>
        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });
        
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
