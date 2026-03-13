@extends('layouts.dashboard')

@section('title', 'Buku Disukai')

@section('content')
    <div class="flex flex-col gap-6">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-stone-900">Buku Disukai</h1>
                <p class="text-sm text-stone-500">Koleksi yang kamu tandai sebagai favorit</p>
                <br>
                <h2 class="text-sm font-semibold text-stone-900">Total koleksi: {{ $likedBooks->count() }}</h2>
            </div>
        </header>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @php
            $likedBooks = $likedBooks ?? collect();
        @endphp

        @if ($likedBooks->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-200 bg-white/70 p-6 text-sm text-stone-500">
                Kamu belum menyukai buku apapun.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-5    ">
                @foreach ($likedBooks as $book)
                    <article class="flex flex-col gap-3 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                        <div class="h-40 overflow-hidden rounded-2xl bg-stone-50">
                            <img
                                src="{{ $book->Cover ?: 'https://img.freepik.com/premium-vector/online-bookstore-logo-icon-vector__540252-1036.jpg?w=1800' }}"
                                alt="Cover {{ $book->Judul }}"
                                class="h-full w-full object-cover"
                            >
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-semibold text-stone-900">{{ $book->Judul }}</p>
                            <p class="text-xs text-stone-500">Penulis : {{ $book->Penulis }}</p>
                            <p class="text-xs text-stone-500">Penerbit : {{ $book->Penerbit }}</p>
                        </div>
                        <div class="flex items-center justify-between text-xs text-stone-500">
                            <span class="inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 text-rose-500">
                                    <path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.42 3.42 5 5.5 5c1.54 0 3.04.99 3.57 2.36h1.87C11.46 5.99 12.96 5 14.5 5 16.58 5 18 6.42 18 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span>{{ $book->liked_by_count ?: 0 }} suka</span>
                            </span>
                            <form method="POST" action="{{ route('buku.like.destroy', $book->BukuID) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="rounded-full border border-rose-200 px-3 py-1 text-[10px] font-semibold text-rose-600 transition hover:border-rose-500 hover:bg-rose-50"
                                >
                                    Hapus dari daftar
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
