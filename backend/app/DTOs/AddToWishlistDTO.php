<?php
namespace App\DTOs;
class AddToWishlistDTO {
    public function __construct(
        public readonly int $productId,
        public readonly ?int $variantId = null,
        public readonly ?int $bundleProductId = null,
        public readonly ?int $userId = null,
        public readonly ?string $sessionId = null,
        public readonly string $platform = 'web',
        public readonly ?string $sourceId = null,
        public readonly ?string $deviceId = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null
    ) {}
}