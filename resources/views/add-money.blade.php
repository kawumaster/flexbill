<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Money</h2>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow">

          @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/fund-wallet">
    @csrf

    <input type="number" name="amount" placeholder="Enter amount">

   <button class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
        Fund Wallett
    </button>
</form>
    </div>
</x-app-layout>
