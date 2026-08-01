@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
            @php $total = 0; @endphp
            @foreach($cartItems as $item)
                @php $subtotal = $item->price * $item->quantity; $total += $subtotal; @endphp
                <div class="flex justify-between border-b py-2">
                    <span>{{ $item->name }} x{{ $item->quantity }}</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
            @endforeach
            <div class="flex justify-between font-bold text-lg mt-4">
                <span>Total</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-4">Shipping & Payment</h2>
            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="customer_name" required class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="customer_email" required class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="customer_phone" required class="mt-1 block w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <textarea name="shipping_address[address]" rows="3" required class="mt-1 block w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                    <textarea name="billing_address[address]" rows="3" required class="mt-1 block w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" required class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="cash">Cash on Delivery</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                    Place Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/order/confirmation/' + data.order_id;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => alert('Something went wrong. Please try again.'));
    });
</script>
@endpush