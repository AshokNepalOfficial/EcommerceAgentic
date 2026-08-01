@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="bg-white rounded-lg shadow p-6 text-center">
    <div class="mb-6">
        <svg class="mx-auto w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    <h1 class="text-3xl font-bold mb-2">Thank You for Your Order!</h1>
    <p class="text-gray-600 mb-4">Your order #{{ $id }} has been placed successfully.</p>
    <p class="text-gray-500 mb-6">We'll send you a confirmation email shortly.</p>
    <a href="/" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 inline-block">
        Continue Shopping
    </a>
</div>
@endsection