<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;
class OrderController extends Controller {
    public function __construct(protected OrderRepositoryInterface $orderRepo) {}
    public function index(Request $request) {
        $platform = $request->get('platform');
        $orders = $platform ? $this->orderRepo->getByPlatform($platform) : $this->orderRepo->getByUser(auth()->id(), 20);
        return view('admin.orders.index', compact('orders', 'platform'));
    }
    public function show(int $id) { $order = $this->orderRepo->find($id); return view('admin.orders.show', compact('order')); }
    public function updateStatus(Request $request, int $id) {
        $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded']);
        $order = $this->orderRepo->updateStatus($id, $request->status);
        return back()->with('success', 'Order status updated.');
    }
    public function updatePayment(Request $request, int $id) {
        $request->validate(['payment_status' => 'required|in:pending,paid,failed,refunded']);
        $order = $this->orderRepo->updatePaymentStatus($id, $request->payment_status);
        return back()->with('success', 'Payment status updated.');
    }
    public function byPlatform(string $platform) { $orders = $this->orderRepo->getByPlatform($platform); return view('admin.orders.platform', compact('orders', 'platform')); }
    public function bySource(Request $request) {
        $request->validate(['source_id' => 'required|string','platform' => 'required|string']);
        $order = $this->orderRepo->getBySource($request->source_id, $request->platform);
        if (!$order) return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        return response()->json(['success' => true, 'order' => $order]);
    }
}