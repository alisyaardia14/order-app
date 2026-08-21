<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Rumah Makan Cabe Bawang</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="navbar admin-navbar">
        <div class="container">
            <div class="brand">
                🌶️ Cabe Bawang
                <small>Admin Panel</small>
            </div>
        </div>
    </header>

    <main class="container content">
        <div class="login-wrap">
            <section class="login-card">
                <span class="eyebrow">Admin</span>
                <h1>Masuk ke Panel Admin</h1>
                <p class="login-sub">Kelola menu dan pesanan Rumah Makan Cabe Bawang.</p>

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

                <form method="POST" action="{{ route('admin.login.submit') }}" class="form-grid">
                    @csrf

                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" autofocus required>

                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>

                    <button type="submit">🔐 Masuk</button>
                </form>
            </section>
        </div>
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} Rumah Makan Cabe Bawang &mdash; Pedasnya Bikin Nagih 🌶️
    </footer>
</body>
</html>
