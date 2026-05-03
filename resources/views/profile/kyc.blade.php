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
</x-app-layout>