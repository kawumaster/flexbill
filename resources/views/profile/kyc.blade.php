<x-app-layout>
<div class="max-w-md mx-auto mt-6 bg-white p-5 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-4">KYC Verification</h2>

    <form method="POST" action="#">
        @csrf

        <input type="text" placeholder="Full Name" class="w-full border p-2 mb-3 rounded">

        <input type="text" placeholder="NIN / BVN" class="w-full border p-2 mb-3 rounded">

        <button class="w-full bg-green-600 text-white py-2 rounded">
            Submit
        </button>
    </form>

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