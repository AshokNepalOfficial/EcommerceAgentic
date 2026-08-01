<?php
namespace App\DTOs;
class GenerateVariantDTO {
    public function __construct(
        public readonly int $productId,
        public readonly array $attributeIds,
        public readonly float $basePrice,
        public readonly int $baseStock,
        public readonly string $skuPrefix,
        public readonly ?string $slugTemplate = null
    ) {}
}