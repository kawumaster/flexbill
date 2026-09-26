<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800">
            Transaction Receipt
        </h2>
    </x-slot>

<div class="max-w-md mx-auto mt-6 bg-white p-5 rounded-xl shadow space-y-4">

    {{-- STATUS --}}
    <div class="text-center">
        <div class="text-lg font-semibold
            @if($tx->status == 'success') text-green-600
            @elseif($tx->status == 'failed') text-red-600
            @else text-yellow-500 @endif">
            {{ strtoupper($tx->status) }}
        </div>

        <div class="text-sm text-gray-500">
            {{ $tx->created_at->format('d M Y, h:i A') }}
        </div>
    </div>

    {{-- AMOUNT --}}
    <div class="text-center">
        <div class="text-2xl font-bold">
            ₦{{ number_format($tx->amount) }}
                    </div>
    </div>

    <hr>

    {{-- DETAILS --}}
    <div class="text-sm space-y-2">

        <div class="flex justify-between">
            <span class="text-gray-500">Type</span>
            <span class="capitalize">{{ $tx->type }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500">Reference</span>
            <span class="text-xs">{{ $tx->reference }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500">Status</span>
            <span>{{ ucfirst($tx->status) }}</span>
        </div>

    </div>

    <hr>

    {{-- ACTION --}}
    <!-- <a href="{{ route('transactions.index') 
}}" -->
<div class="block justify-between gap-3"><a href="{{ route('dashboard') 
}}"

       class="block text-center text-gray-100 bg-gray-600 py-2 rounded-lg text-sm">
        Download
    </a>
    <br>
<a href="{{ route('dashboard') 
}}"

       class="block text-center text-gray-100 bg-gray-800 py-2 rounded-lg text-sm">
        Back to Homepage
    </a></div>

</div>
</x-app-layout>