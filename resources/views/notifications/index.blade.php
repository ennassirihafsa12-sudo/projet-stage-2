@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('header-actions')
    @if ($nonLues > 0)
        <form action="{{ route('notifications.marquer-tout-lu') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-blue-600 hover:underline">Tout marquer comme lu</button>
        </form>
    @endif
@endsection

@section('content')
    <div class="divide-y divide-slate-100 rounded-xl bg-white shadow-sm">
        @forelse ($notifications as $notification)
            <div class="flex gap-4 px-6 py-4 {{ $notification->lu ? 'opacity-70' : 'bg-blue-50/30' }}">
                <div class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $notification->lu ? 'bg-slate-300' : 'bg-blue-500' }}"></div>
                <div class="flex-1">
                    <p class="font-medium text-slate-900">{{ $notification->titre }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                    <p class="mt-2 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs">
                    <span class="rounded-full bg-slate-100 px-2 py-0.5">{{ $notification->type->label() }}</span>
                </span>
            </div>
        @empty
            <p class="px-6 py-8 text-center text-slate-500">Aucune notification.</p>
        @endforelse
    </div>
@endsection
