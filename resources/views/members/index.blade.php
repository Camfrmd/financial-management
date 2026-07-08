@extends('layouts.app')

@section('title', __('Members'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Members Management') }}</h1>
        <a href="{{ route('members.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
            + {{ __('Add Member') }}
        </a>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-4">{{ __('Name') }}</th>
                    <th class="px-6 py-4">{{ __('Group') }}</th>
                    <th class="px-6 py-4">{{ __('Status') }}</th>
                    <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-medium text-white">{{ $member->member_name }}</td>
                        <td class="px-6 py-4">{{ $member->group->group_name ?? __('N/A') }}</td>
                        <td class="px-6 py-4">
                            @if($member->status === 'active')
                                <span class="bg-green-900/50 text-green-400 text-xs px-2 py-1 rounded border border-green-800">{{ __('Active') }}</span>
                            @else
                                <span class="bg-gray-700 text-gray-400 text-xs px-2 py-1 rounded border border-gray-600">{{ __('Exempted') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end space-x-2">
                            <a href="{{ route('members.edit', $member->member_id) }}" class="p-1 text-gray-400 hover:text-yellow-400 transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('members.destroy', $member->member_id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this member?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">
                            {{ __('No members found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
