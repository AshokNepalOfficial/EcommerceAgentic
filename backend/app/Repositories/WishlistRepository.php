<?php
namespace App\Repositories;
use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Models\Wishlist;
use Illuminate\Support\Collection;
class WishlistRepository implements WishlistRepositoryInterface {
    public function getWishlist(?int $userId, ?string $sessionId, ?string $platform = null): Collection {
        return Wishlist::with(['variant.product','bundle'])->where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->when($platform, fn($q) => $q->where('platform',$platform))->get();
    }
    public function addVariant(int $variantId, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Wishlist {
        return Wishlist::firstOrCreate(
            ['product_variant_id' => $variantId, 'item_type' => 'variant', 'user_id' => $userId, 'session_id' => $sessionId, 'platform' => $platform, 'source_id' => $sourceId],
            ['device_id' => $deviceId, 'ip_address' => $ipAddress, 'user_agent' => $userAgent]
        );
    }
    public function addBundle(int $bundleId, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Wishlist {
        return Wishlist::firstOrCreate(
            ['bundle_product_id' => $bundleId, 'item_type' => 'bundle', 'user_id' => $userId, 'session_id' => $sessionId, 'platform' => $platform, 'source_id' => $sourceId],
            ['device_id' => $deviceId, 'ip_address' => $ipAddress, 'user_agent' => $userAgent]
        );
    }
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool {
        return Wishlist::where('id',$itemId)->where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->delete() > 0;
    }
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void {
        Wishlist::where(fn($q) => $userId ? $q->where('user_id',$userId) : $q->where('session_id',$sessionId))->when($platform, fn($q) => $q->where('platform',$platform))->delete();
    }
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void {
        $guestItems = Wishlist::where('session_id',$sessionId)->when($platform, fn($q) => $q->where('platform',$platform))->get();
        foreach ($guestItems as $guest) {
            if ($guest->item_type === 'variant') {
                Wishlist::firstOrCreate(
                    ['user_id' => $userId, 'product_variant_id' => $guest->product_variant_id, 'item_type' => 'variant', 'platform' => $guest->platform, 'source_id' => $guest->source_id],
                    ['device_id' => $guest->device_id, 'ip_address' => $guest->ip_address, 'user_agent' => $guest->user_agent]
                );
            } else {
                Wishlist::firstOrCreate(
                    ['user_id' => $userId, 'bundle_product_id' => $guest->bundle_product_id, 'item_type' => 'bundle', 'platform' => $guest->platform, 'source_id' => $guest->source_id],
                    ['device_id' => $guest->device_id, 'ip_address' => $guest->ip_address, 'user_agent' => $guest->user_agent]
                );
            }
            $guest->delete();
        }
    }
}