<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PeminjamanController extends Controller
{
    private const MANAGER_ROLES = ['admin', 'petugas'];
    private const STATUS_PENDING = 'Belum';
    private const STATUS_IN_PROGRESS = 'Sedang dipinjam';
    private const STATUS_COMPLETED = 'Selesai';
    private const ACTIVE_STATUSES = [self::STATUS_PENDING, self::STATUS_IN_PROGRESS];

    public function index(): View
    {
        /** @var User|null $user */
        $user = Auth::user();

        $activeLoans = Peminjaman::with(['buku', 'user'])
            ->whereIn('StatusPeminjaman', self::ACTIVE_STATUSES)
            ->when($user?->role === 'peminjam', fn ($query) => $query->where('UserID', $user->id))
            ->orderByDesc('PeminjamanID')
            ->get();

        $books = Buku::orderBy('Judul')->get();
        $users = in_array($user?->role, self::MANAGER_ROLES)
            ? User::orderBy('namaLengkap')->get()
            : collect();
        $canRequestLoan = $user?->role === 'peminjam';

        return view('pages.peminjaman', [
            'activeLoans' => $activeLoans,
            'books' => $books,
            'users' => $users,
            'canManageLoans' => $this->isManager($user),
            'canAssignToOthers' => in_array($user->role, self::MANAGER_ROLES),
            'canRequestLoan' => $canRequestLoan,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user?->role !== 'peminjam') {
            abort(403, 'Hanya peminjam yang dapat mengajukan peminjaman.');
        }

        $validated = $request->validate([
            'book_id' => ['required', 'exists:buku,BukuID'],
        ]);

        $targetUserId = $user->id;

        $alreadyLoaned = Peminjaman::where('BukuID', $validated['book_id'])
            ->where('StatusPeminjaman', 'Belum')
            ->exists();

        if ($alreadyLoaned) {
            return redirect()
                ->route('peminjaman')
                ->with('error', 'Buku masih dipinjam pengguna lain.');
        }

        Peminjaman::create([
            'UserID' => $targetUserId,
            'BukuID' => $validated['book_id'],
            'TanggalPeminjaman' => now()->toDateString(),
            'StatusPeminjaman' => self::STATUS_IN_PROGRESS,
        ]);

        return redirect()
            ->route('peminjaman')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function complete(Peminjaman $peminjaman): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $this->isManager($user)) {
            abort(403);
        }

        if ($peminjaman->StatusPeminjaman === 'Selesai') {
            return redirect()
                ->route('peminjaman')
                ->with('warning', 'Peminjaman sudah selesai.');
        }

        $peminjaman->update([
            'StatusPeminjaman' => 'Selesai',
            'TanggalPengembalian' => now()->toDateString(),
        ]);

        return redirect()
            ->route('peminjaman')
            ->with('success', 'Peminjaman ditandai selesai.');
    }

    public function history(): View
    {
        /** @var User|null $user */
        $user = Auth::user();

        $history = Peminjaman::with(['buku', 'user'])
            ->where('StatusPeminjaman', 'Selesai')
            ->when($user?->role === 'peminjam', fn ($query) => $query->where('UserID', $user->id))
            ->orderByDesc('TanggalPengembalian')
            ->get();

        return view('pages.riwayatPeminjaman', compact('history'));
    }

    public function exportHistory(): StreamedResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        $history = Peminjaman::with(['buku', 'user'])
            ->where('StatusPeminjaman', 'Selesai')
            ->when($user?->role === 'peminjam', fn ($query) => $query->where('UserID', $user->id))
            ->orderByDesc('TanggalPengembalian')
            ->get();

        $filename = 'riwayat-peminjaman-' . now()->format('YmdHis') . '.csv';

        $callback = function () use ($history) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID Peminjaman', 'Judul Buku', 'Peminjam', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status']);

            foreach ($history as $loan) {
                fputcsv($handle, [
                    $loan->PeminjamanID,
                    $loan->buku->Judul,
                    $loan->user->namaLengkap ?? $loan->user->username,
                    $loan->TanggalPeminjaman,
                    $loan->TanggalPengembalian,
                    $loan->StatusPeminjaman,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    private function isManager(User $user): bool
    {
        return in_array($user->role, self::MANAGER_ROLES, true);
    }
}
