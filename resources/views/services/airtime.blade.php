 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buy Airtime</h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-6 bg-white p-6 rounded shadow">
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <<form method="POST" action="/buy-airtime">
    @csrf

    <div class="mb-3">
        <label class="block text-sm font-medium">Network</label>
        <select name="network" class="w-full border-gray-300 rounded-lg" required>
            <option value="MTN">MTN</option>
            <option value="Airtel">Airtel</option>
            <option value="Glo">Glo</option>
            <option value="9mobile">9mobile</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium">Phone Number</label>
        <input type="text" name="phone" class="w-full border-gray-300 rounded-lg" required>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium">amount (₦)</label>
        <input type="number" name="amount" class="w-full border-gray-300 rounded-lg" required>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
        Buy Airtime
    </button>
</form>

    </div>
</x-app-layout>