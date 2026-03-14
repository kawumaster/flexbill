<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h2 class="text-xl font-semibold mb-4">Buy Data</h2>

        <div class="bg-white p-4 rounded-xl shadow">
            <form>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600">Network</label>
                    <select class="w-full border-gray-300 rounded-lg">
                        <option>MTN</option>
                        <option>Airtel</option>
                        <option>Glo</option>
                        <option>9mobile</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600">Data Plan</label>
                    <select class="w-full border-gray-300 rounded-lg">
                        <option>1GB - ₦300</option>
                        <option>2GB - ₦500</option>
                        <option>5GB - ₦1000</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600">Phone Number</label>
                    <input type="text" class="w-full border-gray-300 rounded-lg" placeholder="e.g. 08162826390">
                </div>

                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 w-full">Buy Data</button>
            </form>
        </div>
    </div>
</x-app-layout>
