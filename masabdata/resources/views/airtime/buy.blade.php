<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Buy Airtime
        </h2>
    </x-slot>

<!-- alert -->
<div class="max-w-xl mx-auto mt-6 bg-green p-6 shadow rounded-lg">

        {{-- Alerts --}}
        @if(session('error'))
            <div class="mb-4 text-red-600 font-semibold text-center">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 text-green-600 bg-green font-semibold text-center">
                {{ session('success') }}
            </div>
        @endif
        <!-- end alert -->

    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('airtime.buy') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Phone Number</label>
                <input type="text" name="phone"
                       class="w-full border rounded p-2" 
                       placeholder="080xxxxxxxx" required>

            </div>

            <div class="mb-4">
                <label class="block mb-1">Network</label>
                <select name="network" class="w-full border rounded p-2" required>
                    <option value="MTN">MTN</option>
                    <option value="GLO">GLO</option>
                    <option value="AIRTEL">AIRTEL</option>
                    <option value="9MOBILE">9MOBILE</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Amount (₦)</label>
                <input type="number" name="amount"
                       min="50"
                       class="w-full border rounded p-2" required>
            </div>

            <button class="w-full bg-green-600 text-white py-2 rounded">
                Buy Airtime
            </button>
        </form>
    </div>
</x-app-layout>