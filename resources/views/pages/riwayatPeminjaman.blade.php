@extends('layouts.dashboard')

@section('title', 'Riwayat Peminjaman')

@section('content')
    @php
        use Illuminate\Support\Carbon;
    @endphp

    <div class="flex flex-col gap-6">
        <header class="flex flex-col gap-1">
            <h1 class="text-3xl font-semibold text-stone-900">Riwayat Peminjaman</h1>
            <p class="text-sm text-stone-500">Lihat kembali koleksi yang sudah dikembalikan.</p>
        </header>

        @if ($history->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-200 bg-white p-6 text-sm text-stone-500">
                Belum ada riwayat peminjaman selesai untuk saat ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-xs text-stone-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Judul Buku</th>
                            <th class="px-4 py-3 text-left">Peminjam</th>
                            <th class="px-4 py-3 text-left">Tanggal Pinjam</th>
                            <th class="px-4 py-3 text-left">Tanggal Kembali</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($history as $loan)
                            <tr>
                                <td class="px-4 py-4 font-semibold text-stone-900">{{ $loan->buku->Judul }}</td>
                                <td class="px-4 py-4 text-stone-600">{{ $loan->user->namaLengkap ?? $loan->user->username }}</td>
                                <td class="px-4 py-4 text-stone-600">{{ Carbon::parse($loan->TanggalPeminjaman)->isoFormat('D MMMM YYYY') }}</td>
                                <td class="px-4 py-4 text-stone-600">
                                    {{ $loan->TanggalPengembalian ? Carbon::parse($loan->TanggalPengembalian)->isoFormat('D MMMM YYYY') : '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $loan->StatusPeminjaman }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
