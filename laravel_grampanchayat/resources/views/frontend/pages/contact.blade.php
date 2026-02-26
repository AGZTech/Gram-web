@extends('frontend.layouts.main')

@section('title', 'संपर्क | ' . \App\Models\Setting::get('site_name'))

@section('content')
<!-- Page Header -->
<section class="bg-navy text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">संपर्क करा</h1>
        <p class="text-gray-300">आम्हाला तुमचा संदेश पाठवा</p>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-navy mb-6">
                        <i class="fas fa-paper-plane text-saffron mr-2"></i>
                        संदेश पाठवा
                    </h2>
                    
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    नाव <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition"
                                    placeholder="तुमचे नाव">
                                @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    मोबाईल नंबर <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition"
                                    placeholder="9876543210">
                                @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">
                                ईमेल (ऐच्छिक)
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition"
                                placeholder="email@example.com">
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">
                                विषय <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition"
                                placeholder="संदेशाचा विषय">
                            @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">
                                संदेश <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-saffron focus:border-saffron transition resize-none"
                                placeholder="तुमचा संदेश येथे लिहा...">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full md:w-auto bg-saffron text-white px-8 py-3 rounded-lg font-semibold hover:bg-saffron-dark transition">
                            <i class="fas fa-paper-plane mr-2"></i>
                            संदेश पाठवा
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div>
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-navy mb-4">
                        <i class="fas fa-map-marker-alt text-saffron mr-2"></i>
                        पत्ता
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        {!! nl2br(e(\App\Models\Setting::get('contact_address', 'ग्रामपंचायत कार्यालय, आदर्शगाव'))) !!}
                    </p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-navy mb-4">
                        <i class="fas fa-phone-alt text-saffron mr-2"></i>
                        फोन
                    </h3>
                    <p class="text-gray-600">
                        <a href="tel:{{ \App\Models\Setting::get('contact_phone') }}" class="hover:text-saffron transition">
                            {{ \App\Models\Setting::get('contact_phone', '02XX-XXXXXX') }}
                        </a>
                    </p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-navy mb-4">
                        <i class="fas fa-envelope text-saffron mr-2"></i>
                        ईमेल
                    </h3>
                    <p class="text-gray-600">
                        <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}" class="hover:text-saffron transition">
                            {{ \App\Models\Setting::get('contact_email', 'grampanchayat@gov.in') }}
                        </a>
                    </p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-navy mb-4">
                        <i class="fas fa-clock text-saffron mr-2"></i>
                        कार्यालयीन वेळ
                    </h3>
                    <p class="text-gray-600">
                        सोमवार - शनिवार<br>
                        सकाळी 10:00 - संध्याकाळी 5:00<br>
                        <span class="text-red-500">रविवार बंद</span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Google Map -->
        @if(\App\Models\Setting::get('google_map_embed'))
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h3 class="text-xl font-bold text-navy mb-4">
                    <i class="fas fa-map text-saffron mr-2"></i>
                    नकाशा
                </h3>
                <div class="aspect-video rounded-lg overflow-hidden">
                    {!! \App\Models\Setting::get('google_map_embed') !!}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
