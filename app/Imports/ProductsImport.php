<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Shared\Date;

class ProductsImport implements ToCollection, SkipsEmptyRows, WithHeadingRow
{
    public function __construct(private readonly ?int $userId = null)
    {
    }

    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $data = $this->validateRow($row->toArray(), $index + 2);

                $brand = Brand::firstOrCreate(
                    ['name' => $data['brand']],
                    ['status' => true]
                );

                $category = Category::firstOrCreate(
                    ['name' => $data['category']],
                    ['status' => true]
                );

                $unit = Unit::firstOrCreate(
                    ['title' => $data['unit']],
                    ['short_name' => $data['unit']]
                );

                $product = Product::where('sku', $data['sku'])->first();
                $shouldRecordInitialPurchase = ! $product;

                $product = Product::updateOrCreate(
                    ['sku' => $data['sku']],
                    [
                        'image' => $data['image'] ?? '',
                        'name' => $data['name'],
                        'slug' => $this->uniqueSlug($data['sku'], $product),
                        'description' => $data['description'] ?? '',
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'unit_id' => $unit->id,
                        'price' => $data['price'],
                        'discount' => $data['discount'] ?? 0,
                        'discount_type' => $data['discount_type'] ?? 'fixed',
                        'purchase_price' => $data['purchase_price'],
                        'quantity' => $data['quantity'] ?? 0,
                        'expire_date' => $this->normalizeDate($data['expire_date'] ?? null),
                        'status' => $this->normalizeStatus($data['status'] ?? 1),
                    ]
                );

                if ($shouldRecordInitialPurchase) {
                    $this->recordInitialPurchase($product, (int) $data['quantity'], (float) $data['purchase_price'], (float) $data['price']);
                }
            }
        });
    }

    private function validateRow(array $row, int $rowNumber): array
    {
        $data = collect($row)
            ->mapWithKeys(fn($value, $key) => [Str::snake(trim((string) $key)) => is_string($value) ? trim($value) : $value])
            ->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:fixed,percentage'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'expire_date' => ['nullable'],
            'status' => ['nullable'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages([
                'file' => 'แถวที่ ' . $rowNumber . ': ' . $validator->errors()->first(),
            ]);
        }

        return $validator->validated();
    }

    private function uniqueSlug(string $sku, ?Product $product = null): string
    {
        $baseSlug = Str::slug(Str::lower($sku)) ?: 'product-' . Str::lower(Str::random(8));
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)
            ->when($product, fn($query) => $query->where('id', '!=', $product->id))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function normalizeDate(mixed $date): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        if ($date instanceof \DateTimeInterface) {
            return $date->format('Y-m-d');
        }

        if (is_numeric($date)) {
            return Date::excelToDateTimeObject($date)->format('Y-m-d');
        }

        $timestamp = strtotime((string) $date);

        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    private function normalizeStatus(mixed $status): int
    {
        if ($status === null || $status === '') {
            return 1;
        }

        $activeValues = ['1', 'true', 'active', 'yes', 'y', 'ใช้งาน', 'เปิด'];
        $normalized = Str::lower(trim((string) $status));

        return in_array($normalized, $activeValues, true) ? 1 : 0;
    }

    private function recordInitialPurchase(Product $product, int $quantity, float $purchasePrice, float $price): void
    {
        if (! $this->userId || $quantity <= 0) {
            return;
        }

        $supplier = Supplier::query()->first();

        if (! $supplier) {
            return;
        }

        $subTotal = $purchasePrice * $quantity;

        $purchase = Purchase::create([
            'supplier_id' => $supplier->id,
            'user_id' => $this->userId,
            'sub_total' => $subTotal,
            'tax' => 0,
            'discount_value' => 0,
            'discount_type' => 'fixed',
            'shipping' => 0,
            'grand_total' => $subTotal,
            'status' => 1,
            'date' => now(),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'purchase_price' => $purchasePrice,
            'price' => $price,
            'quantity' => $quantity,
        ]);
    }
}
