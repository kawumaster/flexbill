<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800">
            Transactions
        </h2>
    </x-slot>

<div class="max-w-3xl mx-auto mt-6 space-y-3">

    @forelse($transactions as $tx)

        <a href="{{ route('transactions.show', $tx->reference) }}"
           class="block bg-white p-4 rounded-xl shadow-sm hover:shadow transition">

            <div class="flex justify-between items-center">

                <div>
                    <div class="font-semibold text-sm capitalize">
                        @if($tx->type == 'wallet_funding_dva')
    DVA Funding
@elseif($tx->type == 'wallet_funding_card')
    Card Funding
@else
    {{ ucfirst(str_replace('_', ' ', $tx->type)) }}
@endif
                        <!-- {{ $tx->type }} -->
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $tx->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>

                <div class="text-right">
                    <div class="font-semibold text-sm">
                        ₦{{ number_format($tx->amount) }}
                    </div>

                    <div class="text-xs 
                        @if($tx->status == 'success') text-green-600 
                        @elseif($tx->status == 'failed') text-red-600 
                        @else text-yellow-500 @endif">
                        {{ ucfirst($tx->status) }}
                    </div>
                </div>

            </div>
        </a>

    @empty
        <div class="text-center text-gray-400 text-sm">
            No transactions yet
        </div>
    @endforelse

    <div>
        {{ $transactions->links() }}
    </div>

</div>
</x-app-layout>