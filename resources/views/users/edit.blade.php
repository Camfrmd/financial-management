@extends('layouts.app')

@section('title', __('Edit User'))
@section('back_button', true)
@section('back_url', route('users.index'))
@section('back_text', __('Back to Users List'))

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gray-900/50 p-6 border-b border-gray-700 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ __('Edit User') }} {{ $user->username }}</h2>
                    <p class="text-sm text-gray-400 mt-1">{{ __('Update user data, role, or active status.') }}</p>
                </div>
                @if($user->is_active)
                    <span class="text-green-500 font-bold border border-green-500 px-3 py-1 rounded">{{ __('ACTIVE') }}</span>
                @else
                    <span class="text-red-500 font-bold border border-red-500 px-3 py-1 rounded">{{ __('INACTIVE') }}</span>
                @endif
            </div>
            
            <form action="{{ route('users.update', $user->user_id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Username') }}</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                            class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Role') }}</label>
                        <select name="role" id="role" required class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>{{ __('Member') }}</option>
                            <option value="treasurer" {{ old('role', $user->role) == 'treasurer' ? 'selected' : '' }}>{{ __('Treasurer') }}</option>
                            <option value="kelian" {{ old('role', $user->role) == 'kelian' ? 'selected' : '' }}>{{ __('Kelian') }}</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="pt-4 border-t border-gray-700">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 text-red-600 bg-gray-900 border-gray-600 rounded focus:ring-red-500 focus:ring-2">
                            <span class="text-gray-300 font-medium">{{ __('Active Account (Can Login)') }}</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 ml-8">{{ __('Uncheck to deactivate this account. The user will not be able to log in.') }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-400 mb-4 uppercase tracking-wider">{{ __('Change Password (Optional)') }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="password" placeholder="{{ __('Leave blank if not changing') }}"
                                    class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="{{ __('Repeat new password') }}"
                                    class="w-full bg-gray-900 border border-gray-600 rounded px-4 py-2 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700 flex justify-end space-x-4">
                    <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-600 rounded text-gray-300 hover:bg-gray-700 transition">{{ __('Cancel') }}</a>
                    <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded shadow-lg transition">{{ __('Update User') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
