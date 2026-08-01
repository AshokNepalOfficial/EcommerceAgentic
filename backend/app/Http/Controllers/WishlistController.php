<?php
namespace App\Http\Controllers;
use App\Contracts\Services\WishlistServiceInterface;
use App\DTOs\AddToWishlistDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class WishlistController extends Controller {
    public function __construct(protected WishlistServiceInterface $wishlistService) {}
    private function getIdentifier(Request $request): array {
        return ['user_id' => auth()->id(), 'session_id' => auth()->check() ? null : ($request->session()->get('guest_session_id', Str::random(40))), 'platform' => $request->header('Platform', 'web'), 'source_id' => $request->header('Source-Id'), 'device_id' => $request->header('Device-Id'), 'ip_address' => $request->ip(), 'user_agent' => $request->userAgent()];
    }
    public function index(Request $request) {
        $ids = $this->getIdentifier($request);
        if (!$ids['user_id'] && !$request->session()->has('guest_session_id')) $request->session()->put('guest_session_id', $ids['session_id']);
        $wishlistItems = $this->wishlistService->getWishlist($ids['user_id'], $ids['session_id']);
        return view('wishlist.index', compact('wishlistItems'));
    }
    public function add(Request $request) {
        $request->validate(['product_id' => 'required|exists:products,id','variant_id' => 'nullable|exists:product_variants,id']);
        $ids = $this->getIdentifier($request);
        if (!$ids['user_id'] && !$request->session()->has('guest_session_id')) $request->session()->put('guest_session_id', $ids['session_id']);
        $dto = new AddToWishlistDTO($request->product_id, $request->variant_id, null, $ids['user_id'], $ids['session_id'], $ids['platform'], $ids['source_id'], $ids['device_id'], $ids['ip_address'], $ids['user_agent']);
        try { $this->wishlistService->addToWishlist($dto); return response()->json(['success' => true, 'message' => 'Added to wishlist!']); } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()], 400); }
    }
    public function remove(Request $request) {
        $request->validate(['item_id' => 'required|exists:wishlists,id']);
        $ids = $this->getIdentifier($request);
        $this->wishlistService->removeItem($request->item_id, $ids['user_id'], $ids['session_id']);
        return response()->json(['success' => true, 'message' => 'Removed from wishlist!']);
    }
    public function clear(Request $request) {
        $ids = $this->getIdentifier($request);
        $this->wishlistService->clear($ids['user_id'], $ids['session_id']);
        return response()->json(['success' => true, 'message' => 'Wishlist cleared!']);
    }
}