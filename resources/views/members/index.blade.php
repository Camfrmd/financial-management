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
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('members.edit', $member->member_id) }}" class="text-yellow-500 hover:text-yellow-400 font-medium mr-3">{{ __('Edit') }}</a>
                            <form action="{{ route('members.destroy', $member->member_id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this member?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 font-medium">{{ __('Delete') }}</button>
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
