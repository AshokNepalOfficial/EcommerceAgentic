<?php
namespace App\Http\Controllers;
use App\Contracts\Services\CartServiceInterface;
use App\DTOs\AddToCartDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CartController extends Controller {
    public function __construct(protected CartServiceInterface $cartService) {}
    private function getIdentifier(Request $request): array {
        return [
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : ($request->session()->get('guest_session_id', Str::random(40))),
            'platform' => $request->header('Platform', 'web'),
            'source_id' => $request->header('Source-Id'),
            'device_id' => $request->header('Device-Id'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];
    }
    public function index(Request $request) {
        $ids = $this->getIdentifier($request);
        if (!$ids['user_id'] && !$request->session()->has('guest_session_id')) $request->session()->put('guest_session_id', $ids['session_id']);
        $cartItems = $this->cartService->getCart($ids['user_id'], $ids['session_id']);
        return view('cart.index', compact('cartItems'));
    }
    public function add(Request $request) {
        $request->validate(['product_id' => 'required|exists:products,id','variant_id' => 'nullable|exists:product_variants,id','quantity' => 'integer|min:1']);
        $ids = $this->getIdentifier($request);
        if (!$ids['user_id'] && !$request->session()->has('guest_session_id')) $request->session()->put('guest_session_id', $ids['session_id']);
        $dto = new AddToCartDTO($request->product_id, $request->variant_id, null, $request->quantity ?? 1, $ids['user_id'], $ids['session_id'], $ids['platform'], $ids['source_id'], $ids['device_id'], $ids['ip_address'], $ids['user_agent']);
        try { $this->cartService->addToCart($dto); return response()->json(['success' => true, 'message' => 'Added to cart!']); } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()], 400); }
    }
    public function update(Request $request) {
        $request->validate(['item_id' => 'required|exists:carts,id','quantity' => 'required|integer|min:0']);
        $ids = $this->getIdentifier($request);
        if ($request->quantity == 0) $this->cartService->removeItem($request->item_id, $ids['user_id'], $ids['session_id']);
        else $this->cartService->updateQuantity($request->item_id, $request->quantity, $ids['user_id'], $ids['session_id']);
        return response()->json(['success' => true, 'message' => 'Cart updated!']);
    }
    public function remove(Request $request) {
        $request->validate(['item_id' => 'required|exists:carts,id']);
        $ids = $this->getIdentifier($request);
        $this->cartService->removeItem($request->item_id, $ids['user_id'], $ids['session_id']);
        return response()->json(['success' => true, 'message' => 'Removed from cart!']);
    }
    public function clear(Request $request) {
        $ids = $this->getIdentifier($request);
        $this->cartService->clear($ids['user_id'], $ids['session_id']);
        return response()->json(['success' => true, 'message' => 'Cart cleared!']);
    }
}