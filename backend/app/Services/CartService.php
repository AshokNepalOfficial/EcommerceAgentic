<?php
namespace App\Services;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Services\CartServiceInterface;
use App\DTOs\AddToCartDTO;
use App\Models\Product;
use Illuminate\Support\Collection;
class CartService implements CartServiceInterface {
    public function __construct(protected CartRepositoryInterface $cartRepo) {}
    public function getCart(?int $userId, ?string $sessionId, ?string $platform = null): Collection { return $this->cartRepo->getCart($userId, $sessionId, $platform); }
    public function addToCart(AddToCartDTO $dto): void {
        $product = Product::with(['variants','bundleComponents'])->findOrFail($dto->productId);
        if ($product->type === 'grouped') throw new \Exception('Grouped products cannot be added to cart directly.');
        if ($product->type === 'bundle') {
            $components = $product->bundleComponents->map(fn($c) => ['id' => $c->id,'title' => $c->title,'price' => $c->price,'quantity' => $c->pivot->quantity,'stock' => $c->stock])->toArray();
            $this->cartRepo->addBundle($dto->productId, $dto->quantity, $components, $dto->userId, $dto->sessionId, $dto->platform, $dto->sourceId, $dto->deviceId, $dto->ipAddress, $dto->userAgent);
            return;
        }
        if ($product->type === 'simple') { $variant = $product->variants()->firstOrFail(); $this->cartRepo->addVariant($variant->id, $dto->quantity, $dto->userId, $dto->sessionId, $dto->platform, $dto->sourceId, $dto->deviceId, $dto->ipAddress, $dto->userAgent); return; }
        if ($product->type === 'variable') { if (!$dto->variantId) throw new \Exception('Please select a specific variant.'); $this->cartRepo->addVariant($dto->variantId, $dto->quantity, $dto->userId, $dto->sessionId, $dto->platform, $dto->sourceId, $dto->deviceId, $dto->ipAddress, $dto->userAgent); return; }
    }
    public function updateQuantity(int $itemId, int $quantity, ?int $userId, ?string $sessionId): void { $this->cartRepo->updateQuantity($itemId, $quantity, $userId, $sessionId); }
    public function removeItem(int $itemId, ?int $userId, ?string $sessionId): bool { return $this->cartRepo->removeItem($itemId, $userId, $sessionId); }
    public function clear(?int $userId, ?string $sessionId, ?string $platform = null): void { $this->cartRepo->clear($userId, $sessionId, $platform); }
    public function mergeGuestToUser(int $userId, string $sessionId, ?string $platform = null): void { $this->cartRepo->mergeGuestToUser($userId, $sessionId, $platform); }
    public function getBySource(string $sourceId, string $platform): Collection { return $this->cartRepo->getBySource($sourceId, $platform); }
}