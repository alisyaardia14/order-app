<header class="navbar admin-navbar">
    <div class="container">
        <div class="brand">
            🌶️ Cabe Bawang
            <small>Admin Panel</small>
        </div>
        <nav>
            <a href="{{ route('admin.menus.index') }}">Kelola Menu</a>
            <a href="{{ route('admin.payments.index') }}">Menyelesaikan Pembayaran</a>
            <a href="{{ route('admin.orders.index') }}">Kelola Pesanan</a>
            <a href="{{ route('admin.revenue.index', ['period' => 'day']) }}">Laporan Pendapatan</a>
            @auth('admin')
                <span class="admin-user">👤 {{ Auth::guard('admin')->user()->username }}</span>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline-form">
                    @csrf
                    <button type="submit" class="button-small button-danger">Logout</button>
                </form>
            @endauth
        </nav>
    </div>
</header>
