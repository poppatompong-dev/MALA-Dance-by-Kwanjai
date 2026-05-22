<?php

namespace App\Services;

use App\Models\RewardRule;
use App\Models\RewardUsage;
use App\Models\Customer;
use Carbon\Carbon;
use Exception;

class RewardService
{
    /**
     * Get all currently active rewards a customer is eligible for.
     */
    public function getAvailableRewards(?Customer $customer, $cartTotal)
    {
        $now = Carbon::now();
        $query = RewardRule::where('status', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->where('min_purchase', '<=', $cartTotal)
            ->orderBy('priority', 'desc');

        $rules = $query->get();

        $eligibleRules = collect();

        foreach ($rules as $rule) {
            // Check global usage limit
            if ($rule->usage_limit !== null && $rule->usage_count >= $rule->usage_limit) {
                continue;
            }

            // Customer specific checks
            if ($customer) {
                if ($rule->required_points > 0 && $customer->points < $rule->required_points) {
                    continue;
                }
                if ($rule->customer_tier && $customer->tier !== $rule->customer_tier) {
                    continue;
                }
                if ($rule->per_customer_limit !== null) {
                    $usedCount = RewardUsage::where('reward_rule_id', $rule->id)
                        ->where('customer_id', $customer->id)
                        ->count();
                    if ($usedCount >= $rule->per_customer_limit) {
                        continue;
                    }
                }
                
                // Birthday check
                if ($rule->type === 'birthday') {
                    if (!$customer->birth_date || !Carbon::parse($customer->birth_date)->isBirthday()) {
                        continue;
                    }
                }
            } else {
                // If no customer, rules requiring points/tier/limits/birthday are invalid
                if ($rule->required_points > 0 || $rule->customer_tier || $rule->per_customer_limit || $rule->type === 'birthday' || $rule->type === 'redeem_points') {
                    continue;
                }
            }
            
            // Note: coupon codes might need manual input, so we can flag them or only return if matched.
            // For now, we return all eligible rules. The frontend can decide to hide coupon rules until typed.
            $eligibleRules->push($rule);
        }

        return $eligibleRules;
    }

    /**
     * Validate and calculate a specific reward against a cart.
     */
    public function calculateReward(RewardRule $rule, ?Customer $customer, $cartTotal)
    {
        // Re-validate limits
        if ($rule->min_purchase > $cartTotal) {
            throw new Exception("ยอดซื้อไม่ถึงขั้นต่ำสำหรับโปรโมชั่นนี้");
        }
        if ($rule->usage_limit !== null && $rule->usage_count >= $rule->usage_limit) {
            throw new Exception("โปรโมชั่นนี้ถูกใช้ครบจำนวนที่กำหนดแล้ว");
        }
        if ($customer) {
            if ($rule->required_points > 0 && $customer->points < $rule->required_points) {
                throw new Exception("แต้มสะสมไม่เพียงพอ");
            }
            if ($rule->per_customer_limit !== null) {
                $usedCount = RewardUsage::where('reward_rule_id', $rule->id)
                    ->where('customer_id', $customer->id)
                    ->count();
                if ($usedCount >= $rule->per_customer_limit) {
                    throw new Exception("คุณใช้สิทธิ์โปรโมชั่นนี้ครบแล้ว");
                }
            }
        }

        $discount = 0;
        $pointsChange = 0;

        if ($rule->type === 'redeem_points') {
            $pointsChange = -$rule->required_points;
        }

        if ($rule->benefit_type === 'fixed_discount') {
            $discount = min($rule->benefit_value, $cartTotal);
        } elseif ($rule->benefit_type === 'percent_discount') {
            $discount = round($cartTotal * ($rule->benefit_value / 100), 2);
        } elseif ($rule->benefit_type === 'bonus_points') {
            $pointsChange += $rule->benefit_value;
        }
        
        return [
            'discount' => $discount,
            'points_change' => $pointsChange,
        ];
    }
}
