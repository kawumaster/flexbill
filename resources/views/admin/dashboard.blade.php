
<x-admin-layout>
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">Users:  ₦{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</div>
        <div class="bg-white p-4 rounded shadow">Transactions</div>
        <div class="bg-white p-4 rounded shadow">Notifications</div>
        <div class="bg-white p-4 rounded shadow">Profit</div>
       
    </div>
</x-admin-layout>
