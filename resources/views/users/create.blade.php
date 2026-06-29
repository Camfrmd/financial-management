<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan Banjar - Tambah Pengguna</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <nav class="bg-gray-800 border-b-2 border-red-700 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="bg-red-700 text-white font-bold p-2 rounded"><a href="{{ route('dashboard') }}">SKB</a></div>
            <h1 class="text-xl font-semibold hidden sm:block">Tambah Pengguna Baru</h1>
        </div>
        <div class="flex items-center space-x-6">
            <a href="{{ route('users.index') }}" class="text-gray-300 hover:text-white mr-4 text-sm">{{ __('Kembali ke Daftar') }}</a>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-12 max-w-2xl flex-grow">
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gray-900/50 p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold text-white">Formulir Pengguna</h2>
                <p class="text-sm text-gray-400 mt-1">Isi data di bawah ini untuk membuat akun baru.</p>
            </div>
            
            <form action="{{ route('users.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-1">Nama Pengguna (Username)</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-1">Peran (Role)</label>
                        <select name="role" id="role" required class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Anggota Biasa (Member)</option>
                            <option value="treasurer" {{ old('role') == 'treasurer' ? 'selected' : '' }}>Bendahara (Treasurer)</option>
                            <option value="kelian" {{ old('role') == 'kelian' ? 'selected' : '' }}>Kelian (Supervisor)</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Kata Sandi</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700 flex justify-end space-x-4">
                    <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-600 rounded text-gray-300 hover:bg-gray-700 transition">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-red-700 hover:bg-red-600 text-white font-bold rounded shadow-lg transition">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
