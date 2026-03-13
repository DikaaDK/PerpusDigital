@php
    use Illuminate\Support\Carbon;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #1f2933;
            margin: 0;
            padding: 24px;
            background: #f8fafc;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        h1 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
        }

        .meta {
            font-size: 12px;
            color: #475569;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }

        th {
            text-align: left;
            font-weight: 600;
            color: #475569;
            background: #f1f5f9;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            background: #ecfccb;
            color: #15803d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>
    <div class="card">
        <header>
            <h1>Riwayat Peminjaman</h1>
            <div class="meta">
                Total entri: {{ $history->count() }} (Diproses pada {{ Carbon::now()->isoFormat('D MMMM YYYY HH:mm') }})
            </div>
        </header>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Buku</th>
                    <th>Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $loan)
                    <tr>
                        <td>{{ $loan->PeminjamanID }}</td>
                        <td>{{ $loan->buku->Judul }}</td>
                        <td>{{ $loan->user->namaLengkap ?? $loan->user->username }}</td>
                        <td>{{ Carbon::parse($loan->TanggalPeminjaman)->isoFormat('D MMMM YYYY') }}</td>
                        <td>{{ $loan->TanggalPengembalian ? Carbon::parse($loan->TanggalPengembalian)->isoFormat('D MMMM YYYY') : '-' }}</td>
                        <td>
                            <span class="status">{{ $loan->StatusPeminjaman }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
