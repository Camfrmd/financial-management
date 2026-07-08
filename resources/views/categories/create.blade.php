@extends('layouts.app')

@section('title', __('Create Category'))
@section('back_button', true)
@section('back_url', route('categories.index'))

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white mb-2">{{ __('Create New Category') }}</h1>
    <p class="text-gray-400">{{ __('Add a new account to your Chart of Accounts.') }}</p>
</div>

<div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden max-w-2xl">
    <div class="p-6">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <!-- Category Name -->
            <div class="mb-6">
                <label for="category_name" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Category Name') }}</label>
                <input type="text" name="category_name" id="category_name" required value="{{ old('category_name') }}"
                       class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                @error('category_name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent Category (Optional) -->
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Parent Category (Optional)') }}</label>
                <select name="parent_id" id="parent_id"
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                    <option value="">-- {{ __('Main Category (No Parent)') }} --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->category_id }}" data-type="{{ $parent->type }}" {{ old('parent_id') == $parent->category_id ? 'selected' : '' }}>
                            {{ $parent->category_name }} ({{ ucfirst($parent->type) }})
                        </option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-xs mt-1">{{ __('Select a parent category to create a sub-category.') }}</p>
                @error('parent_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-8">
                <label for="type" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Account Type') }}</label>
                <select name="type" id="type" required
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>{{ __('Income') }}</option>
                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>{{ __('Expense') }}</option>
                </select>
                <p id="type_help" class="text-gray-500 text-xs mt-1">{{ __('Sub-categories will automatically inherit their parent\'s type.') }}</p>
                @error('type')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-800">
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all">
                    {{ __('Save Category') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentSelect = document.getElementById('parent_id');
        const typeSelect = document.getElementById('type');
        
        function updateTypeSelect() {
            if (parentSelect.value) {
                const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                const parentType = selectedOption.getAttribute('data-type');
                
                typeSelect.value = parentType;
                typeSelect.disabled = true; // Visual feedback, backend ignores it anyway
            } else {
                typeSelect.disabled = false;
            }
        }

        parentSelect.addEventListener('change', updateTypeSelect);
        
        // Run on load in case there's old data
        updateTypeSelect();
    });
</script>
@endsection
