@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    @if(isset($variant))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <img src="{{ $variant->primary_image->image_url ?? asset('default.jpg') }}" 
                     alt="{{ $variant->primary_image->alt_text ?? $variant->title }}" 
                     class="w-full rounded-lg shadow">
                @if($variant->images->count())
                    <div class="flex gap-2 mt-4 overflow-x-auto">
                        @foreach($variant->images as $image)
                            <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}" 
                                 class="w-20 h-20 object-cover rounded border cursor-pointer" 
                                 onclick="document.querySelector('.main-image').src=this.src">
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ $variant->title }}</h1>
                <p class="text-gray-600 mt-2">{{ $variant->description ?? $variant->product->description }}</p>
                <div class="mt-4">
                    <span class="text-3xl font-bold text-indigo-600">${{ number_format($variant->price, 2) }}</span>
                    <span class="ml-4 text-sm {{ $variant->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $variant->stock > 0 ? 'In Stock ('.$variant->stock.')' : 'Out of Stock' }}
                    </span>
                </div>
                @if($variant->attributeValues->count())
                    <div class="mt-4 space-y-2">
                        @foreach($variant->attributeValues as $attrVal)
                            <p><strong>{{ $attrVal->attribute->name }}:</strong> {{ $attrVal->value }}</p>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $variant->stock }}" 
                           class="w-16 border rounded px-2 py-1">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                        Add to Cart
                    </button>
                </form>
                @if($siblings->count())
                    <div class="mt-8 pt-4 border-t">
                        <h3 class="font-semibold text-lg">Other Options</h3>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($siblings as $sib)
                                <a href="{{ route('product.detail', $sib->slug) }}" 
                                   class="bg-gray-100 px-3 py-1 rounded hover:bg-gray-200">
                                    {{ $sib->title }} (${{ number_format($sib->price, 2) }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- Grouped or Bundle product --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $product->description }}</p>
        </div>
        @if($product->type === 'grouped')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($product->groupedProducts as $child)
                    <div class="border rounded-lg p-4 shadow-sm">
                        <h3 class="font-semibold text-lg">{{ $child->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $child->short_description }}</p>
                        @foreach($child->variants as $variant)
                            <div class="mt-2">
                                <span class="font-bold">${{ number_format($variant->price, 2) }}</span>
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-2 flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $child->id }}">
                                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $variant->stock }}" 
                                           class="w-16 border rounded px-1 py-1 text-sm">
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                        Add
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @elseif($product->type === 'bundle')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <img src="{{ $product->og_image ?? asset('default-bundle.jpg') }}" alt="{{ $product->name }}" class="w-full rounded-lg">
                </div>
                <div>
                    @php
                        $bundlePrice = $product->bundleComponents->sum(fn($c) => $c->price * $c->pivot->quantity);
                    @endphp
                    <h2 class="text-3xl font-bold text-indigo-600">${{ number_format($bundlePrice, 2) }}</h2>
                    <ul class="mt-4 list-disc pl-5">
                        @foreach($product->bundleComponents as $comp)
                            <li>{{ $comp->title }} x{{ $comp->pivot->quantity }}</li>
                        @endforeach
                    </ul>
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" value="1" min="1" class="w-20 border rounded px-2 py-1 mr-2">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                            Add Bundle to Cart
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection