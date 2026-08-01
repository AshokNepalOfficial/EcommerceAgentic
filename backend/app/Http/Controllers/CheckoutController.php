<?php
namespace App\Http\Controllers;
use App\Contracts\Services\CartServiceInterface;
use App\Contracts\Services\CheckoutServiceInterface;
use App\DTOs\CheckoutDTO;
use Illuminate\Http\Request;
class CheckoutController extends Controller {
    public function __construct(protected CartServiceInterface $cartService, protected CheckoutServiceInterface $checkoutService) {}
    public function index(Request $request) {
        $cartItems = $this->cartService->getCart(auth()->id(), $request->session()->get('guest_session_id'));
        if ($cartItems->isEmpty()) return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        return view('checkout.index', compact('cartItems'));
    }
    public function place(Request $request) {
        $cartItems = $this->cartService->getCart(auth()->id(), $request->session()->get('guest_session_id'));
        if ($cartItems->isEmpty()) return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
        $request->validate(['customer_name' => 'required|string','customer_email' => 'required|email','customer_phone' => 'required|string','shipping_address' => 'required|array','billing_address' => 'required|array','payment_method' => 'required|string']);
        $dto = new CheckoutDTO(auth()->id(), $request->only(['customer_name','customer_email','customer_phone']), $request->shipping_address, $request->billing_address, $request->payment_method, $cartItems->toArray(), $request->header('Platform', 'web'), $request->header('Source-Id'), $request->header('Device-Id'), $request->ip(), $request->userAgent());
        try { $order = $this->checkoutService->processOrder($dto); $this->cartService->clear(auth()->id(), $request->session()->get('guest_session_id')); return response()->json(['success' => true, 'message' => 'Order placed!', 'order_id' => $order->id, 'order_number' => $order->order_number]); } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()], 500); }
    }
}