@extends('layouts.app')

@section('title', __('Create Fund'))
@section('back_url', route('funds.index'))
@section('back_text', __('Back to Funds'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white mb-2">{{ __('Create New Fund') }}</h1>
        <p class="text-gray-400">{{ __('Create a new community fund. The initial balance will always be set to Rp 0.') }}</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-900/30 border border-red-800/50 rounded-lg text-red-400 flex items-start shadow-lg">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden">
        <div class="p-6 sm:p-8">
            <form action="{{ route('funds.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Fund Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Fund Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors"
                               placeholder="{{ __('e.g., Kas Umum, Kas Pembangunan') }}">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors placeholder-gray-600"
                                  placeholder="{{ __('Describe the purpose of this fund (optional)') }}">{{ old('description') }}</textarea>
                    </div>

                    <!-- Group (Optional) -->
                    <div>
                        <label for="group_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Associated Group (Optional)') }}</label>
                        <select name="group_id" id="group_id"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors appearance-none">
                            <option value="">-- {{ __('No Group / General Fund') }} --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->group_id }}" {{ old('group_id') == $group->group_id ? 'selected' : '' }}>
                                    {{ $group->group_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Activity (Optional) -->
                    <div>
                        <label for="activity_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Associated Activity (Optional)') }}</label>
                        <select name="activity_id" id="activity_id"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors appearance-none">
                            <option value="">-- {{ __('No Specific Activity') }} --</option>
                            @foreach($activities as $activity)
                                <option value="{{ $activity->activity_id }}" {{ old('activity_id') == $activity->activity_id ? 'selected' : '' }}>
                                    {{ $activity->activity_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-800 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-red-500">
                        {{ __('Save Fund') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
