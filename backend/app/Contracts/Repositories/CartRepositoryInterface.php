<?php
namespace App\Contracts\Repositories;
use App\Models\Cart;
use Illuminate\Support\Collection;
interface CartRepositoryInterface {
    public function getCart(?int $userId, ?string $sessionId, ?string $platform = null): Collection;
    public function addVariant(int $variantId, int $quantity, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Cart;
    public function addBundle(int $bundleId, int $quantity, array $components, ?int $userId, ?string $sessionId, string $platform, ?string $sourceId, ?string $deviceId, ?string $ipAddress, ?string $userAgent): Cart;
    public function updateQuantity(int $itemId, int $quantity, ?int $userId, ?string $sessionId): Cart;
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool;
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void;
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void;
    public function getBySource(string $sourceId, string $platform): Collection;
}