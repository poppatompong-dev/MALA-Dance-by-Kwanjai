<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class InventoryService
{
    /**
     * Adjust stock for a product and log the movement.
     *
     * @param Product $product
     * @param int $quantityChange Positive for stock-in, negative for stock-out
     * @param string $type sale, purchase, adjustment, void, spoilage, refund
     * @param string|null $referenceType e.g., App\Models\Order
     * @param int|null $referenceId e.g., 1
     * @param string|null $notes
     * @param int|null $userId
     * @return Product
     * @throws Exception
     */
    public function adjustStock(
        Product $product,
        int $quantityChange,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
        ?int $userId = null
    ) {
        if ($quantityChange === 0) {
            return $product;
        }

        $userId = $userId ?? auth()->id();
        $previousQuantity = $product->quantity;
        $newQuantity = $previousQuantity + $quantityChange;

        // Strict Negative Stock Policy Check (Can be bypassed via config later)
        if ($newQuantity < 0 && $type === 'sale') {
            throw new Exception("สต็อกสินค้า {$product->name} ไม่เพียงพอ (จำนวนปัจจุบัน: {$previousQuantity})");
        }

        DB::transaction(function () use (
            $product, $quantityChange, $type, $previousQuantity, $newQuantity,
            $referenceType, $referenceId, $notes, $userId
        ) {
            // 1. Update Product table
            $product->quantity = $newQuantity;
            $product->save();

            // 2. Append to Stock Movement Ledger
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'type' => $type,
                'quantity_change' => $quantityChange,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes,
            ]);
        });

        return $product;
    }
}
