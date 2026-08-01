<?php
namespace App\Services;
use App\Contracts\Services\CheckoutServiceInterface;
use App\DTOs\CheckoutDTO;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class CheckoutService implements CheckoutServiceInterface {
    public function processOrder(CheckoutDTO $dto): Order {
        DB::beginTransaction();
        try {
            $subtotal = collect($dto->cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
            $order = Order::create([
                'user_id' => $dto->userId,
                'session_id' => session()->getId(),
                'order_number' => 'ORD-' . Str::upper(Str::random(10)),
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'grand_total' => $subtotal,
                'items_snapshot' => $dto->cartItems,
                'customer_name' => $dto->customerInfo['name'] ?? null,
                'customer_email' => $dto->customerInfo['email'] ?? null,
                'customer_phone' => $dto->customerInfo['phone'] ?? null,
                'shipping_address' => $dto->shippingAddress,
                'billing_address' => $dto->billingAddress,
                'platform' => $dto->platform,
                'source_id' => $dto->sourceId,
                'device_id' => $dto->deviceId,
                'ip_address' => $dto->ipAddress,
                'user_agent' => $dto->userAgent,
                'payment_method' => $dto->paymentMethod,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);
            foreach ($dto->cartItems as $item) {
                if ($item['item_type'] === 'bundle') {
                    foreach ($item['components'] as $comp) {
                        $variant = ProductVariant::findOrFail($comp['id']);
                        $variant->stock -= ($comp['quantity'] * $item['quantity']);
                        $variant->save();
                    }
                    OrderItem::create(['order_id' => $order->id, 'bundle_product_id' => $item['product_id'], 'item_type' => 'bundle', 'name_snapshot' => $item['name'], 'price_snapshot' => $item['price'], 'quantity' => $item['quantity'], 'component_snapshot' => $item['components'], 'platform' => $dto->platform, 'source_id' => $dto->sourceId]);
                } else {
                    $variant = ProductVariant::findOrFail($item['variant_id']);
                    $variant->stock -= $item['quantity'];
                    $variant->save();
                    OrderItem::create(['order_id' => $order->id, 'product_variant_id' => $variant->id, 'item_type' => 'variant', 'name_snapshot' => $item['name'], 'price_snapshot' => $item['price'], 'quantity' => $item['quantity'], 'attributes_snapshot' => $item['attributes'] ?? null, 'platform' => $dto->platform, 'source_id' => $dto->sourceId]);
                }
            }
            DB::commit();
            return $order;
        } catch (\Exception $e) { DB::rollBack(); throw $e; }
    }
}