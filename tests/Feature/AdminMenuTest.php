<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    public function test_admin_can_create_update_and_delete_menu_items(): void
    {
        $path = storage_path('app/test-menus.json');
        @unlink($path);
        putenv('MENU_STORE_PATH=' . $path);

        $this->post(route('admin.menus.store'), [
            'name' => 'Test Food',
            'price' => '15000',
            'desc' => 'Test description',
        ])->assertRedirect(route('admin.menus.index'));

        $items = json_decode(file_get_contents($path), true);
        $this->assertCount(1, $items);
        $id = $items[0]['id'];

        $this->put(route('admin.menus.update', ['id' => $id]), [
            'name' => 'Updated Food',
            'price' => '18000',
            'desc' => 'Updated description',
        ])->assertRedirect(route('admin.menus.index'));

        $updatedItems = json_decode(file_get_contents($path), true);
        $this->assertSame('Updated Food', $updatedItems[0]['name']);
        $this->assertSame('18000', $updatedItems[0]['price']);

        $this->delete(route('admin.menus.destroy', ['id' => $id]))
            ->assertRedirect(route('admin.menus.index'));

        $this->assertEmpty(json_decode(file_get_contents($path), true));
    }

    public function test_admin_nav_has_payment_completion_link(): void
    {
        $admin = new Admin([
            'id' => 1,
            'username' => 'admin',
            'password' => 'secret',
        ]);
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.menus.index'))
            ->assertOk()
            ->assertSee('Menyelesaikan Pembayaran');
    }
}
