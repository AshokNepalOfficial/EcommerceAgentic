<?php
namespace App\Repositories;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
class CartRepository implements CartRepositoryInterface {
    public function getCart(?int $userId, ?string $sessionId, ?string $platform = null): Collection {
        return Cart::with(['variant.product','bundle'])->where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->when($platform, fn($q) => $q->where('platform',$platform))->get();
    }
    public function addVariant(int $variantId, int $quantity, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Cart {
        return Cart::updateOrCreate(
            ['product_variant_id' => $variantId, 'item_type' => 'variant', 'platform' => $platform, 'source_id' => $sourceId, 'user_id' => $userId, 'session_id' => $sessionId],
            ['quantity' => DB::raw("quantity + $quantity"), 'device_id' => $deviceId, 'ip_address' => $ipAddress, 'user_agent' => $userAgent]
        );
    }
    public function addBundle(int $bundleId, int $quantity, array $components, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Cart {
        return Cart::updateOrCreate(
            ['bundle_product_id' => $bundleId, 'item_type' => 'bundle', 'platform' => $platform, 'source_id' => $sourceId, 'user_id' => $userId, 'session_id' => $sessionId],
            ['quantity' => DB::raw("quantity + $quantity"), 'component_snapshot' => $components, 'device_id' => $deviceId, 'ip_address' => $ipAddress, 'user_agent' => $userAgent]
        );
    }
    public function updateQuantity(int $itemId, int $quantity, ?int $userId, ?string $sessionId): Cart {
        $cart = Cart::where('id',$itemId)->where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->firstOrFail();
        $cart->quantity = $quantity;
        $cart->save();
        return $cart;
    }
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool {
        return Cart::where('id',$itemId)->where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->delete() > 0;
    }
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void {
        Cart::where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->when($platform, fn($q) => $q->where('platform',$platform))->delete();
    }
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void {
        $guestItems = Cart::where('session_id',$sessionId)->when($platform, fn($q) => $q->where('platform',$platform))->get();
        foreach ($guestItems as $guest) {
            if ($guest->item_type === 'variant') {
                Cart::updateOrCreate(
                    ['user_id' => $userId, 'product_variant_id' => $guest->product_variant_id, 'item_type' => 'variant', 'platform' => $guest->platform, 'source_id' => $guest->source_id],
                    ['quantity' => DB::raw("quantity + {$guest->quantity}"), 'device_id' => $guest->device_id, 'ip_address' => $guest->ip_address, 'user_agent' => $guest->user_agent]
                );
            } else {
                Cart::updateOrCreate(
                    ['user_id' => $userId, 'bundle_product_id' => $guest->bundle_product_id, 'item_type' => 'bundle', 'platform' => $guest->platform, 'source_id' => $guest->source_id],
                    ['quantity' => DB::raw("quantity + {$guest->quantity}"), 'component_snapshot' => $guest->component_snapshot, 'device_id' => $guest->device_id, 'ip_address' => $guest->ip_address, 'user_agent' => $guest->user_agent]
                );
            }
            $guest->delete();
        }
    }
    public function getBySource(string $sourceId, string $platform): Collection {
        return Cart::with(['variant.product','bundle'])->where('source_id',$sourceId)->where('platform',$platform)->get();
    }
}