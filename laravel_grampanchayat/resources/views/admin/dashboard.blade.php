@extends('admin.layouts.app')

@section('title', 'डॅशबोर्ड')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">डॅशबोर्ड</h1>
    <p class="text-gray-600">स्वागत आहे, {{ Auth::guard('admin')->user()->name }}</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">नोटीस</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['notices'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bullhorn text-blue-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">बातम्या</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['news'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-newspaper text-green-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">योजना</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['schemes'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-yellow-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">नवीन चौकशी</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['inquiries'] }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-envelope text-red-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Visitor Stats -->
<div class="grid md:grid-cols-3 gap-4 mb-8">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">एकूण भेटीगार</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_visitors']) }}</p>
            </div>
            <i class="fas fa-users text-4xl text-orange-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-slate-700 to-slate-800 rounded-xl shadow p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-300 text-sm">आज</p>
                <p class="text-3xl font-bold">{{ number_format($stats['today_visitors']) }}</p>
            </div>
            <i class="fas fa-calendar-day text-4xl text-gray-500"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm">या महिन्यात</p>
                <p class="text-3xl font-bold">{{ number_format($stats['monthly_visitors']) }}</p>
            </div>
            <i class="fas fa-chart-line text-4xl text-emerald-200"></i>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Notices -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800"><i class="fas fa-bullhorn text-orange-500 mr-2"></i>अलीकडील नोटीस</h2>
            <a href="{{ route('admin.notices.index') }}" class="text-orange-500 text-sm hover:underline">सर्व पहा</a>
        </div>
        <div class="p-4">
            @forelse($recentNotices as $notice)
            <div class="flex items-start gap-3 py-3 border-b last:border-b-0">
                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bell text-blue-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 truncate">{{ $notice->title }}</p>
                    <p class="text-xs text-gray-500">{{ $notice->notice_date->format('d-m-Y') }}</p>
                </div>
                <a href="{{ route('admin.notices.edit', $notice) }}" class="text-gray-400 hover:text-orange-500">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">कोणतीही नोटीस नाही</p>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Inquiries -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800"><i class="fas fa-envelope text-orange-500 mr-2"></i>नवीन चौकशी</h2>
            <a href="{{ route('admin.inquiries.index') }}" class="text-orange-500 text-sm hover:underline">सर्व पहा</a>
        </div>
        <div class="p-4">
            @forelse($recentInquiries as $inquiry)
            <div class="flex items-start gap-3 py-3 border-b last:border-b-0">
                <div class="w-8 h-8 bg-red-100 rounded flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-red-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800">{{ $inquiry->name }}</p>
                    <p class="text-sm text-gray-600 truncate">{{ $inquiry->subject }}</p>
                    <p class="text-xs text-gray-500">{{ $inquiry->created_at->format('d-m-Y H:i') }}</p>
                </div>
                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-gray-400 hover:text-orange-500">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">कोणतीही नवीन चौकशी नाही</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 bg-white rounded-xl shadow p-6">
    <h2 class="font-bold text-gray-800 mb-4"><i class="fas fa-bolt text-orange-500 mr-2"></i>द्रुत कृती</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.notices.create') }}" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <i class="fas fa-plus-circle text-blue-500 text-xl"></i>
            <span class="font-medium text-gray-700">नवीन नोटीस</span>
        </a>
        <a href="{{ route('admin.news.create') }}" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <i class="fas fa-plus-circle text-green-500 text-xl"></i>
            <span class="font-medium text-gray-700">नवीन बातमी</span>
        </a>
        <a href="{{ route('admin.schemes.create') }}" class="flex items-center gap-3 p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
            <i class="fas fa-plus-circle text-yellow-500 text-xl"></i>
            <span class="font-medium text-gray-700">नवीन योजना</span>
        </a>
        <a href="{{ route('admin.gallery.create') }}" class="flex items-center gap-3 p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
            <i class="fas fa-plus-circle text-purple-500 text-xl"></i>
            <span class="font-medium text-gray-700">नवीन अल्बम</span>
        </a>
    </div>
</div>
@endsection
