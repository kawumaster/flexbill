<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800">
            Profile
        </h2>
    </x-slot>

<div class="min-h-screen bg-gray-100 pb-24">

    {{-- USER CARD --}}
    <div class="bg-white p-4 shadow-sm">
        <div class="flex items-center gap-3">
            
            <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center text-lg font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div>
                <div class="font-semibold text-sm">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ auth()->user()->email }}
                </div>
            </div>
        </div>
    </div>

    {{-- KYC STATUS --}}
    <div class="p-4">
        <div class="bg-white p-4 rounded-xl shadow-sm">

            <div class="flex justify-between items-center mb-3">
                <div class="font-semibold text-sm">Verification Status</div>

                @if(auth()->user()->kyc_status == 'verified')
                    <span class="text-green-600 text-xs">Verified ✔</span>
                @else
                    <span class="text-yellow-500 text-xs">Not Verified</span>
                @endif
            </div>

            <div class="text-xs text-gray-500 mb-3">
                Verify your account to enjoy more benefits.
            </div>

            @if(auth()->user()->kyc_status != 'verified')
                <a href="{{ route('kyc.form') }}"
                   class="block text-center bg-green-600 text-white py-2 rounded-lg text-sm">
                    Verify Now
                </a>
            @endif

        </div>
    </div>

    {{-- ACCOUNT OPTIONS --}}
    <div class="px-4 space-y-3">

        <div class="bg-white rounded-xl shadow-sm divide-y">

            <a href="#" class="flex justify-between p-3 text-sm">
                <span>Personal Information</span>
                <span>›</span>
            </a>

            <a href="#" class="flex justify-between p-3 text-sm">
                <span>Security Settings</span>
                <span>›</span>
            </a>

            <a href="{{ route('transactions.index') }}" class="flex justify-between p-3 text-sm">
                <span>Transaction History</span>
                <span>›</span>
            </a>

        </div>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-sm font-medium">
                Logout
            </button>
        </form>

    </div>

</div>

{{-- BOTTOM NAV --}}
<nav class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around py-2">

    <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-500">
        <span>🏠</span>
        <span class="text-[10px]">Home</span>
    </a>

    <a href="{{ route('transactions.index') }}" class="flex flex-col items-center text-gray-500">
        <span>📄</span>
        <span class="text-[10px]">History</span>
    </a>

    <a href="{{ route('airtime') }}" class="flex flex-col items-center text-gray-500">
        <span>📱</span>
        <span class="text-[10px]">Airtime</span>
    </a>

    <a href="{{ route('data.index') }}" class="flex flex-col items-center text-gray-500">
        <span>🌐</span>
        <span class="text-[10px]">Data</span>
    </a>

    <a href="{{ route('profile') }}" class="flex flex-col items-center text-green-600">
        <span>👤</span>
        <span class="text-[10px]">Profile</span>
    </a>

</nav>

</x-app-layout>