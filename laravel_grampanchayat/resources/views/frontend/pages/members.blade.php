@extends('frontend.layouts.main')

@section('title', 'सदस्य | ' . \App\Models\Setting::get('site_name'))

@section('content')
<!-- Page Header -->
<section class="bg-navy text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">ग्रामपंचायत सदस्य</h1>
        <p class="text-gray-300">निर्वाचित प्रतिनिधी आणि अधिकारी</p>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($members as $member)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                <div class="bg-gradient-to-r from-navy to-navy-light p-6 text-center">
                    @if($member->photo)
                    <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" 
                        class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-saffron mb-4">
                    @else
                    <div class="w-32 h-32 rounded-full mx-auto bg-saffron flex items-center justify-center mb-4">
                        <i class="fas fa-user text-5xl text-white"></i>
                    </div>
                    @endif
                    <h3 class="text-xl font-bold text-white">{{ $member->name }}</h3>
                    <p class="text-saffron font-semibold">{{ $member->designation_label }}</p>
                    @if($member->ward_number)
                    <p class="text-gray-400 text-sm">{{ $member->ward_number }}</p>
                    @endif
                </div>
                <div class="p-5">
                    @if($member->bio)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $member->bio }}</p>
                    @endif
                    
                    <div class="space-y-2 text-sm">
                        @if($member->phone)
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-phone text-saffron w-5"></i>
                            <a href="tel:{{ $member->phone }}" class="hover:text-saffron">{{ $member->phone }}</a>
                        </p>
                        @endif
                        
                        @if($member->email)
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-envelope text-saffron w-5"></i>
                            <a href="mailto:{{ $member->email }}" class="hover:text-saffron">{{ $member->email }}</a>
                        </p>
                        @endif
                        
                        @if($member->term_start && $member->term_end)
                        <p class="flex items-center gap-2 text-gray-500 text-xs">
                            <i class="fas fa-calendar text-saffron w-5"></i>
                            कार्यकाळ: {{ $member->term_start->format('Y') }} - {{ $member->term_end->format('Y') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">सध्या कोणतेही सदस्य नाहीत</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
