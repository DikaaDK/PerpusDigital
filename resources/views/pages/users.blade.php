@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="flex flex-col gap-6">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-stone-900">Manajemen Pengguna</h1>
                <p class="text-sm text-stone-500">Kelola akses petugas sekaligus pantau aktivitas akun.</p>
            </div>
        </header>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
            <section class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-xs text-stone-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Peran</th>
                            <th class="px-4 py-3 text-left">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-4 font-semibold text-stone-900">{{ $user->namaLengkap }}</td>
                                <td class="px-4 py-4 text-stone-500">{{ $user->email }}</td>
                                <td class="px-4 py-4 text-stone-600 capitalize">{{ ucfirst($user->role) }}</td>
                                <td class="px-4 py-4 text-stone-500">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-stone-500">Belum ada pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            <section class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
                <div class="mb-4">
                    <p class="text-xs text-stone-500">tambah petugas</p>
                </div>

                <form class="space-y-4" method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-stone-500" for="username">Username</label>
                        <input
                            class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                        >
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-stone-500" for="namaLengkap">Nama lengkap</label>
                        <input
                            class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            type="text"
                            id="namaLengkap"
                            name="namaLengkap"
                            value="{{ old('namaLengkap') }}"
                            required
                        >
                        @error('namaLengkap')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-stone-500" for="alamat">Alamat</label>
                        <input
                            class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            type="text"
                            id="alamat"
                            name="alamat"
                            value="{{ old('alamat') }}"
                            required
                        >
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-stone-500" for="email">Email</label>
                        <input
                            class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-stone-500" for="password">Password</label>
                        <input
                            class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            type="password"
                            id="password"
                            name="password"
                            required
                        >
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-stone-500" for="password_confirmation">Konfirmasi password</label>
                        <input
                            class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                        >
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-500 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 transition">
                        Tambah Petugas
                    </button>
                </form>
            </section>
        </div>

        <section class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-stone-500">ulasan terbaru</p>
                    <h2 class="text-xl font-semibold text-stone-900">Masukan dari peminjam</h2>
                    <p class="text-sm text-stone-500">Menampilkan {{ $reviews->count() }} ulasan terakhir dari pengguna.</p>
                </div>
            </div>

            <div class="mt-6 space-y-5">
                @forelse ($reviews as $review)
                    <article class="rounded-2xl border border-stone-100 bg-stone-50/40 p-4 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-stone-900">{{ $review->buku?->Judul ?? 'Buku sudah dihapus' }}</p>
                                <p class="text-xs text-stone-500">oleh {{ $review->user?->namaLengkap ?? 'Pengguna tidak ditemukan' }}</p>
                            </div>
                            <div class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-600">
                                Rating {{ $review->Rating }}/5
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-stone-700">{{ \Illuminate\Support\Str::limit($review->Ulasan, 140, '...') }}</p>
                    </article>
                @empty
                    <div class="rounded-2xl border border-stone-100 bg-stone-50/40 p-4 text-sm text-stone-500">
                        Belum ada ulasan yang bisa ditampilkan.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
