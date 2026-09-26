<label class="border rounded-xl p-3 cursor-pointer hover:bg-purple-50 transition text-center shadow-sm">

    <input type="radio"
           name="variation_code"
           value="{{ $plan['variation_code'] }}"
           data-amount="{{ $plan['variation_amount'] }}"
           required>

    <div class="text-xs font-medium">
        {{ $plan['name'] }}
    </div>

    <div class="text-purple-600 font-semibold text-sm mt-1">
        ₦{{ number_format($plan['variation_amount']) }}
    </div>

</label>