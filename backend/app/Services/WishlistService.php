<?php
namespace App\Services;
use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Contracts\Services\WishlistServiceInterface;
use App\DTOs\AddToWishlistDTO;
use App\Models\Product;
use Illuminate\Support\Collection;
class WishlistService implements WishlistServiceInterface {
    public function __construct(protected WishlistRepositoryInterface $wishlistRepo) {}
    public function getWishlist(?int $userId, ?string $sessionId, ?string $platform = null): Collection { return $this->wishlistRepo->getWishlist($userId, $sessionId, $platform); }
    public function addToWishlist(AddToWishlistDTO $dto): void {
        $product = Product::findOrFail($dto->productId);
        if ($product->type === 'grouped') throw new \Exception('Grouped products cannot be added to wishlist directly.');
        if ($product->type === 'bundle') { $this->wishlistRepo->addBundle($dto->productId, $dto->userId, $dto->sessionId, $dto->platform, $dto->sourceId, $dto->deviceId, $dto->ipAddress, $dto->userAgent); return; }
        if ($product->type === 'simple') { $variant = $product->variants()->firstOrFail(); $this->wishlistRepo->addVariant($variant->id, $dto->userId, $dto->sessionId, $dto->platform, $dto->sourceId, $dto->deviceId, $dto->ipAddress, $dto->userAgent); return; }
        if ($product->type === 'variable') { if (!$dto->variantId) throw new \Exception('Please select a specific variant.'); $this->wishlistRepo->addVariant($dto->variantId, $dto->userId, $dto->sessionId, $dto->platform, $dto->sourceId, $dto->deviceId, $dto->ipAddress, $dto->userAgent); return; }
    }
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool { return $this->wishlistRepo->removeItem($itemId, $userId, $sessionId); }
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void { $this->wishlistRepo->clear($userId, $sessionId, $platform); }
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void { $this->wishlistRepo->mergeGuestToUser($userId, $sessionId, $platform); }
}