<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fund Walletz</h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-6 bg-white p-6 rounded shadow">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('wallet.add') }}">
    @csrf

    <div class="mb-4">
        <label class="block text-sm font-medium">Amount (₦)</label>
        <input type="number" name="amount" class="w-full border-gray-300 rounded-lg" required>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
        Add Money
    </button>
</form>

    </div>
</x-app-layout>



