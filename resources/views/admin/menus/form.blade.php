<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($item) ? 'Edit Menu' : 'Tambah Menu' }} - Admin Cabe Bawang</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('admin.partials.navbar')

    <main class="container content">
        <h1>{{ isset($item) ? 'Edit Menu' : 'Tambah Menu' }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ isset($item) ? route('admin.menus.update', $item) : route('admin.menus.store') }}" class="form-grid admin-form" enctype="multipart/form-data">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required>

            <label for="price">Harga</label>
            <input id="price" type="number" name="price" min="0" value="{{ old('price', $item->price ?? '') }}" required>

            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $item->description ?? '') }}</textarea>

            <label for="image">Gambar Menu</label>
            @if(isset($item) && $item->image_path)
                <div class="current-image">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="max-width: 160px; display: block; margin-bottom: 8px;">
                    <label class="checkbox-row">
                        <input type="checkbox" name="remove_image" value="1">
                        Hapus gambar saat ini
                    </label>
                </div>
            @endif
            <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp">
            <small style="color: var(--teks-muda);">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>

            <label class="checkbox-row">
                <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $item->is_available ?? true))>
                Menu tersedia
            </label>

            <div>
                <button type="submit">Simpan</button>
                <a href="{{ route('admin.menus.index') }}" class="button button-secondary">Kembali</a>
            </div>
        </form>
    </main>
</body>
</html>
