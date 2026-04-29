<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions History
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-6 bg-white p-6 shadow rounded-lg">

        <!-- Filter -->
        <form method="GET" class="mb-4 flex gap-2">
            <select name="type" class="border rounded px-3 py-2">
                <option value="">All Types</option>
                <option value="funding">Funding</option>
                <option value="data">Data</option>
                <option value="airtime">Airtime</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Filter
            </button>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Date</th>
                        <th class="p-2 border">Type</th>
                        <th class="p-2 border">Reference</th>
                        <th class="p-2 border">Amount</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td class="p-2 border">{{ $tx->created_at->format('d M Y H:i') }}</td>
                            <td class="p-2 border capitalize">{{ $tx->type }}</td>
                            <td class="p-2 border">{{ $tx->reference }}</td>
                            <td class="p-2 border">₦{{ number_format($tx->amount) }}</td>
                            <td class="p-2 border">
                                <span class="px-2 py-1 rounded text-white
                                    {{ $tx->status === 'success' ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-4">
                                No transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
