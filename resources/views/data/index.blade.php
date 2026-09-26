<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800">
            Data
        </h2>
    </x-slot>

@php
    $wallet = auth()->user()->wallet;
@endphp

<div class="max-w-3xl mx-auto mt-4 space-y-4">
    
    {{-- ALERTS --}}
    @if(session('error'))
        <div class="text-red-600 text-center text-sm">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-2 rounded-lg text-center text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- WALLET --}}
    <div class="p-3 bg-green-50 text-green-700 rounded-xl text-center text-xs font-medium shadow-sm">
        Wallet Balance: ₦{{ number_format($wallet->balance ?? 0, 2) }}
    </div>

    {{-- TOP CARD --}}
    <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div id="networkBadge"
                     class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-bold">
                    N
                </div>

                <div>
                    <div id="phoneDisplay" class="font-semibold text-lg tracking-wide">
                        08XXXXXXXXX
                    </div>
                    <div id="networkPreview" class="text-xs text-gray-500"></div>
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-400 mt-3">
            Enjoy fast and affordable data bundles
        </p>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('data.buy') }}" id="dataForm"
          class="bg-white p-4 rounded-2xl shadow space-y-4">
        @csrf

        {{-- PHONE --}}
        <input type="text" name="phone" id="phone"
               class="w-full border rounded-xl p-3 text-sm"
               placeholder="Enter phone number" required>

        {{-- NETWORK --}}
        <div class=" network-crad grid grid-cols-4 gap-2 text-xs">
            @foreach(['mtn','airtel','glo','9mobile'] as $net)
                <label class=" border rounded-xl p-2 text-center cursor-pointer hover:bg-gray-100 bg-gray-100">
                    <input type="radio" name="network" value="{{ $net }}">
                    <div class="uppercase mt-1">{{ $net }}</div>
                </label>
            @endforeach
        </div>

        {{-- TABS --}}
        <div class="flex gap-4 text-sm border-b pb-2" id="tabs">
            <button type="button" data-tab="all" class="tab active">Best</button>
            <button type="button" data-tab="daily" class="tab">Daily</button>
            <button type="button" data-tab="weekly" class="tab">Weekly</button>
            <button type="button" data-tab="monthly" class="tab">Monthly</button>
        </div>

        {{-- PLANS --}}
        <div id="plansContainer" class="grid grid-cols-3 md:grid-cols-3 gap-3">
            <div class="text-gray-400 text-sm">Select network...</div>
        </div>

        {{-- BUTTON --}}
        <button id="buyBtn"
                class="w-full bg-purple-600 text-white py-3 rounded-xl text-sm font-semibold">
            Buy Data
        </button>
    </form>
</div>

<style>
.tab {
    padding-bottom: 4px;
    color: gray;
}
.tab.active {
    color: purple;
    border-bottom: 2px solid purple;
    font-weight: 600;
}
.plan-card input:checked + div {
    border: 2px solid purple;
}

.network-card .checked {
    border: 2px solid purple;
}
</style>

<script>

// NETWORK COLORS
const networkColors = {
    mtn: 'bg-yellow-400',
    airtel: 'bg-red-500',
    glo: 'bg-green-500',
    '9mobile': 'bg-green-700'
};

// NETWORK PREFIX
const networkMap = {
    mtn: ['0803','0806','0813','0816','0810','0814','0903','0906','0703','0706'],
    glo: ['0805','0807','0815','0811','0905'],
    airtel: ['0802','0808','0812','0701','0708','0902','0907'],
    '9mobile': ['0809','0817','0818','0909']
};

let allPlans = [];
let currentTab = 'all';

// PHONE INPUT
document.getElementById('phone').addEventListener('input', function () {

    let phone = this.value;
    document.getElementById('phoneDisplay').innerText = phone || "08XXXXXXXXX";

    let prefix = phone.substring(0,4);
    let detected = null;

    for (let net in networkMap) {
        if (networkMap[net].includes(prefix)) detected = net;
    }

    if (detected) {
        document.getElementById('networkPreview').innerText = detected.toUpperCase();

        let badge = document.getElementById('networkBadge');
        badge.className = `w-10 h-10 rounded-full text-white flex items-center justify-center ${networkColors[detected]}`;
        badge.innerText = detected.charAt(0).toUpperCase();

        document.querySelectorAll('input[name="network"]').forEach(el => {
            el.checked = el.value === detected;
        });

        loadPlans(detected);
    }
});


// LOAD PLANS
document.querySelectorAll('input[name="network"]').forEach(el => {
    el.addEventListener('change', function () {
        loadPlans(this.value);
    });
});

function loadPlans(network) {
    let container = document.getElementById('plansContainer');

    container.innerHTML = "Loading...";

    fetch(`/data/plans/${network}`)
        .then(res => res.json())
        .then(plans => {
            allPlans = plans;
            renderPlans();
        });
}


// FILTER LOGIC
function renderPlans() {
    let container = document.getElementById('plansContainer');

    let filtered = allPlans.filter(p => {

        let name = p.name.toLowerCase();

        if (currentTab === 'daily') return name.includes('1 day');
        if (currentTab === 'weekly') return name.includes('7 days');
        if (currentTab === 'monthly') return name.includes('30 days');

        return true;
    });

    if (!filtered.length) {
        container.innerHTML = "No plans";
        return;
    }

    container.innerHTML = "";

    filtered.slice(0,9).forEach(plan => {

        container.innerHTML += `
        <label class="plan-card cursor-pointer">
            <input type="radio" name="variation_code"
                   value="${plan.variation_code}"
                   data-amount="${plan.variation_amount}" hidden>

            <div class="bg-gray-100 rounded-2xl p-3 text-center hover:shadow">

                <div class="text-xs text-gray-500">
                    ${plan.name.match(/\\d+\\s?day/i) || ''}
                </div>

                <div class="font-semibold text-sm">
                    ${plan.name}
                </div>

                <div class="text-gray-400 text-xs line-through">
                    ₦${parseInt(plan.variation_amount).toLocaleString()}
                </div>

                <div class="text-purple-600 font-bold text-sm">
                    Pay ₦${parseInt(plan.variation_amount).toLocaleString()}
                </div>

            </div>
        </label>
        `;
    });
}


// TABS
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function () {

        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        currentTab = this.dataset.tab;

        renderPlans();
    });
});


// BUTTON LOADING
document.getElementById('dataForm').addEventListener('submit', function () {
    let btn = document.getElementById('buyBtn');
    btn.innerText = "Processing...";
    btn.disabled = true;
});

</script>

</x-app-layout>