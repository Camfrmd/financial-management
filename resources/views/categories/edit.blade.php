@extends('layouts.app')

@section('title', __('Edit Category'))
@section('back_button', true)
@section('back_url', route('categories.index'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">{{ __('Edit Category') }}: {{ $category->category_name }}</h1>
        <p class="text-gray-400">{{ __('Modify the details of your account category.') }}</p>
    </div>
</div>

<div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden max-w-2xl">
    <div class="p-6">
        <form action="{{ route('categories.update', $category->category_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Category Name -->
            <div class="mb-6">
                <label for="category_name" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Category Name') }}</label>
                <input type="text" name="category_name" id="category_name" required value="{{ old('category_name', $category->category_name) }}"
                       class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                @error('category_name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent Category (Optional) -->
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Parent Category (Optional)') }}</label>
                @if($category->children()->count() > 0)
                    <!-- Disable changing parent if it has children -->
                    <select name="parent_id" id="parent_id" disabled
                            class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white opacity-50 cursor-not-allowed">
                        <option value="">-- {{ __('Main Category (No Parent)') }} --</option>
                    </select>
                    <p class="text-yellow-500 text-xs mt-2">{{ __('Cannot assign a parent because this category has sub-categories.') }}</p>
                @else
                    <select name="parent_id" id="parent_id"
                            class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                        <option value="">-- {{ __('Main Category (No Parent)') }} --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->category_id }}" data-type="{{ $parent->type }}" {{ old('parent_id', $category->parent_id) == $parent->category_id ? 'selected' : '' }}>
                                {{ $parent->category_name }} ({{ ucfirst($parent->type) }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-1">{{ __('Select a parent category to make this a sub-category.') }}</p>
                    @error('parent_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Type -->
            <div class="mb-8">
                <label for="type" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Account Type') }}</label>
                <select name="type" id="type" required
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>{{ __('Income') }}</option>
                    <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>{{ __('Expense') }}</option>
                </select>
                <p id="type_help" class="text-gray-500 text-xs mt-1">{{ __('Sub-categories will automatically inherit their parent\'s type.') }}</p>
                @error('type')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between pt-4 border-t border-gray-800">
                <a href="{{ route('categories.index') }}" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all">
                    {{ __('Update Category') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentSelect = document.getElementById('parent_id');
        const typeSelect = document.getElementById('type');
        
        if (!parentSelect) return; // If disabled/missing

        function updateTypeSelect() {
            if (parentSelect.value) {
                const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                const parentType = selectedOption.getAttribute('data-type');
                
                typeSelect.value = parentType;
                typeSelect.disabled = true;
            } else {
                typeSelect.disabled = false;
            }
        }

        parentSelect.addEventListener('change', updateTypeSelect);
        
        // Run on load in case there's old data or existing parent
        updateTypeSelect();
    });
</script>
@endsection
