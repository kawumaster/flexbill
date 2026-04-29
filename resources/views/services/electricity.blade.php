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

                <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 w-full">Pay Now</button>
            </form>
        </div>
    </div>
</x-app-layout>
