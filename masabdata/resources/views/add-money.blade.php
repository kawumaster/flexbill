<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Money</h2>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow">
        <form method="POST" action="{{ route('wallet.add') }}">
            @csrf
            <label class="block mb-2 font-semibold">Amount (₦)</label>
            <input type="number" name="amount" class="border p-2 rounded w-full mb-3" min="100" required>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add to wallet
            </button>
        </form>
    </div>
</x-app-layout>
