<div class="grid grid-cols-2 md:grid-cols-3 gap-2">
@foreach($plans as $plan)
<label class="border p-2 rounded-lg cursor-pointer hover:bg-blue-50 shadow-sm">
    <input type="radio"
           name="variation_code"
           value="{{ $plan['variation_code'] }}"
           data-amount="{{ $plan['variation_amount'] }}"
           required>

    <div class="text-xs font-medium">
        {{ $plan['name'] }}
    </div>

    <div class="text-blue-600 text-sm font-semibold mt-1">
        ₦{{ number_format($plan['variation_amount']) }}
    </div>
</label>
@endforeach
</div>