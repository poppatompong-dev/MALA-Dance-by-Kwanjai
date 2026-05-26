<?php

namespace Database\Seeders;

use App\Models\SalesChannel;
use Illuminate\Database\Seeder;

class SalesChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            [
                'name' => 'หน้าร้าน',
                'slug' => 'walk_in',
                'icon' => 'fas fa-store',
                'color' => '#28a745',
                'commission_percent' => 0,
                'sort_order' => 1,
                'description' => 'ขายหน้าร้าน / ไม่ผ่านแพลตฟอร์ม',
            ],
            [
                'name' => 'Grab Food',
                'slug' => 'grab',
                'icon' => 'fas fa-motorcycle',
                'color' => '#00b14f',
                'commission_percent' => 32,
                'sort_order' => 2,
                'description' => 'ออเดอร์ผ่านแอป Grab Food',
            ],
            [
                'name' => 'LINE MAN',
                'slug' => 'line_man',
                'icon' => 'fab fa-line',
                'color' => '#06c755',
                'commission_percent' => 30,
                'sort_order' => 3,
                'description' => 'ออเดอร์ผ่านแอป LINE MAN',
            ],
            [
                'name' => 'Shopee Food',
                'slug' => 'shopee_food',
                'icon' => 'fas fa-shopping-bag',
                'color' => '#ee4d2d',
                'commission_percent' => 30,
                'sort_order' => 4,
                'description' => 'ออเดอร์ผ่านแอป Shopee Food',
            ],
        ];

        foreach ($channels as $channel) {
            SalesChannel::updateOrCreate(
                ['slug' => $channel['slug']],
                $channel
            );
        }
    }
}
