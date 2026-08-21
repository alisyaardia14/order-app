<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class OrderController
{
    public function home(): View
    {
        return view('home');
    }

    public function menu(Request $request): View
    {
        $items = Menu::query()
            ->orderByDesc('is_available')
            ->orderBy('name')
            ->get();

        $cart = $request->session()->get('cart', []);

        return view('menu', compact('items', 'cart'));
    }

    public function cart(Request $request): View
    {
        $cart = collect($request->session()->get('cart', []))->values();
        $total = $cart->sum(fn (array $item) => $item['price'] * $item['quantity']);

        return view('cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $menu = Menu::query()
            ->whereKey($data['menu_id'])
            ->where('is_available', true)
            ->firstOrFail();

        $cart = $request->session()->get('cart', []);
        $key = (string) $menu->id;
        $sudahAda = isset($cart[$key]);

        if ($sudahAda) {
            $cart[$key]['quantity'] += $data['quantity'];
        } else {
            $cart[$key] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $data['quantity'],
            ];
        }

        $request->session()->put('cart', $cart);

        $pesan = $sudahAda
            ? "\"{$menu->name}\" sudah ada di keranjang, jumlah berhasil ditambahkan."
            : "\"{$menu->name}\" berhasil ditambahkan ke keranjang.";

        return redirect()->back()->with('success', $pesan);
    }

    public function removeFromCart(Request $request, int $menuId): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[(string) $menuId]);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Menu dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'table_number' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $cart = collect($request->session()->get('cart', []))->values();

        if ($cart->isEmpty()) {
            return redirect()->route('cart')->withErrors([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        $menuIds = $cart->pluck('menu_id')->map(fn ($id) => (int) $id)->all();
        $menus = Menu::query()
            ->whereIn('id', $menuIds)
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        if ($menus->count() !== count($menuIds)) {
            return redirect()->route('cart')->withErrors([
                'cart' => 'Ada menu yang sudah tidak tersedia. Hapus menu tersebut lalu pesan kembali.',
            ]);
        }

        $order = DB::transaction(function () use ($data, $cart, $menus) {
            $total = $cart->sum(function (array $item) use ($menus) {
                $menu = $menus->get((int) $item['menu_id']);
                return $menu->price * $item['quantity'];
            });

            $order = Order::create([
                ...$data,
                'order_code' => $this->generateOrderCode(),
                'table_number' => $data['table_number'],
                'total_amount' => $total,
                'status' => 'ready',
            ]);

            foreach ($cart as $item) {
                $menu = $menus->get((int) $item['menu_id']);
                $subtotal = $menu->price * $item['quantity'];

                $order->items()->create([
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ]);
            }

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()->route('menu')
            ->with('success', 'Pesanan berhasil dibuat! Kode pesanan: ' . $order->order_code);
    }

    private function generateOrderCode(): string
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $code = 'ORD-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);

            if (! Order::where('order_code', $code)->exists()) {
                return $code;
            }
        }

        throw new RuntimeException('Gagal membuat kode pesanan unik.');
    }
}