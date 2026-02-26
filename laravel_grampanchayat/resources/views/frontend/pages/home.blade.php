@extends('frontend.layouts.main')

@section('title', 'मुखपृष्ठ | ' . \App\Models\Setting::get('site_name', 'ग्रामपंचायत आदर्शगाव'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-navy text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-gradient-to-r from-navy to-transparent"></div>
    </div>
    <div class="container mx-auto px-4 py-16 md:py-24 relative">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">
                    स्वागत आहे <br>
                    <span class="text-saffron">{{ \App\Models\Setting::get('site_name', 'ग्रामपंचायत आदर्शगाव') }}</span>
                </h1>
                <p class="text-lg text-gray-300 mb-8">
                    {{ \App\Models\Setting::get('site_tagline', 'स्वच्छ गाव, समृद्ध गाव') }} - डिजिटल इंडिया अभियानांतर्गत आमची अधिकृत वेबसाईट
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('services.index') }}" class="bg-saffron text-white px-6 py-3 rounded-lg font-semibold hover:bg-saffron-dark transition">
                        <i class="fas fa-hand-holding-heart mr-2"></i>लोकसेवा
                    </a>
                    <a href="{{ route('schemes.index') }}" class="bg-white text-navy px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        <i class="fas fa-file-alt mr-2"></i>योजना माहिती
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="relative">
                    <div class="w-80 h-80 bg-saffron/20 rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="w-64 h-64 bg-saffron/30 rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="relative z-10 text-center">
                        <i class="fas fa-landmark text-8xl text-saffron mb-4"></i>
                        <p class="text-2xl font-bold">डिजिटल ग्रामपंचायत</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Links -->
<section class="py-12 bg-white -mt-8 relative z-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('services.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-users-cog text-3xl mb-3"></i>
                <p class="font-semibold">लोकसेवा</p>
            </a>
            <a href="{{ route('schemes.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-hand-holding-usd text-3xl mb-3"></i>
                <p class="font-semibold">योजना</p>
            </a>
            <a href="{{ route('works.index') }}" class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-hard-hat text-3xl mb-3"></i>
                <p class="font-semibold">विकासकामे</p>
            </a>
            <a href="{{ route('notices.index') }}" class="bg-gradient-to-br from-red-500 to-red-600 text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-bullhorn text-3xl mb-3"></i>
                <p class="font-semibold">नोटीस</p>
            </a>
            <a href="{{ route('gallery.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-images text-3xl mb-3"></i>
                <p class="font-semibold">गॅलरी</p>
            </a>
            <a href="{{ route('contact.index') }}" class="bg-gradient-to-br from-saffron to-saffron-dark text-white p-6 rounded-xl text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-phone-alt text-3xl mb-3"></i>
                <p class="font-semibold">संपर्क</p>
            </a>
        </div>
    </div>
</section>

<!-- Sarpanch Message -->
@if($sarpanch)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3 bg-navy p-8 text-center">
                    @if($sarpanch->photo)
                    <img src="{{ asset($sarpanch->photo) }}" alt="{{ $sarpanch->name }}" class="w-40 h-40 rounded-full mx-auto object-cover border-4 border-saffron mb-4">
                    @else
                    <div class="w-40 h-40 rounded-full mx-auto bg-saffron flex items-center justify-center mb-4">
                        <i class="fas fa-user text-6xl text-white"></i>
                    </div>
                    @endif
                    <h3 class="text-xl font-bold text-white">{{ $sarpanch->name }}</h3>
                    <p class="text-saffron font-semibold">सरपंच</p>
                    @if($sarpanch->phone)
                    <p class="text-gray-400 text-sm mt-2"><i class="fas fa-phone mr-1"></i> {{ $sarpanch->phone }}</p>
                    @endif
                </div>
                <div class="md:w-2/3 p-8">
                    <h2 class="text-2xl font-bold text-navy mb-4">
                        <i class="fas fa-quote-left text-saffron mr-2"></i>
                        सरपंच मनोगत
                    </h2>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        {{ \App\Models\Setting::get('sarpanch_message', 'आपल्या गावाच्या सर्वांगीण विकासासाठी आम्ही कटिबद्ध आहोत.') }}
                    </p>
                    @if($sarpanch->bio)
                    <p class="text-gray-500 mt-4 text-sm">{{ $sarpanch->bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Latest News & Notices -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- News -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-navy">
                        <i class="fas fa-newspaper text-saffron mr-2"></i>
                        ताज्या बातम्या
                    </h2>
                    <a href="{{ route('news.index') }}" class="text-saffron hover:underline font-semibold">
                        सर्व पहा <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($latestNews->take(4) as $news)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        @if($news->featured_image)
                        <img src="{{ asset($news->featured_image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-navy to-navy-light flex items-center justify-center">
                            <i class="fas fa-newspaper text-4xl text-saffron"></i>
                        </div>
                        @endif
                        <div class="p-5">
                            <p class="text-xs text-saffron font-semibold mb-2">
                                <i class="far fa-calendar mr-1"></i>{{ $news->formatted_date }}
                            </p>
                            <h3 class="font-bold text-navy mb-2 line-clamp-2">{{ $news->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $news->excerpt }}</p>
                            <a href="{{ route('news.show', $news->slug) }}" class="text-saffron text-sm font-semibold mt-3 inline-block hover:underline">
                                अधिक वाचा <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            
            <!-- Notices -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-navy">
                        <i class="fas fa-bullhorn text-saffron mr-2"></i>
                        नोटीस बोर्ड
                    </h2>
                    <a href="{{ route('notices.index') }}" class="text-saffron hover:underline font-semibold text-sm">
                        सर्व <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 max-h-[500px] overflow-y-auto">
                    @forelse($notices as $notice)
                    <a href="{{ route('notices.show', $notice->id) }}" class="block p-3 border-b last:border-b-0 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-{{ $notice->is_important ? 'red' : 'blue' }}-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-{{ $notice->is_important ? 'exclamation' : 'bell' }} text-{{ $notice->is_important ? 'red' : 'blue' }}-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-navy text-sm line-clamp-2">{{ $notice->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-calendar mr-1"></i>{{ $notice->formatted_date }}
                                    @if($notice->is_important)
                                    <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs ml-2">महत्त्वाचे</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <p class="text-gray-500 text-center py-4">सध्या कोणतीही नोटीस नाही</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Schemes Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-navy mb-2">
                <i class="fas fa-hand-holding-heart text-saffron mr-2"></i>
                शासकीय योजना
            </h2>
            <p class="text-gray-600">विविध शासकीय योजनांची माहिती मिळवा</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($schemes->take(6) as $scheme)
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition border-l-4 border-saffron">
                <h3 class="font-bold text-navy mb-3">{{ $scheme->title }}</h3>
                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ Str::limit(strip_tags($scheme->description), 120) }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">{{ $scheme->department }}</span>
                    <a href="{{ route('schemes.show', $scheme->slug) }}" class="text-saffron font-semibold text-sm hover:underline">
                        माहिती पहा <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('schemes.index') }}" class="inline-block bg-navy text-white px-8 py-3 rounded-lg font-semibold hover:bg-navy-light transition">
                सर्व योजना पहा <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Development Works -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-navy mb-2">
                <i class="fas fa-hard-hat text-saffron mr-2"></i>
                विकासकामे
            </h2>
            <p class="text-gray-600">गावातील सुरू असलेली आणि पूर्ण झालेली विकासकामे</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($recentWorks as $work)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="md:flex">
                    @if($work->featured_image)
                    <div class="md:w-1/3">
                        <img src="{{ asset($work->featured_image) }}" alt="{{ $work->title }}" class="w-full h-full object-cover min-h-[150px]">
                    </div>
                    @endif
                    <div class="p-5 {{ $work->featured_image ? 'md:w-2/3' : '' }}">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $work->status_color }}">
                                {{ $work->status_label }}
                            </span>
                        </div>
                        <h3 class="font-bold text-navy mb-2">{{ $work->title }}</h3>
                        @if($work->location)
                        <p class="text-gray-500 text-sm mb-2"><i class="fas fa-map-marker-alt mr-1"></i> {{ $work->location }}</p>
                        @endif
                        @if($work->budget)
                        <p class="text-gray-600 text-sm mb-3">
                            <strong>अंदाजपत्रक:</strong> ₹{{ number_format($work->budget) }}
                        </p>
                        @endif
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-saffron h-2.5 rounded-full" style="width: {{ $work->progress_percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500">प्रगती: {{ $work->progress_percentage }}%</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('works.index') }}" class="inline-block bg-saffron text-white px-8 py-3 rounded-lg font-semibold hover:bg-saffron-dark transition">
                सर्व विकासकामे पहा <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
@if($albums->count() > 0)
<section class="py-12 bg-navy text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-2">
                <i class="fas fa-images text-saffron mr-2"></i>
                फोटो गॅलरी
            </h2>
            <p class="text-gray-400">गावातील विविध कार्यक्रमांचे फोटो</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($albums->take(4) as $album)
            <a href="{{ route('gallery.show', $album->slug) }}" class="group relative overflow-hidden rounded-xl">
                @if($album->cover_image)
                <img src="{{ asset($album->cover_image) }}" alt="{{ $album->title }}" class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                @else
                <div class="w-full h-48 bg-navy-light flex items-center justify-center">
                    <i class="fas fa-images text-4xl text-saffron"></i>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-4">
                    <div>
                        <h3 class="font-bold text-white">{{ $album->title }}</h3>
                        <p class="text-gray-300 text-sm">{{ $album->photos_count }} फोटो</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('gallery.index') }}" class="inline-block bg-saffron text-white px-8 py-3 rounded-lg font-semibold hover:bg-saffron-dark transition">
                संपूर्ण गॅलरी पहा <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Contact CTA -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="bg-gradient-to-r from-saffron to-saffron-dark rounded-2xl p-8 md:p-12 text-white text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">आमच्याशी संपर्क साधा</h2>
            <p class="text-lg mb-6 opacity-90">कोणतीही माहिती, तक्रार किंवा सूचना असल्यास आमच्याशी संपर्क करा</p>
            <a href="{{ route('contact.index') }}" class="inline-block bg-white text-saffron px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-envelope mr-2"></i>संपर्क करा
            </a>
        </div>
    </div>
</section>
@endsection
