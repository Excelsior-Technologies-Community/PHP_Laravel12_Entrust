<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $product->name }}</h2>
            @permission('update-products')
                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest">Edit</a>
            @endpermission
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <dl class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">SKU</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $product->sku }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $product->is_active ? 'Active' : 'Inactive' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Price</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">Rs. {{ number_format($product->price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Stock</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $product->stock }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $product->description ?: 'No description added.' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
