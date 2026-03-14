<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buy Data
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6 bg-white p-6 shadow rounded-lg">

        {{-- Alerts --}}
        @if(session('error'))
            <div class="mb-4 text-red-600 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 text-green-600 font-semibold text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('data.buy') }}">
            @csrf

            <!-- Phone Number -->
            <div class="mb-4">
                <label class="block font-medium mb-1">Phone Number</label>
                <input type="text" name="phone"
                    class="w-full border rounded px-3 py-2"
                    placeholder="080xxxxxxxx"
                    required>
            </div>

            <!-- Data Plan -->
            <div class="mb-4">
                <label class="block font-medium mb-1">Select Plan</label>
                <select name="plan_id"
                    class="w-full border rounded px-3 py-2"
                    required>
                    <option value="">-- Choose Plan --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">
                            {{ $plan->network }} - {{ $plan->plan }} (₦{{ number_format($plan->price) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Buy Data
            </button>
        </form>

    </div>
</x-app-layout>
