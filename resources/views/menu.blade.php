<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu - Rumah Makan Cabe Bawang</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="brand">
                🌶️ Cabe Bawang
                <small>Rumah Makan</small>
            </div>
            <nav>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('menu') }}">Daftar Menu</a>
                <a href="{{ route('cart') }}">Keranjang</a>
            </nav>
        </div>
    </header>

    <main class="container content">
        <h1>Daftar Menu</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($items->isEmpty())
            <div class="alert alert-warning">Belum ada menu yang tersedia.</div>
        @endif

        <div class="menu-grid">
            @foreach ($items as $item)
                <article class="menu-card @unless($item->is_available) menu-card-unavailable @endunless">
                    @if(isset($cart[(string) $item->id]))
                        <span class="in-cart-badge">✓ {{ $cart[(string) $item->id]['quantity'] }} di Keranjang</span>
                    @endif
                    @if($item->image_path)
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 10px; margin-bottom: 10px;">
                    @else
                        <div style="width: 100%; height: 160px; border-radius: 10px; margin-bottom: 10px; background: var(--krem-tua); display:flex; align-items:center; justify-content:center; font-size: 2.5rem;">🌶️</div>
                    @endif
                    <h2>{{ $item->name }}</h2>
                    <p>{{ $item->description ?: 'Tidak ada deskripsi.' }}</p>
                    <p class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                    @if($item->is_available)
                        <form action="{{ route('cart.items.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $item->id }}">

                            <label for="quantity-{{ $item->id }}">Jumlah</label>
                            <input id="quantity-{{ $item->id }}" type="number" name="quantity" value="1" min="1" max="100">

                            <button type="submit">🛒 Tambah ke Keranjang</button>
                        </form>
                    @else
                        <button type="button" class="unavailable-badge" disabled>Menu Tidak Tersedia</button>
                    @endif
                </article>
            @endforeach
        </div>
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} Rumah Makan Cabe Bawang &mdash; Pedasnya Bikin Nagih 🌶️
    </footer>
</body>
</html>