@extends('layouts.app')

@section('title', __('Audit Trail'))

@section('content')
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">{{ __('Audit Trail & Activity Log') }}</h2>
                <p class="text-gray-500 mt-1 text-sm">{{ __('Read-only system record of all critical activities.') }}</p>
            </div>
            <span class="text-xs text-blue-400 border border-blue-500/30 bg-blue-500/10 px-3 py-1.5 rounded-full font-semibold flex items-center shadow-inner">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.95 11.95 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                {{ __('Secure Log') }}
            </span>
        </div>

        @if($activities->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 text-lg">{{ __('No activities recorded yet.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-md">{{ __('Date & Time') }}</th>
                            <th class="px-4 py-3">{{ __('Causer (User)') }}</th>
                            <th class="px-4 py-3">{{ __('Event & Description') }}</th>
                            <th class="px-4 py-3 rounded-tr-md">{{ __('Changes / Properties') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($activities as $activity)
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-400">
                                    {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($activity->causer)
                                        <div class="font-medium text-gray-200">{{ $activity->causer->username ?? $activity->causer->email }}</div>
                                        <div class="text-xs text-gray-500">{{ __('Role:') }} {{ ucfirst($activity->causer->role ?? __('N/A')) }}</div>
                                    @else
                                        <span class="text-gray-500 italic">{{ __('System') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-white">{{ $activity->description }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ __('Subject:') }} {{ $activity->subject_type ? class_basename($activity->subject_type) . ' #' . $activity->subject_id : __('N/A') }}
                                    </div>
                                    <div class="text-xs text-blue-400 font-mono mt-0.5">{{ $activity->log_name }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $properties = $activity->properties;
                                    @endphp
                                    @if($properties && $properties->count() > 0)
                                        <div class="bg-gray-900 rounded p-2 text-xs font-mono text-gray-400 overflow-x-auto max-w-xs">
                                            @if(isset($properties['old']) && isset($properties['attributes']))
                                                <div class="mb-1 text-red-400">{{ __('Old:') }} {{ json_encode($properties['old']) }}</div>
                                                <div class="text-green-400">{{ __('New:') }} {{ json_encode($properties['attributes']) }}</div>
                                            @else
                                                {{ json_encode($properties->toArray()) }}
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-600 italic text-xs">{{ __('No additional data') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
@endsection
