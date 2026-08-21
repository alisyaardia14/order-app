<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menus')->insert([
            [
                'name' => 'Nasi Goreng',
                'price' => 20000,
                'description' => 'Nasi goreng spesial dengan telur dan ayam',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie Ayam',
                'price' => 18000,
                'description' => 'Mie ayam dengan suwiran ayam dan pangsit',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ayam Geprek',
                'price' => 22000,
                'description' => 'Ayam geprek pedas disajikan dengan nasi putih',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('menus')
            ->whereIn('name', ['Nasi Goreng', 'Mie Ayam', 'Ayam Geprek'])
            ->delete();
    }
};
