@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Orders</h1>
    @if(!empty($platform))
        <p class="text-sm text-gray-500 mb-4">Filtered by platform: <span class="font-medium">{{ $platform }}</span></p>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Order #</th>
                    <th class="text-left py-2">Customer</th>
                    <th class="text-left py-2">Total</th>
                    <th class="text-left py-2">Platform</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Payment</th>
                    <th class="text-left py-2">Date</th>
                    <th class="text-right py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</td>
                        <td>${{ number_format($order->grand_total, 2) }}</td>
                        <td><span class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $order->platform }}</span></td>
                        <td><span class="text-xs px-2 py-0.5 rounded {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') }}">{{ $order->status }}</span></td>
                        <td><span class="text-xs px-2 py-0.5 rounded {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $order->payment_status }}</span></td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection