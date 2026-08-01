<?php
namespace App\Contracts\Services;
use App\DTOs\CheckoutDTO;
use App\Models\Order;
interface CheckoutServiceInterface {
    public function processOrder(CheckoutDTO $dto): Order;
}