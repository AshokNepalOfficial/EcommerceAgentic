<?php
namespace App\Contracts\Repositories;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
interface OrderRepositoryInterface {
    public function create(array $data): Order;
    public function find(int $id): Order;
    public function findByOrderNumber(string $orderNumber): Order;
    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator;
    public function getByPlatform(string $platform, int $perPage = 10): LengthAwarePaginator;
    public function getBySource(string $sourceId, string $platform): ?Order;
    public function updateStatus(int $id, string $status): Order;
    public function updatePaymentStatus(int $id, string $status): Order;
}