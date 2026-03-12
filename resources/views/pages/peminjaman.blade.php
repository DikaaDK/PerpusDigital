@extends('layouts.dashboard')

@section('title', 'Peminjaman')

@section('content')
    @php
        use Illuminate\Support\Carbon;

        $currentRole = auth()->user()->role ?? 'guest';
    @endphp

    <div class="flex flex-col gap-6">
        <header class="flex flex-col gap-1">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold text-stone-900">Peminjaman Aktif</h1>
                </div>
                <span class="rounded-full border border-stone-200 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-stone-500">
                    {{ $currentRole }}
                </span>
            </div>
        </header>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @elseif (session('warning'))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                {{ session('warning') }}
            </div>
        @elseif (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="space-y-4">
                <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-stone-500">Peminjaman yang sedang berjalan</p>
                            <h2 class="text-lg font-semibold text-stone-900">{{ $activeLoans->count() }} koleksi aktif</h2>
                        </div>
                        <span class="rounded-full border border-stone-200 px-3 py-1 text-xs font-semibold text-stone-500">
                            {{ $currentRole === 'peminjam' ? 'Hanya data pribadi' : 'Semua peminjaman' }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($activeLoans as $loan)
                            <article class="flex flex-col gap-3 rounded-2xl border border-stone-100 bg-stone-50/60 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-base font-semibold text-stone-900">{{ $loan->buku->Judul }}</p>
                                        <p class="text-xs text-stone-500">
                                            {{ $loan->user->namaLengkap ?? $loan->user->username }} · Dipinjam sejak {{ Carbon::parse($loan->TanggalPeminjaman)->isoFormat('D MMMM YYYY') }}
                                        </p>
                                    </div>
                                    @php
                                        $loanStatus = $loan->StatusPeminjaman;
                                        $statusColors = $loanStatus === 'Selesai'
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : 'bg-amber-50 text-amber-700';
                                    @endphp
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors }}">
                                        {{ $loanStatus }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-stone-500">
                                    <span>ID {{ $loan->PeminjamanID }} · Buku {{ $loan->BukuID }}</span>
                                    @if ($canManageLoans)
                                        <form method="POST" action="{{ route('peminjaman.complete', $loan) }}" class="">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="rounded-full bg-emerald-600 px-3 py-1 text-[10px] font-semibold text-white transition hover:bg-emerald-700"
                                            >
                                                Tandai selesai
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <p class="rounded-2xl border border-dashed border-stone-200 bg-white/60 p-5 text-sm text-stone-500">
                                Tidak ada peminjaman aktif saat ini.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-dashed border-stone-200 bg-white/80 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-stone-500">Formulir</p>
                            <h2 class="text-xl font-semibold text-stone-900">Ajukan peminjaman</h2>
                        </div>
                        <span class="rounded-full border border-stone-200 px-3 py-1 text-xs font-semibold text-stone-500">
                            Hak {{ $currentRole }}
                        </span>
                    </div>

                    @if ($canRequestLoan)
                        <form method="POST" action="{{ route('peminjaman.store') }}" class="mt-4 space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-stone-500" for="book_id">Pilih buku</label>
                                <select
                                    id="book_id"
                                    name="book_id"
                                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                >
                                    <option value="">Pilih koleksi</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->BukuID }}" @selected(old('book_id') == $book->BukuID)>
                                            {{ $book->Judul }} · {{ $book->Penulis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('book_id')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-stone-300"
                                {{ $books->isEmpty() ? 'disabled' : '' }}
                            >
                                Ajukan peminjaman
                            </button>
                        </form>
                    @else
                        <p class="mt-4 text-sm text-stone-600">
                            {{ $currentRole === 'peminjam' ? 'Lengkapi data akun Anda terlebih dahulu.' : 'Hanya peminjam yang dapat mengajukan peminjaman dari koleksi.' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
