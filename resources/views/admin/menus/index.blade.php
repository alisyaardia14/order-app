<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin Cabe Bawang</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('admin.partials.navbar')

    <main class="container content">
        <div class="page-header">
            <h1>Kelola Menu</h1>
            <a href="{{ route('admin.menus.create') }}" class="button">+ Tambah Menu</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Tersedia</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            @else
                                <span style="color:#999;">-</span>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->description ?: '-' }}</td>
                        <td>{{ $item->is_available ? 'Ya' : 'Tidak' }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.menus.edit', $item) }}" class="button button-small" style="background: var(--merah);">Edit</a>
                            <form action="{{ route('admin.menus.destroy', $item) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button-danger button-small" onclick="return confirm('Hapus menu ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada menu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
