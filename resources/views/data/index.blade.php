<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Buy Data
        </h2>
    </x-slot>

@php
    $wallet = auth()->user()->wallet;
@endphp

<div class="max-w-3xl mx-auto mt-6 bg-white p-6 shadow rounded-lg">

    {{-- WALLET --}}
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-center font-semibold">
        Wallet Balance: ₦{{ number_format($wallet->balance ?? 0, 2) }}
    </div>

    {{-- ALERTS --}}
    @if(session('error'))
        <div class="mb-3 text-red-600 text-center font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-3 text-green-600 text-center font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($plans))
        <div class="text-red-600 text-center">
            No data plans available.
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('data.buy') }}">
        @csrf

        {{-- PHONE --}}
        <input type="text"
               name="phone"
               class="w-full border p-2 rounded mb-4"
               placeholder="Enter phone number"
               required>

        {{-- NETWORK --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Select Network</label>

            <div class="grid grid-cols-4 gap-2">
                @foreach(['mtn','airtel','glo','9mobile'] as $net)
                    <label class="border rounded p-2 text-center cursor-pointer">
                        <input type="radio" name="network" value="{{ $net }}" required>
                        <div class="text-sm uppercase mt-1">{{ $net }}</div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- PLANS --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Select Data Plan</label>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                @foreach($plans as $plan)
                    <label class="border p-3 rounded cursor-pointer hover:bg-blue-50">

                        <input type="radio"
                               name="variation_code"
                               value="{{ $plan['variation_code'] }}"
                               data-amount="{{ $plan['variation_amount'] }}"
                               required>

                        <div class="font-semibold text-sm">
                            {{ $plan['name'] }}
                        </div>

                        <div class="text-blue-600 font-bold mt-1">
                            ₦{{ number_format($plan['variation_amount']) }}
                        </div>

                    </label>
                @endforeach

            </div>
        </div>

        {{-- HIDDEN AMOUNT (IMPORTANT) --}}
        <input type="hidden" name="amount" id="amount">

        {{-- BUTTON --}}
        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Buy Data
        </button>

    </form>
</div>

{{-- JS TO SET AMOUNT --}}
<script>
    document.querySelectorAll('input[name="variation_code"]').forEach(el => {
        el.addEventListener('change', function () {
            document.getElementById('amount').value = this.dataset.amount;
        });
    });
</script>

</x-app-layout>