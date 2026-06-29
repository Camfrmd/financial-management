@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Daftar Pengguna (Users)</h2>
        <a href="{{ route('users.create') }}" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow-lg transition duration-200">
            + Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-800/50 border border-green-600 text-green-200 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                <tr>
                    <th class="px-4 py-3 rounded-tl-md">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Peran (Role)</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right rounded-tr-md">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/20 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-200">{{ $user->username }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @if($user->role === 'kelian')
                                <span class="px-2 py-1 bg-yellow-600/20 text-yellow-500 border border-yellow-600/50 rounded text-xs font-semibold">Kelian</span>
                            @elseif($user->role === 'treasurer')
                                <span class="px-2 py-1 bg-blue-600/20 text-blue-400 border border-blue-600/50 rounded text-xs font-semibold">Bendahara</span>
                            @else
                                <span class="px-2 py-1 bg-gray-600/20 text-gray-400 border border-gray-600/50 rounded text-xs font-semibold">Anggota</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($user->is_active)
                                <span class="text-green-500 font-semibold text-xs border border-green-500/30 bg-green-500/10 px-2 py-1 rounded">Aktif</span>
                            @else
                                <span class="text-red-500 font-semibold text-xs border border-red-500/30 bg-red-500/10 px-2 py-1 rounded">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('users.edit', $user->user_id) }}" class="text-yellow-500 hover:text-yellow-400 font-medium">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
