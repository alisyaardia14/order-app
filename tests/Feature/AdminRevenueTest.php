<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRevenueTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_revenue_report_by_day_and_month(): void
    {
        $admin = new Admin([
            'id' => 1,
            'username' => 'admin',
            'password' => 'secret',
        ]);
        $this->actingAs($admin, 'admin');

        Order::create([
            'order_code' => 'ORD-001',
            'customer_name' => 'Budi',
            'customer_phone' => '0811111111',
            'notes' => null,
            'total_amount' => 15000,
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Order::create([
            'order_code' => 'ORD-002',
            'customer_name' => 'Sari',
            'customer_phone' => '0822222222',
            'notes' => null,
            'total_amount' => 25000,
            'status' => 'completed',
            'created_at' => now()->subMonth(),
            'updated_at' => now()->subMonth(),
        ]);

        $this->get(route('admin.revenue.index', ['period' => 'day']))
            ->assertOk()
            ->assertSee('Laporan Pendapatan')
            ->assertSee('Per Hari');

        $this->get(route('admin.revenue.index', ['period' => 'month']))
            ->assertOk()
            ->assertSee('Laporan Pendapatan')
            ->assertSee('Per Bulan');
    }

    public function test_admin_can_download_revenue_report_as_pdf(): void
    {
        $admin = new Admin([
            'id' => 2,
            'username' => 'admin2',
            'password' => 'secret',
        ]);
        $this->actingAs($admin, 'admin');

        Order::create([
            'order_code' => 'ORD-003',
            'customer_name' => 'Dina',
            'customer_phone' => '0833333333',
            'notes' => null,
            'total_amount' => 32000,
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('admin.revenue.pdf', ['period' => 'day']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_orders_store_table_number_for_customer_lookup(): void
    {
        Order::create([
            'order_code' => 'ORD-004',
            'table_number' => '05',
            'customer_name' => 'Edi',
            'customer_phone' => '0844444444',
            'notes' => 'Tanpa pedas',
            'total_amount' => 20000,
            'status' => 'ready',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('orders', [
            'table_number' => '05',
            'customer_name' => 'Edi',
        ]);
    }
}
