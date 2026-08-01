<?php
namespace App\Contracts\Services;
use App\DTOs\AddToCartDTO;
use Illuminate\Support\Collection;
interface CartServiceInterface {
    public function getCart(?int $userId, ?string $sessionId, ?string $platform = null): Collection;
    public function addToCart(AddToCartDTO $dto): void;
    public function updateQuantity(int $itemId, int $quantity, ?int $userId, ?string $sessionId): void;
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool;
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void;
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void;
    public function getBySource(string $sourceId, string $platform): Collection;
}