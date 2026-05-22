<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RewardRule;

class RewardRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RewardRule::updateOrCreate(
            ['name' => 'ฉลองเปิดร้านใหม่ ส่วนลด 10%'],
            [
                'type' => 'coupon',
                'benefit_type' => 'percent_discount',
                'benefit_value' => 10,
                'status' => true,
                'min_purchase' => 300,
                'is_stackable' => false,
                'priority' => 10
            ]
        );

        RewardRule::updateOrCreate(
            ['name' => 'สมาชิกใช้ 100 แต้ม ลด 50 บาท'],
            [
                'type' => 'redeem_points',
                'benefit_type' => 'fixed_discount',
                'benefit_value' => 50,
                'status' => true,
                'required_points' => 100,
                'min_purchase' => 0,
                'is_stackable' => true,
                'priority' => 5
            ]
        );

        RewardRule::updateOrCreate(
            ['name' => 'โปรโมชั่นวันเกิด รับคะแนนโบนัส 50 แต้ม'],
            [
                'type' => 'birthday',
                'benefit_type' => 'bonus_points',
                'benefit_value' => 50,
                'status' => true,
                'min_purchase' => 0,
                'is_stackable' => true,
                'priority' => 1
            ]
        );
    }
}
