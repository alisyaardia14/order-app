<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Rumah Makan Cabe Bawang</title>
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
        <section class="hero">
            <span class="eyebrow">Makan di Tempat</span>
            <h1>Selamat Datang di Rumah Makan Cabe Bawang</h1>
            <p>Nikmati masakan rumahan pedas yang menggugah selera. Pesan langsung dari meja Anda — pilih menu, masukkan nomor meja, lalu pesanan kami antar segar dan hangat ke tempat Anda duduk.</p>
            <a class="button" href="{{ route('menu') }}">Lihat Menu &rarr;</a>
        </section>

        <section class="info-grid">
            <div class="card">
                <h2>🍽️ Pilih Menu</h2>
                <p>Menu dan harga terbaru, langsung dari dapur kami.</p>
            </div>
            <div class="card">
                <h2>🧾 Pesan dari Meja</h2>
                <p>Cukup masukkan nomor meja, tanpa perlu antre ke kasir.</p>
            </div>
            <div class="card">
                <h2>🔥 Lacak Pesanan</h2>
                <p>Gunakan nomor meja untuk memantau progres masakan Anda.</p>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} Rumah Makan Cabe Bawang &mdash; Pedasnya Bikin Nagih 🌶️
    </footer>
</body>
</html>
