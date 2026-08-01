@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">My Wishlist</h1>
    @if($wishlistItems->isEmpty())
        <p class="text-gray-500">Your wishlist is empty. <a href="/" class="text-indigo-600 hover:underline">Start browsing</a></p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    @if($item->item_type === 'bundle')
                        <div class="h-32 bg-gray-200 rounded flex items-center justify-center text-gray-500">Bundle</div>
                        <h3 class="font-semibold mt-2">{{ $item->bundle->name ?? 'Bundle' }}</h3>
                        <p class="text-gray-500 text-sm">Bundle</p>
                    @else
                        <img src="{{ $item->variant->thumbnail ?? asset('default.jpg') }}" 
                             alt="{{ $item->variant->title }}" class="w-full h-32 object-cover rounded">
                        <h3 class="font-semibold mt-2">{{ $item->variant->title }}</h3>
                        <p class="text-indigo-600 font-bold">${{ number_format($item->variant->price, 2) }}</p>
                    @endif
                    <div class="mt-3 flex gap-2">
                        <form action="{{ route('cart.add') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->item_type === 'bundle' ? $item->bundle_id : $item->variant->product_id }}">
                            <input type="hidden" name="variant_id" value="{{ $item->product_variant_id }}">
                            <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
                                Add to Cart
                            </button>
                        </form>
                        <form action="{{ route('wishlist.remove') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            <form action="{{ route('wishlist.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Clear Wishlist</button>
            </form>
        </div>
    @endif
</div>
@endsection