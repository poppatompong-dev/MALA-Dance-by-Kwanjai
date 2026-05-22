<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\PosCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolesAndPermissionsSeeder;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_processes_transaction_and_deducts_stock()
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create([
            'username' => 'testadmin',
        ]);
        $user->assignRole('Owner');

        $product = Product::create([
            'name' => 'Test Skewer',
            'sku' => 'TEST-001',
            'quantity' => 10,
            'price' => 100,
            'discount' => 0,
            'discount_type' => 'fixed',
            'purchase_price' => 50,
            'is_stock_tracked' => 1,
            'status' => 1,
        ]);

        PosCart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $customer = \App\Models\Customer::create([
            'name' => 'Walk-in',
            'phone' => '0000000000',
            'email' => 'walkin@example.com',
            'address' => 'N/A',
        ]);

        $response = $this->actingAs($user)->put('/admin/order/create', [
            'total' => 200,
            'paid' => 200,
            'customer_id' => $customer->id,
            'order_discount' => 0,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'บันทึกการขายเรียบร้อยแล้ว']);

        // Verify stock deducted
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 8,
        ]);

        // Verify ledger entry
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity_change' => -2,
            'type' => 'sale',
        ]);

        // Verify cart is empty
        $this->assertDatabaseMissing('pos_carts', [
            'user_id' => $user->id,
        ]);
    }
}
