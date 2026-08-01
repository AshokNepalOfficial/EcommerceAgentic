<?php
namespace App\Contracts\Services;
use App\DTOs\AddToWishlistDTO;
use Illuminate\Support\Collection;
interface WishlistServiceInterface {
    public function getWishlist(?int $userId, ?string $sessionId, ?string $platform = null): Collection;
    public function addToWishlist(AddToWishlistDTO $dto): void;
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool;
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void;
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void;
}