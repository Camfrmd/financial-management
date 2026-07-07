@extends('layouts.app')

@section('title', __('Edit Member'))

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">{{ __('Edit Member') }}</h1>
            <a href="{{ route('members.index') }}" class="text-gray-400 hover:text-white">{{ __('Back to List') }}</a>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-700">
            <form action="{{ route('members.update', $member->member_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="member_name" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Member Name') }}</label>
                    <input type="text" name="member_name" id="member_name" value="{{ old('member_name', $member->member_name) }}" required
                        class="w-full bg-gray-900 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-red-500">
                    @error('member_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="group_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Community Group') }}</label>
                    <select name="group_id" id="group_id" required
                        class="w-full bg-gray-900 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-red-500">
                        <option value="">-- {{ __('Select a Group') }} --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->group_id }}" {{ old('group_id', $member->group_id) == $group->group_id ? 'selected' : '' }}>
                                {{ $group->group_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Status') }}</label>
                    <select name="status" id="status" required
                        class="w-full bg-gray-900 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-red-500">
                        <option value="active" {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="exempted" {{ old('status', $member->status) === 'exempted' ? 'selected' : '' }}>{{ __('Exempted') }}</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('members.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded transition">
                        {{ __('Update Member') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
