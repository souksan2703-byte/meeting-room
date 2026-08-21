@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-1">Notifications</h1>
    <p class="text-gray-500 mb-6">การแจ้งเตือนทั้งหมดของคุณ</p>

    <div class="bg-white rounded-lg shadow-sm divide-y">
        @forelse ($notifications as $notification)
            <a href="{{ $notification->link ?? '#' }}" class="flex items-start gap-3 p-4 hover:bg-gray-50">
                <span class="text-xl shrink-0">🔔</span>
                <div class="min-w-0">
                    <p class="font-medium text-sm">{{ $notification->title }}</p>
                    @if ($notification->body)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $notification->body }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </a>
        @empty
            <p class="p-6 text-center text-gray-400 text-sm">ยังไม่มีการแจ้งเตือน</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection