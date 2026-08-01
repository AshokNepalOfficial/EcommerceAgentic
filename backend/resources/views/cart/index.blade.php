@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>
    @if($cartItems->isEmpty())
        <p class="text-gray-500">Your cart is empty. <a href="/" class="text-indigo-600 hover:underline">Start shopping</a></p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Product</th>
                        <th class="text-center py-2">Price</th>
                        <th class="text-center py-2">Quantity</th>
                        <th class="text-right py-2">Subtotal</th>
                        <th class="text-right py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php
                            $subtotal = $item->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b">
                            <td class="py-3">
                                <div class="flex items-center gap-3">
                                    @if($item->item_type === 'bundle')
                                        <span class="font-medium">{{ $item->bundle->name ?? 'Bundle' }}</span>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Bundle</span>
                                    @else
                                        <img src="{{ $item->variant->thumbnail ?? asset('default.jpg') }}" 
                                             alt="{{ $item->variant->title }}" class="w-12 h-12 object-cover rounded">
                                        <span class="font-medium">{{ $item->variant->title }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">${{ number_format($item->price, 2) }}</td>
                            <td class="text-center">
                                <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" 
                                           class="w-16 border rounded px-1 py-1 text-center">
                                    <button type="submit" class="ml-1 text-sm text-indigo-600 hover:underline">Update</button>
                                </form>
                            </td>
                            <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                            <td class="text-right">
                                <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right font-bold py-4">Total:</td>
                        <td class="text-right font-bold text-lg">${{ number_format($total, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-end gap-4">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Clear Cart</button>
            </form>
            <a href="{{ route('checkout.index') }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                Proceed to Checkout
            </a>
        </div>
    @endif
</div>
@endsection