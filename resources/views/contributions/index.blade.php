@extends('layouts.app')

@section('title', __('Contributions Tracking'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Contributions Tracking') }}</h1>
        <p class="text-gray-400 text-sm mt-1">{{ __('Manage monthly payments for each member. Changes are saved instantly.') }}</p>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-700 mb-6">
        <form action="{{ route('contributions.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="fund_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Select Fund') }}</label>
                <select name="fund_id" id="fund_id" class="w-full bg-gray-900 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-red-500">
                    @foreach($funds as $fund)
                        <option value="{{ $fund->fund_id }}" {{ $selectedFundId == $fund->fund_id ? 'selected' : '' }}>
                            {{ $fund->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-1">
                <label for="period" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Period (YYYY-MM)') }}</label>
                <input type="month" name="period" id="period" value="{{ $selectedPeriod }}" class="w-full bg-gray-900 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-red-500">
            </div>

            <div>
                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-6 rounded transition">
                    {{ __('Load Grid') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Main Grid -->
    <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-4">{{ __('Member Name') }}</th>
                    <th class="px-6 py-4">{{ __('Group') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Payment Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    @php
                        $status = 'pending';
                        if(isset($contributions[$member->member_id])) {
                            $status = $contributions[$member->member_id]->payment_status;
                        }
                    @endphp
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-medium text-white">{{ $member->member_name }}</td>
                        <td class="px-6 py-4">{{ $member->group->group_name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button type="button" 
                                    onclick="updateStatus({{ $member->member_id }}, 'paid', this)"
                                    class="status-btn px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-600 transition-colors {{ $status === 'paid' ? 'bg-green-600 text-white border-green-600 z-10' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                                    {{ __('Paid') }}
                                </button>
                                <button type="button" 
                                    onclick="updateStatus({{ $member->member_id }}, 'pending', this)"
                                    class="status-btn px-4 py-2 text-sm font-medium border-t border-b border-gray-600 transition-colors {{ $status === 'pending' ? 'bg-yellow-600 text-white border-yellow-600 z-10' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                                    {{ __('Pending') }}
                                </button>
                                <button type="button" 
                                    onclick="updateStatus({{ $member->member_id }}, 'exempted', this)"
                                    class="status-btn px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-600 transition-colors {{ $status === 'exempted' ? 'bg-gray-600 text-white border-gray-500 z-10' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                                    {{ __('Exempted') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">
                            {{ __('No members found. Please add members first.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 bg-gray-900 border border-gray-700 text-white px-6 py-3 rounded-lg shadow-xl transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none z-50">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span id="toast-message">Status updated successfully</span>
        </div>
    </div>

    <script>
        const fundId = '{{ $selectedFundId }}';
        const period = '{{ $selectedPeriod }}';
        const csrfToken = '{{ csrf_token() }}';

        function showToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        async function updateStatus(memberId, status, btnElement) {
            // Optimistic UI Update
            const group = btnElement.closest('.inline-flex');
            const buttons = group.querySelectorAll('.status-btn');
            
            // Reset all buttons in this row
            buttons.forEach(btn => {
                btn.className = 'status-btn px-4 py-2 text-sm font-medium border border-gray-600 transition-colors bg-gray-800 text-gray-400 hover:bg-gray-700';
                if(btn.nextElementSibling) btn.classList.remove('border-r'); // Handle borders for middle elements if needed
            });
            
            // Restore proper rounded corners
            buttons[0].classList.add('rounded-l-lg');
            buttons[2].classList.add('rounded-r-lg');
            buttons[1].classList.remove('border-l', 'border-r');
            buttons[1].classList.add('border-t', 'border-b'); // keep top/bottom borders for middle

            // Highlight the clicked one
            if (status === 'paid') {
                btnElement.className = 'status-btn px-4 py-2 text-sm font-medium rounded-l-lg transition-colors bg-green-600 text-white border-green-600 z-10';
            } else if (status === 'pending') {
                btnElement.className = 'status-btn px-4 py-2 text-sm font-medium border-t border-b transition-colors bg-yellow-600 text-white border-yellow-600 z-10';
            } else {
                btnElement.className = 'status-btn px-4 py-2 text-sm font-medium rounded-r-lg transition-colors bg-gray-600 text-white border-gray-500 z-10';
            }

            try {
                const response = await fetch('{{ route('contributions.updateStatus') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        member_id: memberId,
                        fund_id: fundId,
                        period: period,
                        status: status
                    })
                });

                const result = await response.json();
                if (result.success) {
                    showToast();
                } else {
                    console.error('Server returned error', result);
                    alert('{{ __('Failed to update status. Please try again.') }}');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('{{ __('Network error. Could not update status.') }}');
            }
        }
    </script>
@endsection
