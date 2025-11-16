<!DOCTYPE html>
<html lang="pl">
@include('layouts.head', ['title' => 'Update product'])
<body class="bg-gray-100">

<div class="flex h-screen" x-data="{ sidebarOpen: false }">
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
        @include('layouts.header')

        <main class="flex-1 p-6 overflow-y-auto">
            <div>
                @include('layouts.flash-messages')
            </div>

            <div class="w-full bg-white shadow-md rounded-lg mb-3">
                <div class="p-6 flex justify-between items-center">
                    <p class="text-xl">Update produit: {{ $product->name }}</p>
                    <a href="{{ url()->previous() }}">
                        <button class="bg-gray-500 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none ease-linear transition-all duration-150" type="button">
                            Back
                        </button>
                    </a>
                </div>
            </div>

            <div class="w-full bg-white shadow-md rounded-lg">
                <div class="p-6">
                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                @include('layouts.components.forms.input', [
                                    'name' => 'Nom',
                                    'inputId' => 'name',
                                    'inputName' => 'name',
                                    'inputType' => 'text',
                                    'inputValue' => old('name', $product->name),
                                    'inputRequired' => true
                                ])

                                @include('layouts.components.forms.input', [
                                    'name' => 'Categorie',
                                    'inputId' => 'category',
                                    'inputName' => 'category',
                                    'inputType' => 'text',
                                    'inputValue' => old('category', $product->category),
                                    'inputRequired' => true
                                ])
                            </div>

                            <div>
                                @include('layouts.components.forms.input', [
                                    'name' => 'Compteur',
                                    'inputId' => 'count',
                                    'inputName' => 'count',
                                    'inputType' => 'number',
                                    'inputValue' => old('count', $product->count),
                                    'inputRequired' => true
                                ])

                                @include('layouts.components.forms.input', [
                                    'name' => 'Prix',
                                    'inputId' => 'price',
                                    'inputName' => 'price',
                                    'inputType' => 'number',
                                    'inputStep' => '0.01',
                                    'inputMin' => '0',
                                    'inputValue' => old('price', number_format($product->price, 2, '.', '')),
                                    'inputRequired' => true
                                ])

                                <div class="mb-4">
                                    <label for="is_active" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            <span class="text-gray-500"><i class="fa fa-toggle-on"></i></span>
                                        </span>
                                        <select id="is_active" name="is_active" required
                                                class="rounded-none rounded-e-lg border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end border-t border-gray-200">
                            <button type="submit" class="bg-blue-500 mt-3 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Update product</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>

        @include('layouts.footer')
    </div>
</div>

</body>
</html>