@extends('admin.layouts.app')

@section('title', 'नोटीस यादी')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">नोटीस व्यवस्थापन</h1>
    <a href="{{ route('admin.notices.create') }}" class="bg-saffron text-white px-4 py-2 rounded-lg hover:bg-saffron-dark transition">
        <i class="fas fa-plus mr-2"></i>नवीन नोटीस
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">शीर्षक</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">तारीख</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">स्थिती</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ticker</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">क्रिया</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($notices as $notice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($notice->is_important)
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ Str::limit($notice->title, 50) }}</p>
                                @if($notice->file_path)
                                <p class="text-xs text-gray-500"><i class="fas fa-paperclip mr-1"></i>फाईल जोडलेली</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $notice->notice_date->format('d-m-Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $notice->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $notice->is_published ? 'प्रकाशित' : 'मसुदा' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $notice->show_in_ticker ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $notice->show_in_ticker ? 'हो' : 'नाही' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.notices.edit', $notice) }}" class="text-blue-500 hover:text-blue-700" title="संपादित करा">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="inline" onsubmit="return confirm('खात्री आहे? ही नोटीस हटवायची?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="हटवा">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-2"></i>
                        <p>कोणतीही नोटीस नाही</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($notices->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $notices->links() }}
    </div>
    @endif
</div>
@endsection
