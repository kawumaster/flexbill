<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Airtime
        </h2>
    </x-slot>

<div class="max-w-md mx-auto mt-6 bg-gray-50 p-4 rounded-2xl">

    {{-- ALERTS --}}
    @if(session('error'))
        <div class="mb-3 text-red-600 text-center text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-3 bg-green-50 text-green-700 p-2 rounded-lg text-center text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- CARD --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm">

        {{-- PHONE --}}
        <div class="flex items-center gap-3 mb-3">
            <div id="networkBadge" class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                📶
            </div>

            <input type="text" name="phone" id="phone"
                   class="flex-1 text-lg font-semibold border-0 focus:ring-0"
                   placeholder="Enter phone number">

        </div>

        <div id="networkPreview" class="text-xs text-gray-500 mb-3"></div>

        <hr class="mb-3">

        {{-- QUICK AMOUNTS --}}
        <div class="grid grid-cols-3 gap-1 mb-4">
            @foreach([50,100,200,500,1000,2000] as $amt)
                <button type="button"
                        onclick="setAmount({{ $amt }})"
                        class="bg-gray-100 p-3 rounded-xl text-center hover:bg-purple-50">
                    <div class="font-bold">₦{{ $amt }}</div>
                    <div class="text-xs text-purple-600">Buy ₦{{ $amt-2 }}</div>
                </button>
            @endforeach
        </div>

        {{-- FORM --}}
        <form method="POST" action="/buy-airtime" onsubmit="return confirmAirtime()">
            @csrf

            <input type="hidden" name="phone" id="hiddenPhone">

            <input type="number" name="amount" id="amount"
                   class="w-full border rounded-lg p-2 text-sm mb-4"
                   placeholder="₦ 100 - 5,000" required>

            <button class="w-full bg-purple-600 text-white py-2 rounded-full">
                Pays
            </button>
            
        </form>

    </div>
</div>

<script>
const phoneInput = document.getElementById('phone');
const hiddenPhone = document.getElementById('hiddenPhone');
const preview = document.getElementById('networkPreview');
const badge = document.getElementById('networkBadge');

const networkMap = {
    mtn: ['0803','0806','0813','0816','0810','0814','0903','0906','0703','0706','0704'],
    glo: ['0805','0807','0815','0811','0905','0705'],
    airtel: ['0802','0808','0812','0701','0708','0902','0907','0901'],
    '9mobile': ['0809','0817','0818','0909','0908']
};

phoneInput.addEventListener('input', function () {
    let phone = this.value;
    hiddenPhone.value = phone;

    let prefix = phone.substring(0,4);
    let detected = null;

    for (let net in networkMap) {
        if (networkMap[net].includes(prefix)) {
            detected = net;
        }
    }

    if (detected) {
        preview.innerText = "Detected: " + detected.toUpperCase();

        badge.innerText =
            detected === 'mtn' ? '🟡' :
            detected === 'glo' ? '🟢' :
            detected === 'airtel' ? '🔴' :
            '⚫';
    } else {
        preview.innerText = "";
        badge.innerText = "📶";
    }
});

function setAmount(val) {
    document.getElementById('amount').value = val;
}

function confirmAirtime() {
    let phone = hiddenPhone.value;
    let amount = document.getElementById('amount').value;

    if (!phone || phone.length < 11) {
        alert("Enter valid phone");
        return false;
    }

    return confirm(`Buy ₦${amount} airtime for ${phone}?`);
}
</script>

</x-app-layout>