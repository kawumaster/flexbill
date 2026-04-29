<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Money</h2>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow">
        <form method="POST" action="/fund-wallet">
    @csrf

    <input type="number" name="amount" placeholder="Enter amount">

    <button type="submit">Fund Wallet</button>
</form>
    </div>
</x-app-layout>
