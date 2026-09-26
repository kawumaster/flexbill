<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h2 class="text-xl font-semibold mb-4">Electricity Payment</h2>

        <div class="bg-white p-4 rounded-xl shadow">
            <form method="POST" action="/pay">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600">Disco</label>
                    <select class="w-full border-gray-300 rounded-lg">
                        <option>IKEDC</option>
                        <option>EKEDC</option>
                        <option>KEDCO</option>
                        <option>BEDC</option>
                        <option>jEDC</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600">Meter Number</label>
                    <input type="text" class="w-full border-gray-300 rounded-lg" placeholder="Enter Meter Number">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600">Amount (₦)</label>
                    <input type="number" class="w-full border-gray-300 rounded-lg" placeholder="e.g. 1000">
                </div>

                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-600 w-full">Pay Now</button>
            </form>
        </div>
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
