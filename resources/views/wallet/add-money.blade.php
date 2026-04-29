<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fund your Wallet</h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-6 bg-white p-6 rounded shadow">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        
             <form method="POST" action="/fund-wallet">
    @csrf

    <input type="number" name="amount" placeholder="Enter amount">

    <button type="submit">Fund Wallet</button>
</form>

    </div>
</x-app-layout>



