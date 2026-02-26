<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>प्रशासक लॉगिन | {{ \App\Models\Setting::get('site_name', 'ग्रामपंचायत') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * { font-family: 'Mukta', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 to-slate-800 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-landmark text-white text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ \App\Models\Setting::get('site_name', 'ग्रामपंचायत आदर्शगाव') }}</h1>
            <p class="text-gray-400">प्रशासक पॅनेल लॉगिन</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
            @endif
            
            @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
            @endif
            
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-1 text-orange-500"></i> ईमेल
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        placeholder="admin@example.com">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-1 text-orange-500"></i> पासवर्ड
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="ml-2 text-gray-600 text-sm">मला लक्षात ठेवा</span>
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    लॉगिन करा
                </button>
            </form>
        </div>
        
        <!-- Back to Website -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-arrow-left mr-1"></i> वेबसाईटवर परत जा
            </a>
        </div>
        
        <!-- Demo Credentials -->
        <div class="mt-6 bg-slate-700/50 rounded-lg p-4 text-center">
            <p class="text-gray-300 text-sm mb-2">Demo Login:</p>
            <p class="text-gray-400 text-xs">Email: admin@grampanchayat.gov.in</p>
            <p class="text-gray-400 text-xs">Password: Admin@123</p>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
