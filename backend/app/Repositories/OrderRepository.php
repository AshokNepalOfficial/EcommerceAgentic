<?php
namespace App\Repositories;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
class OrderRepository implements OrderRepositoryInterface {
    public function create(array $data): Order { return Order::create($data); }
    public function find(int $id): Order { return Order::with(['items','user'])->findOrFail($id); }
    public function findByOrderNumber(string $orderNumber): Order { return Order::with(['items','user'])->where('order_number',$orderNumber)->firstOrFail(); }
    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator { return Order::with(['items'])->where('user_id',$userId)->orderBy('created_at','desc')->paginate($perPage); }
    public function getByPlatform(string $platform, int $perPage = 10): LengthAwarePaginator { return Order::with(['items','user'])->where('platform',$platform)->orderBy('created_at','desc')->paginate($perPage); }
    public function getBySource(string $sourceId, string $platform): ?Order { return Order::with(['items','user'])->where('source_id',$sourceId)->where('platform',$platform)->first(); }
    public function updateStatus(int $id, string $status): Order { $order = $this->find($id); $order->status = $status; $order->save(); return $order; }
    public function updatePaymentStatus(int $id, string $status): Order { $order = $this->find($id); $order->payment_status = $status; $order->save(); return $order; }
}