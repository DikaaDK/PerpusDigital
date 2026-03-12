@extends('layouts.dashboard')

@section('title', 'Daftar Buku')

@section('content')
    @php
        $canManage = $canManage ?? false;
        $likedBookIds = $likedBookIds ?? [];
        $recentReviews = $recentReviews ?? collect();
    @endphp

    <div class="flex flex-col gap-6">
        <header class="flex flex-col gap-1">
            <h1 class="text-3xl font-semibold text-stone-900">Daftar Buku</h1>
            <p class="text-sm text-stone-500">Semua Buku yang ada di sistem perpustakaan</p>
        </header>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="space-y-6">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-stone-900">Total koleksi: {{ $books->count() }}</h2>
                    </div>
                    @if ($canManage)
                        <button
                            type="button"
                            data-modal-trigger
                            class="inline-flex items-center gap-2 rounded-full p-3 text-white bg-blue-500 hover:bg-blue-600"
                        >
                            <span class="items-center justify-center rounded-ful">
                            Tambah buku
                        </button>
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
                    @forelse ($books as $book)
                        <article class="flex flex-col gap-3 rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                            <div class="h-40 overflow-hidden rounded-2xl bg-stone-50">
                                <img
                                    src="{{ $book->Cover ?: 'https://img.freepik.com/premium-vector/online-bookstore-logo-icon-vector__540252-1036.jpg?w=1800' }}"
                                    alt="Cover {{ $book->Judul }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-stone-900">{{ $book->Judul }}</p>
                                <p class="text-xs text-stone-500">Penulis : {{ $book->Penulis }}</p>
                                <p class="text-xs text-stone-500">Penerbit : {{ $book->Penerbit }}</p>
                                <p class="text-xs text-stone-500">Tahun : {{ $book->TahunTerbit }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-2 text-xs text-stone-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 text-rose-500">
                                        <path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.42 3.42 5 5.5 5c1.54 0 3.04.99 3.57 2.36h1.87C11.46 5.99 12.96 5 14.5 5 16.58 5 18 6.42 18 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span>{{ $book->liked_by_count ?: 0 }} suka</span>
                                </span>
                                <div class="flex items-center gap-2">
                                    @if (in_array($book->BukuID, $likedBookIds, true))
                                        <form method="POST" action="{{ route('buku.like.destroy', $book->BukuID) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-full border border-stone-200 px-3 py-1 text-[10px] font-semibold text-stone-600 transition hover:border-rose-500 hover:text-rose-600"
                                            >
                                                Tidak suka
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('buku.like.store', $book->BukuID) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-full border border-stone-200 bg-white px-3 py-1 text-[10px] font-semibold text-rose-600 transition hover:border-rose-500 hover:text-rose-700"
                                            >
                                                Suka
                                            </button>
                                        </form>
                                    @endif
                                    @if ($canManage)
                                        <form method="POST" action="{{ route('buku.destroy', $book->BukuID) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-[10px] font-semibold text-rose-600 transition hover:border-rose-500 hover:bg-rose-100"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-stone-100 pt-3">
                                <div class="flex items-center justify-between text-xs text-stone-500">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 text-amber-500">
                                            <path fill="currentColor" d="M12 17.75l-6.18 3.25 1.18-6.88-5-4.87 6.9-1 3.1-6.28 3.1 6.28 6.9 1-5 4.87 1.18 6.88z" />
                                        </svg>
                                        <span class="font-semibold text-stone-900">{{ $book->reviews_avg_rating ? number_format($book->reviews_avg_rating, 1) : '0.0' }}</span>
                                        <span class="text-stone-400">({{ $book->reviews_count ?: 0 }} ulasan)</span>
                                    </div>
                                </div>

                                @if (auth()->user()?->role === 'peminjam')
                                    @php
                                        $isReviewing = old('book_id') == $book->BukuID;
                                        $selectedRating = $isReviewing ? old('rating', 0) : 0;
                                        $reviewText = $isReviewing ? old('ulasan', '') : '';
                                    @endphp
                                    <details class="mt-2 rounded-2xl border border-stone-100 bg-stone-50/80" @if ($isReviewing) open @endif>
                                        <summary class="flex cursor-pointer items-center justify-between p-3 text-xs font-semibold text-blue-600 transition">
                                            <span>Tambah ulasan & rating</span>
                                            <span class="text-[10px] text-stone-400">⌄</span>
                                        </summary>
                                        <form class="space-y-3 px-3 pb-4 pt-1" method="POST" action="{{ route('buku.ulasan.store', $book->BukuID) }}">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->BukuID }}">
                                            <div class="flex items-center gap-1 text-amber-500">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <label for="rating-{{ $book->BukuID }}-{{ $i }}" class="cursor-pointer text-lg {{ $selectedRating >= $i ? 'text-amber-500' : 'text-stone-200' }}">
                                                        <input id="rating-{{ $book->BukuID }}-{{ $i }}" type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ $i === 1 ? 'required' : '' }}>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                                                            <path d="M12 17.75l-6.18 3.25 1.18-6.88-5-4.87 6.9-1 3.1-6.28 3.1 6.28 6.9 1-5 4.87 1.18 6.88z" />
                                                        </svg>
                                                    </label>
                                                @endfor
                                            </div>
                                            @error('rating')
                                                <p class="text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                            <textarea
                                                name="ulasan"
                                                rows="3"
                                                required
                                                class="w-full rounded-2xl border border-stone-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                            >{{ $reviewText }}</textarea>
                                            @error('ulasan')
                                                <p class="text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                            <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                                Kirim ulasan
                                            </button>
                                        </form>
                                    </details>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-stone-200 bg-white/60 p-5 text-sm text-stone-500">
                            Tidak ada buku dalam daftar.
                        </p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-stone-500">ulasan</p>
                    <h2 class="text-xl font-semibold text-stone-900">Suara peminjam</h2>
                </div>
                <span class="text-xs text-stone-500">{{ $recentReviews->count() }} catatan terkini</span>
            </div>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                @forelse ($recentReviews as $review)
                    <article class="rounded-2xl border border-stone-100 bg-stone-50/70 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-stone-900">{{ $review->buku?->Judul ?? 'Buku tidak ditemukan' }}</p>
                                <p class="text-xs text-stone-500">{{ $review->user?->namaLengkap ?? 'Anonim' }}</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-600">
                                Rating {{ $review->Rating }}/5
                            </span>
                        </div>
                        <p class="mt-3 text-sm text-stone-700">{{ \Illuminate\Support\Str::limit($review->Ulasan, 140, '...') }}</p>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-stone-200 bg-stone-50/60 p-4 text-sm text-stone-500">
                        Belum ada ulasan terbaru.
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    @if ($canManage)
        <div
            id="tambahModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
            aria-hidden="true"
        >
            <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-stone-900">Tambah buku baru</h2>
                    </div>
                    <button type="button" data-modal-close class="rounded-full border border-stone-200 px-3 py-1 text-xs font-semibold text-stone-500 hover:border-stone-400">
                        Tutup
                    </button>
                </div>

                <form method="POST" action="{{ route('daftar-buku') }}" enctype="multipart/form-data" class="mt-6 grid gap-4 md:grid-cols-2">
                    @csrf
                    <div class="space-y-2">
                        <label for="judul" class="text-xs font-semibold text-stone-500">Judul</label>
                        <input
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            required
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        @error('judul')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="penulis" class="text-xs font-semibold text-stone-500">Penulis</label>
                        <input
                            id="penulis"
                            name="penulis"
                            value="{{ old('penulis') }}"
                            required
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        @error('penulis')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="penerbit" class="text-xs font-semibold text-stone-500">Penerbit</label>
                        <input
                            id="penerbit"
                            name="penerbit"
                            value="{{ old('penerbit') }}"
                            required
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        @error('penerbit')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="tahun_terbit" class="text-xs font-semibold text-stone-500">Tahun terbit</label>
                        <input
                            id="tahun_terbit"
                            name="tahun_terbit"
                            type="number"
                            value="{{ old('tahun_terbit') }}"
                            required
                            min="1900"
                            max="{{ now()->year + 1 }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        @error('tahun_terbit')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="cover" class="text-xs font-semibold text-stone-500">Cover</label>
                        <input
                            id="cover"
                            name="cover"
                            type="file"
                            accept="image/*"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        @error('cover')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            Simpan koleksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('tambahModal');
                const triggers = document.querySelectorAll('[data-modal-trigger]');
                const closers = document.querySelectorAll('[data-modal-close]');

                triggers.forEach((button) => {
                    button.addEventListener('click', () => {
                        modal.classList.remove('hidden');
                    });
                });

                closers.forEach((button) => {
                    button.addEventListener('click', () => {
                        modal.classList.add('hidden');
                    });
                });

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        </script>
    @endif
@endsection
