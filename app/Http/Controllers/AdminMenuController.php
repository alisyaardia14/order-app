<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminMenuController extends Controller
{
    public function index(): View
    {
        $items = Menu::query()->latest()->get();

        return view('admin.menus.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.menus.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:menus,name'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'Nama menu ":input" sudah digunakan. Gunakan nama lain, atau edit menu yang sudah ada.',
        ]);

        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('menus', 'public');
        }

        unset($data['image']);

        Menu::create($data);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.form', ['item' => $menu]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:menus,name,'.$menu->id],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'Nama menu ":input" sudah digunakan oleh menu lain. Gunakan nama lain.',
        ]);

        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $data['image_path'] = $request->file('image')->store('menus', 'public');
        } elseif ($request->boolean('remove_image') && $menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
            $data['image_path'] = null;
        }

        unset($data['image'], $data['remove_image']);

        $menu->update($data);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
