@extends('admin.layouts.app')

@section('title', 'नवीन नोटीस जोडा')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.notices.index') }}" class="text-gray-600 hover:text-saffron">
        <i class="fas fa-arrow-left mr-2"></i>मागे जा
    </a>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">नवीन नोटीस जोडा</h1>
    
    <form action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">शीर्षक <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition">
                @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">नोटीस तारीख <span class="text-red-500">*</span></label>
                <input type="date" name="notice_date" value="{{ old('notice_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition">
                @error('notice_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">समाप्ती तारीख (ऐच्छिक)</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition">
                @error('expiry_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">वर्णन</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition resize-none">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">फाईल (PDF/Image - Max 5MB)</label>
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition">
                @error('file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex flex-wrap gap-6 mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}
                    class="w-5 h-5 text-saffron border-gray-300 rounded focus:ring-saffron">
                <span class="text-gray-700">महत्त्वाची नोटीस</span>
            </label>
            
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="show_in_ticker" value="1" {{ old('show_in_ticker', true) ? 'checked' : '' }}
                    class="w-5 h-5 text-saffron border-gray-300 rounded focus:ring-saffron">
                <span class="text-gray-700">Ticker मध्ये दाखवा</span>
            </label>
            
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                    class="w-5 h-5 text-saffron border-gray-300 rounded focus:ring-saffron">
                <span class="text-gray-700">प्रकाशित करा</span>
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="bg-saffron text-white px-6 py-3 rounded-lg font-semibold hover:bg-saffron-dark transition">
                <i class="fas fa-save mr-2"></i>जतन करा
            </button>
            <a href="{{ route('admin.notices.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                रद्द करा
            </a>
        </div>
    </form>
</div>
@endsection
