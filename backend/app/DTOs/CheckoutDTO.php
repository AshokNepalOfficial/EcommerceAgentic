<?php
namespace App\DTOs;
class CheckoutDTO {
    public function __construct(
        public readonly int $userId,
        public readonly array $customerInfo,
        public readonly array $shippingAddress,
        public readonly array $billingAddress,
        public readonly string $paymentMethod,
        public readonly array $cartItems,
        public readonly string $platform = 'web',
        public readonly ?string $sourceId = null,
        public readonly ?string $deviceId = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null
    ) {}
}