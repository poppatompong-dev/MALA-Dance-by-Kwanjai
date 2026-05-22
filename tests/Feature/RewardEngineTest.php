<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\RewardRule;
use App\Services\RewardService;

class RewardEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_available_rewards()
    {
        $customer = Customer::create([
            'name' => 'Test',
            'phone' => '0800000000',
            'points' => 150
        ]);

        $rule1 = RewardRule::create([
            'name' => '10% Discount min 300',
            'type' => 'coupon',
            'benefit_type' => 'percent_discount',
            'benefit_value' => 10,
            'min_purchase' => 300,
            'status' => true
        ]);

        $rule2 = RewardRule::create([
            'name' => 'Use 100 points',
            'type' => 'redeem_points',
            'benefit_type' => 'fixed_discount',
            'benefit_value' => 50,
            'required_points' => 100,
            'min_purchase' => 0,
            'status' => true
        ]);

        $rule3 = RewardRule::create([
            'name' => 'Use 200 points',
            'type' => 'redeem_points',
            'benefit_type' => 'fixed_discount',
            'benefit_value' => 100,
            'required_points' => 200,
            'min_purchase' => 0,
            'status' => true
        ]);

        $service = new RewardService();
        $rewards = $service->getAvailableRewards($customer, 350);

        // Should return rule1 and rule2, but not rule3 (not enough points)
        $this->assertCount(2, $rewards);
        $this->assertTrue($rewards->contains('id', $rule1->id));
        $this->assertTrue($rewards->contains('id', $rule2->id));
        $this->assertFalse($rewards->contains('id', $rule3->id));
    }

    public function test_calculate_reward()
    {
        $customer = Customer::create([
            'name' => 'Test',
            'phone' => '0800000001',
            'points' => 100
        ]);

        $rule = RewardRule::create([
            'name' => '10% Discount min 300',
            'type' => 'coupon',
            'benefit_type' => 'percent_discount',
            'benefit_value' => 10,
            'min_purchase' => 300,
            'status' => true
        ]);

        $service = new RewardService();
        $result = $service->calculateReward($rule, $customer, 400);

        $this->assertEquals(40.0, $result['discount']); // 10% of 400
        $this->assertEquals(0, $result['points_change']);
    }
}
