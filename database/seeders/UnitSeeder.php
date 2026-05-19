<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'title' => 'ไม้',
                'short_name' => 'ไม้',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'แก้ว',
                'short_name' => 'แก้ว',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'ขวด',
                'short_name' => 'ขวด',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'ชุด',
                'short_name' => 'ชุด',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'ถุง',
                'short_name' => 'ถุง',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'กรัม',
                'short_name' => 'กรัม',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Unit::insert($units);
    }
}
