@extends('layouts.app')

@section('title', __('User Management'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">{{ __('Users List') }}</h2>
        <a href="{{ route('users.create') }}" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow-lg transition duration-200">
            + {{ __('Add User') }}
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
                    <th class="px-4 py-3 rounded-tl-md">{{ __('Username') }}</th>
                    <th class="px-4 py-3">{{ __('Email') }}</th>
                    <th class="px-4 py-3">{{ __('Role') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-right rounded-tr-md">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/20 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-200">{{ $user->username }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @if($user->role === 'kelian')
                                <span class="px-2 py-1 bg-yellow-600/20 text-yellow-500 border border-yellow-600/50 rounded text-xs font-semibold">{{ __('Kelian') }}</span>
                            @elseif($user->role === 'treasurer')
                                <span class="px-2 py-1 bg-blue-600/20 text-blue-400 border border-blue-600/50 rounded text-xs font-semibold">{{ __('Treasurer') }}</span>
                            @else
                                <span class="px-2 py-1 bg-gray-600/20 text-gray-400 border border-gray-600/50 rounded text-xs font-semibold">{{ __('Member') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($user->is_active)
                                <span class="text-green-500 font-semibold text-xs border border-green-500/30 bg-green-500/10 px-2 py-1 rounded">{{ __('Active') }}</span>
                            @else
                                <span class="text-red-500 font-semibold text-xs border border-red-500/30 bg-red-500/10 px-2 py-1 rounded">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right flex justify-end">
                            <a href="{{ route('users.edit', $user->user_id) }}" class="p-1 text-gray-400 hover:text-yellow-400 transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
