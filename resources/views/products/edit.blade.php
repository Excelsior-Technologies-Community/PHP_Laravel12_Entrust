<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Product</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @method('PUT')
                    @include('products._form', ['buttonText' => 'Update Product'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
