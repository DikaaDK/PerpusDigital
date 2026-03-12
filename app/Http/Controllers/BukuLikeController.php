<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BukuLikeController extends Controller
{
    public function store(Buku $buku): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $user->likedBooks()->syncWithoutDetaching($buku->BukuID);

        return back()->with('success', 'Buku ditandai sebagai disukai.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $user->likedBooks()->detach($buku->BukuID);

        return back()->with('success', 'Buku dihapus dari daftar disukai.');
    }
}
