<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800">
            Dashboard
        </h2>
    </x-slot>

<div class="min-h-screen bg-gray-100 pb-24">

    {{-- HEADER --}}
    <div class="px-4 pt-4">
        <div class="text-xs text-gray-500">Welcome back</div>
        <div class="text-lg font-semibold">{{ Auth::user()->name ?? 'User' }}</div>
    </div>

    {{-- WALLET CARD --}}
    <div class="px-4 mt-4">
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 text-white p-5 rounded-2xl shadow-lg">

            <div class="flex justify-between items-center">
                <div>
                    <div class="text-xs opacity-80">Available Balance</div>
                    <div class="text-2xl font-bold mt-1">
                        ₦{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}
                    </div>
                </div>

                <a href="{{ route('add-money') }}"
                   class="bg-white text-green-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-100">
                    + Add Money
                </a>
            </div>

            <div class="flex justify-between mt-4 text-xs opacity-90">
                <span>Referral Bonus: ₦100.00</span>
                <a href="{{ route('transactions.index') }}" class="underline">
                    History
                </a>
            </div>

        </div>
    </div>

    {{-- QUICK SERVICES --}}
    <div class="px-4 mt-6">
        <h3 class="text-sm font-semibold mb-3 text-gray-700">Quick Services</h3>

        <div class="grid grid-cols-4 gap-3">

            <a href="{{ route('airtime') }}"
               class="bg-white p-3 rounded-xl shadow-sm text-center hover:shadow transition">
                <div class="text-2xl">📱</div>
                <div class="text-xs mt-1">Airtime</div>
            </a>

            <a href="{{ route('data.index') }}"
               class="bg-white p-3 rounded-xl shadow-sm text-center hover:shadow transition">
                <div class="text-2xl">🌐</div>
                <div class="text-xs mt-1">Data</div>
            </a>

            <a href="{{ route('tv') }}"
               class="bg-white p-3 rounded-xl shadow-sm text-center hover:shadow transition">
                <div class="text-2xl">📺</div>
                <div class="text-xs mt-1">TV</div>
            </a>

            <a href="{{ route('electricity') }}"
               class="bg-white p-3 rounded-xl shadow-sm text-center hover:shadow transition">
                <div class="text-2xl">💡</div>
                <div class="text-xs mt-1">Power</div>
            </a>

        </div>
    </div>

    {{-- RECENT TRANSACTIONS --}}
    <div class="px-4 mt-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Recent Transactions</h3>
            <a href="{{ route('transactions.index') }}" class="text-xs text-blue-600">
                View all
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm divide-y">

            @forelse(\App\Models\Transaction::where('user_id', auth()->id())->latest()->take(5)->get() as $tx)

                <a href="{{ route('transactions.show', $tx->reference) }}"
                   class="flex justify-between items-center p-3 hover:bg-gray-50">

                    <div>
                        <div class="text-sm font-medium capitalize">
                            {{ $tx->type }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $tx->created_at->format('d M, h:i A') }}
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-sm font-semibold">
                            ₦{{ number_format($tx->amount) }}
                        </div>

                        <div class="text-xs
                            @if($tx->status == 'success') text-green-600
                            @elseif($tx->status == 'failed') text-red-600
                            @else text-yellow-500 @endif">
                            {{ ucfirst($tx->status) }}
                        </div>
                    </div>

                </a>

            @empty
                <div class="p-4 text-center text-gray-400 text-sm">
                    No transactions yet
                </div>
            @endforelse

        </div>
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