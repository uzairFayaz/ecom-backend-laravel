<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Your Orders</h1>
                    @if ($orders->isEmpty())
                        <p>You have no orders.</p>
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">Continue Shopping</a>
                    @else
                        <div class="mt-4">
                            @foreach ($orders as $order)
                                <div class="border p-4 mb-4 rounded-lg">
                                    <h2 class="text-lg font-semibold">Order #{{ $order->order_number }}</h2>
                                    <p>Placed on: {{ $order->created_at->format('M d, Y') }}</p>
                                    <p>Total: ${{ number_format($order->net_amount, 2) }}</p>
                                    <p>Status: {{ ucfirst($order->status) }}</p>
                                    <p>Payment: {{ ucfirst($order->payment_status) }} ({{ $order->payment_type }})</p>
                                    <h3 class="mt-2">Items:</h3>
                                    <table class="w-full table-auto">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-2">Product</th>
                                            <th class="px-4 py-2">Variant</th>
                                            <th class="px-4 py-2">Price</th>
                                            <th class="px-4 py-2">Quantity</th>
                                            <th class="px-4 py-2">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td class="border px-4 py-2">{{ $item->product_name }}</td>
                                                <td class="border px-4 py-2">{{ $item->color ?: 'N/A' }}</td>
                                                <td class="border px-4 py-2">${{ number_format($item->price, 2) }}</td>
                                                <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                                <td class="border px-4 py-2">${{ number_format($item->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
