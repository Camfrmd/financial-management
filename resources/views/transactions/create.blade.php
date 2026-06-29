<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan Banjar - Tambah Transaksi</title>
    <!-- Using Tailwind CDN directly since Vite is not built -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">

    <!-- En-tête -->
    <nav class="bg-gray-800 border-b-2 border-red-700 px-6 py-4 flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-red-700 text-white font-bold p-2 rounded">SKB</div>
            <h1 class="text-xl font-semibold hidden sm:block">Sistem Keuangan Banjar</h1>
        </div>
        
        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors text-sm font-medium">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </nav>

    <main class="container mx-auto px-4 max-w-2xl">
        
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-4">{{ __('Add New Transaction') }}</h2>

            <form action="{{ url('/transactions') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Date & Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Date') }}</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                               class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Transaction Type') }}</label>
                        <select name="type" id="type" 
                                class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>{{ __('Income') }}</option>
                            <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>{{ __('Expense') }}</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Amount (Rp)') }}</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="1" step="1"
                           class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                           placeholder="{{ __('Example: 150000') }}">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Fund -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Category (Detail Account)') }}</label>
                        <select name="category_id" id="category_id" 
                                class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fund_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Fund') }}</label>
                        <select name="fund_id" id="fund_id" 
                                class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            <option value="">{{ __('Select Fund') }}</option>
                            @foreach($funds as $fund)
                                <option value="{{ $fund->fund_id }}" {{ old('fund_id') == $fund->fund_id ? 'selected' : '' }}>
                                    {{ $fund->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('fund_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Description') }}</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                              placeholder="{{ __('Transaction details...') }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Proof File -->
                <div>
                    <label for="proof_file" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Transaction Proof (Optional, Max 2MB, JPG/PNG/PDF)') }}</label>
                    <input type="file" name="proof_file" id="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full bg-gray-900 border border-gray-600 rounded-md py-2 px-3 text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                    @error('proof_file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-700 flex justify-end">
                    <button type="submit" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-md transition-colors shadow-lg">
                        {{ __('Save Transaction (Pending)') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
