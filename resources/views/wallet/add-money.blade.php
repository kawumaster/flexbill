<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fund your Wallet</h2>
    </x-slot>

    <div class=" mb-3 max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        
             <form method="POST" action="/fund-wallet">
    @csrf

<div class="mb-4">
        <label class="block text-sm font-medium">Amount (₦)</label>
        <input type="number" name="amount" class="w-full border-gray-300 rounded-lg" required>
    </div>
    

     <button class="bg-purple-600 text-white px-4 py-2 rounded-lg w-full">
        Fund Wallet
    </button>
</form>

    </div>

    {{-- BOTTOM NAVIGATION --}}
<nav class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around py-2">

    <a href="{{ route('dashboard') }}"
       class="flex flex-col items-center text-green-600">
        <span class="text-xl">🏠</span>
        <span class="text-[10px]">Home</span>
    </a>

    <a href="{{ route('transactions.index') }}"
       class="flex flex-col items-center text-gray-500 hover:text-green-600">
        <span class="text-xl">📄</span>
        <span class="text-[10px]">History</span>
    </a>

    <a href="{{ route('airtime') }}"
       class="flex flex-col items-center text-gray-500 hover:text-green-600">
        <span class="text-xl">📱</span>
        <span class="text-[10px]">Airtime</span>
    </a>

    <a href="{{ route('data.index') }}"
       class="flex flex-col items-center text-gray-500 hover:text-green-600">
        <span class="text-xl">🌐</span>
        <span class="text-[10px]">Data</span>
    </a>

    <a href="{{ route('profile') }}"
       class="flex flex-col items-center text-gray-500 hover:text-green-600">
        <span class="text-xl">👤</span>
        <span class="text-[10px]">Profile</span>
    </a>

</nav>
</x-app-layout>



