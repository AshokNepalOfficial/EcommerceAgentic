<?php
namespace App\Contracts\Repositories;
use App\Models\Wishlist;
use Illuminate\Support\Collection;
interface WishlistRepositoryInterface {
    public function getWishlist(?int $userId, ?string $sessionId, ?string $platform = null): Collection;
    public function addVariant(int $variantId, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Wishlist;
    public function addBundle(int $bundleId, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Wishlist;
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool;
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void;
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void;
}