<?php
namespace App\Contracts\Services;
use App\DTOs\GenerateVariantDTO;
interface VariantGeneratorServiceInterface {
    public function generateVariants(GenerateVariantDTO $dto): int;
}