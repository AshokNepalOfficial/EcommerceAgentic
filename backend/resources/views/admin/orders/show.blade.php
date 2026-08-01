@extends('layouts.app')

@section('title', 'Order #'.$order->order_number)

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-500">Placed on {{ $order->created_at->format('F d, Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </form>
            <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <select name="payment_status" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
        <div>
            <h3 class="font-semibold">Customer Information</h3>
            <p>{{ $order->customer_name ?? 'N/A' }}</p>
            <p>{{ $order->customer_email ?? 'N/A' }}</p>
            <p>{{ $order->customer_phone ?? 'N/A' }}</p>
            <h4 class="font-semibold mt-4">Shipping Address</h4>
            <p>{{ $order->shipping_address['address'] ?? 'N/A' }}</p>
            <h4 class="font-semibold mt-4">Billing Address</h4>
            <p>{{ $order->billing_address['address'] ?? 'N/A' }}</p>
        </div>
        <div>
            <h3 class="font-semibold">Order Details</h3>
            <p><strong>Platform:</strong> {{ $order->platform }}</p>
            <p><strong>Source ID:</strong> {{ $order->source_id ?? 'N/A' }}</p>
            <p><strong>Device:</strong> {{ $order->device_id ?? 'N/A' }}</p>
            <p><strong>IP:</strong> {{ $order->ip_address ?? 'N/A' }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
            <p><strong>Payment ID:</strong> {{ $order->payment_id ?? 'N/A' }}</p>
        </div>
    </div>

    <h3 class="font-semibold text-lg mb-4">Items</h3>
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left py-2">Product</th>
                <th class="text-right py-2">Price</th>
                <th class="text-right py-2">Qty</th>
                <th class="text-right py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr class="border-b">
                    <td class="py-2">{{ $item->name_snapshot }}</td>
                    <td class="text-right">${{ number_format($item->price_snapshot, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price_snapshot * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right font-bold py-2">Subtotal</td>
                <td class="text-right font-bold">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax_amount > 0)
            <tr>
                <td colspan="3" class="text-right py-1">Tax</td>
                <td class="text-right">${{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->shipping_cost > 0)
            <tr>
                <td colspan="3" class="text-right py-1">Shipping</td>
                <td class="text-right">${{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            @endif
            @if($order->discount_amount > 0)
            <tr>
                <td colspan="3" class="text-right py-1">Discount</td>
                <td class="text-right">-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3" class="text-right font-bold text-lg py-2">Grand Total</td>
                <td class="text-right font-bold text-lg">${{ number_format($order->grand_total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="mt-6">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:underline">Back to Orders</a>
    </div>
</div>
@endsection