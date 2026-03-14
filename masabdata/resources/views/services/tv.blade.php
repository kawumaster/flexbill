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

                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 w-full">Renew</button>
            </form>
        </div>
    </div>
</x-app-layout>
