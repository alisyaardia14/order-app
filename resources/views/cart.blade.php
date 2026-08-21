<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - Rumah Makan Cabe Bawang</title>
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
        <h1>Keranjang Pesanan</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if ($cart->isNotEmpty())
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.items.destroy', $item['menu_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button button-danger button-small" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="3">Total</th>
                        <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>

            <section class="checkout-card">
                <h2>🍽️ Data Pemesanan</h2>
                <form action="{{ route('checkout') }}" method="POST" class="form-grid">
                    @csrf

                    <label for="table_number">Nomor Meja</label>
                    <input id="table_number" type="text" name="table_number" value="{{ old('table_number') }}" placeholder="Contoh: 05" required>

                    <label for="customer_name">Nama</label>
                    <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}" required>

                    <label for="customer_phone">Nomor Telepon</label>
                    <input id="customer_phone" type="text" name="customer_phone" value="{{ old('customer_phone') }}" required>

                    <label for="notes">Catatan (opsional)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Contoh: pedas level 2, tanpa bawang, dll">{{ old('notes') }}</textarea>

                    <button type="submit">Buat Pesanan</button>
                </form>
            </section>
        @else
            <div class="empty-state">
                <p>Keranjang masih kosong.</p>
                <a class="button" href="{{ route('menu') }}">Pilih Menu</a>
            </div>
        @endif
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} Rumah Makan Cabe Bawang &mdash; Pedasnya Bikin Nagih 🌶️
    </footer>
</body>
</html>
