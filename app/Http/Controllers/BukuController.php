<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\UlasanBuku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        /** @var User|null $user */
        $user = Auth::user();

        $books = Buku::withCount('likedBy')
            ->withAvg('reviews', 'Rating')
            ->withCount('reviews')
            ->orderBy('Judul')
            ->get();
        $canManage = $user && in_array($user->role, ['admin', 'petugas'], true);
        $likedBookIds = $user?->likedBooks()->pluck('BukuID')->toArray() ?? [];
        $recentReviews = UlasanBuku::with(['user', 'buku'])
            ->orderByDesc('UlasanID')
            ->take(6)
            ->get();

        return view('pages.daftarBuku', compact('books', 'canManage', 'likedBookIds', 'recentReviews'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'cover' => ['nullable', 'image', 'max:2048'],
        ], [
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun ini.',
        ]);
        if (! in_array($request->user()?->role, ['admin', 'petugas'], true)) {
            abort(403, 'Hanya petugas atau administrator yang bisa menambah koleksi.');
        }

        $coverUrl = null;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $coverUrl = Storage::url($path);
        }

        Buku::create([
            'Judul' => $data['judul'],
            'Penulis' => $data['penulis'],
            'Penerbit' => $data['penerbit'],
            'TahunTerbit' => $data['tahun_terbit'],
            'Cover' => $coverUrl,
        ]);

        return redirect()->back()->with('success', 'Koleksi baru berhasil ditambahkan.');
    }

    public function liked(): \Illuminate\View\View
    {
        /** @var User|null $user */
        $user = Auth::user();

        $likedBooks = $user
            ? $user->likedBooks()->withCount('likedBy')->orderBy('Judul')->get()
            : collect();

        return view('pages.bukuDisukai', compact('likedBooks'));
    }

    public function storeReview(Request $request, Buku $buku): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_unless($user && $user->role === 'peminjam', 403, 'Hanya peminjam yang bisa mengulas.');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'ulasan' => ['required', 'string', 'max:500'],
        ]);

        UlasanBuku::create([
            'UserID' => $user->id,
            'BukuID' => $buku->BukuID,
            'Ulasan' => $validated['ulasan'],
            'Rating' => $validated['rating'],
        ]);

        return redirect()
            ->route('daftar-buku')
            ->with('success', 'Terima kasih, ulasan Anda tersimpan.');
    }

    public function destroy(Buku $buku)
    {
        $user = Auth::user();

        if (! in_array($user?->role, ['admin', 'petugas'], true)) {
            abort(403);
        }

        if ($buku->Cover) {
            $relativePath = str_replace('/storage/', '', $buku->Cover);
            Storage::disk('public')->delete($relativePath);
        }

        $buku->delete();

        return redirect()
            ->route('daftar-buku')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
