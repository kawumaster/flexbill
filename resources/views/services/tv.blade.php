<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h2 class="text-xl font-semibold mb-4">TV Subscription</h2>

        <div class="bg-white p-4 rounded-xl shadow">
            <form>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600">Provider</label>
                    <select class="w-full border-gray-300 rounded-lg">
                        <option>DSTV</option>
                        <option>GOTV</option>
                        <option>Startimes</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600">Smart Card Number</label>
                    <input type="text" class="w-full border-gray-300 rounded-lg" placeholder="Enter Card Number">
                </div>

                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-600 w-full">Renew</button>
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
