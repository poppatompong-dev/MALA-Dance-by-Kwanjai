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
            ['title' => 'ไม้', 'short_name' => 'ไม้'],
            ['title' => 'แก้ว', 'short_name' => 'แก้ว'],
            ['title' => 'ขวด', 'short_name' => 'ขวด'],
            ['title' => 'ชุด', 'short_name' => 'ชุด'],
            ['title' => 'ถุง', 'short_name' => 'ถุง'],
            ['title' => 'กรัม', 'short_name' => 'กรัม'],
            ['title' => 'จาน', 'short_name' => 'จาน'],
            ['title' => 'ชาม', 'short_name' => 'ชาม'],
            ['title' => 'ถ้วย', 'short_name' => 'ถ้วย'],
            ['title' => 'กล่อง', 'short_name' => 'กล่อง'],
            ['title' => 'เสิร์ฟ', 'short_name' => 'เสิร์ฟ'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['title' => $unit['title']], $unit);
        }
    }
}
