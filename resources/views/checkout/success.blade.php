<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Thank You for Your Order!</h1>
                    <p>Your order #{{ $order->order_number }} has been placed.</p>
                    <p>Total: ${{ number_format($order->net_amount, 2) }}</p>
                    <p>Payment Status: {{ ucfirst($order->payment_status) }}</p>
                    <a href="{{ route('orders.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">View Your Orders</a>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
