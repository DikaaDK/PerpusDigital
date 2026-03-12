<aside class="w-full md:w-72 md:min-h-screen md:border-r border-stone-200 bg-white md:flex md:flex-col">
    <div class="px-5 py-5 flex items-center justify-between border-b border-stone-200">
        <img width="20" height="20" src="https://img.icons8.com/metro/26/books.png" alt="books" class="ml-4"/>
        <h1 class="mt-1 text-xl font-bold text-stone-900">Perpus Digital</h1>
        <p></p>
    </div>

    @php
        $navItems = [
            ['route' => 'users', 'label' => 'Manajemen Pengguna', 'role' => 'admin'],
            ['route' => 'daftar-buku', 'label' => 'Daftar Buku'],
            ['route' => 'buku-disukai', 'label' => 'Buku Disukai'],
            ['route' => 'peminjaman', 'label' => 'Peminjaman'],
            ['route' => 'riwayat-peminjaman', 'label' => 'Riwayat Peminjaman'],
        ];
    @endphp

    <nav class="p-4 space-y-1">
        @foreach ($navItems as $item)
            @if (isset($item['role']) && auth()->user()->role !== $item['role'])
                @continue
            @endif
            @php
                $isActive = request()->routeIs($item['route']);
                $linkClasses = 'flex items-center rounded-xl px-3 py-2.5 text-sm transition';
                if ($isActive) {
                    $linkClasses .= ' bg-blue-50 text-blue-600 shadow-inner';
                } else {
                    $linkClasses .= ' text-stone-600 hover:bg-stone-100';
                }
            @endphp
            <a href="{{ route($item['route']) }}" class="{{ $linkClasses }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="mx-4 mt-6 rounded-lg p-4 shadow-sm md:mt-auto md:mb-4">
        <p class="text-[11px] uppercase tracking-[0.16em] text-stone-500">Account</p>

        <div class="mt-3 flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-stone-100">
                <img width="48" height="48" src="https://img.icons8.com/fluency-systems-regular/48/user--v1.png" alt="user--v1" class="h-7 w-7">
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-stone-900">{{ auth()->user()->username }}</p>
                <p class="truncate text-xs text-stone-500">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="mt-3 ml-18 inline-flex rounded-full border border-stone-300 bg-gray-300 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-gray-900">
            {{ auth()->user()->role }}
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button
                type="submit"
                class="w-full rounded-xl bg-red-500 px-3 py-2 text-sm font-medium text-white hover:bg-red-600 transition"
            >
                Logout
            </button>
        </form>
    </div>
</aside>
