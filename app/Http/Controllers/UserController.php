<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UlasanBuku;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function authorizeAdmin(): void
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin', 403);
    }

    public function index(): View
    {
        $this->authorizeAdmin();

        $users = User::orderByDesc('created_at')->get();
        $reviews = UlasanBuku::with(['user', 'buku'])
            ->orderByDesc('UlasanID')
            ->take(8)
            ->get();

        return view('pages.users', compact('users', 'reviews'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'namaLengkap' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'username' => $data['username'],
            'namaLengkap' => $data['namaLengkap'],
            'alamat' => $data['alamat'],
            'email' => $data['email'],
            'role' => 'petugas',
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users')->with('success', 'Petugas baru berhasil ditambahkan.');
    }
}
