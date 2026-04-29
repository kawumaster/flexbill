<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Modern Dashboard -->
    <div class="min-h-screen bg-gray-100 p-4 pb-24">
        
        <!-- Greeting -->
        <div class="text-lg font-semibold mb-2">Welcome Back 👋</div>
        <div class="text-gray-600 mb-4">{{ Auth::user()->name ?? 'User' }}</div>

        <!-- Wallet Card -->
        <div class="bg-gradient-to-r from-green-700 to-blue-60 text-white p-5 rounded-2xl shadow-md mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm">Available Balance</p>
                    <p class="text-3xl font-bold mt-1">  ₦{{ number_format(auth()->user()->wallet->balance ?? 10, 2) }}
                        
                    </p>


                </div>
                <a href="{{ route('add-money') }}" 
                   class="bg-white text-green-600 px-3 py-1 rounded-lg text-sm font-semibold hover:bg-white-50">
                    + Add Money
                </a>
            </div>
            <div class="mt-3 text-sm text-align-center">Referral Commission: ₦100.00</div>
        </div>

        <!-- Pay Bills Section -->

        <div>
            
            <h3 class="text-md font-semibold mb-3">Pay Bills</h3>


        <div class="grid grid-cols-4 gap-4 mb-6">
            <a href="{{ route('airtime') }}" class="flex flex-col items-center bg-white p-3 rounded-xl shadow hover:shadow-md">
                <div class="text-blue-500 text-3xl">📱</div>
                <p class="text-sm mt-1">Airtime</p>
            </a>
            <a href="{{ route('data.index') }}" class="flex flex-col items-center bg-white p-3 rounded-xl shadow hover:shadow-md">
                <div class="text-green-500 text-3xl">🌐</div>
                <p class="text-sm mt-1">Data</p>
            </a>
            <a href="{{ route('tv') }}" class="flex flex-col items-center bg-white p-3 rounded-xl shadow hover:shadow-md">
                <div class="text-yellow-500 text-3xl">📺</div>
                <p class="text-sm mt-1">TV</p>
            </a>
            <a href="{{ route('electricity') }}" class="flex flex-col items-center bg-white p-3 rounded-xl shadow hover:shadow-md">
                <div class="text-red-500 text-3xl">💡</div>
                <p class="text-sm mt-1">Electricity</p>
            </a>
        </div> 
        </div>

        


        <!-- Recent Activity -->
       
             <div class="mt-6 bg-white p-4 rounded-xl shadow">
    <h3 class="text-lg font-semibold mb-3">Recent Activity</h3>
    <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Date</th>
                        <th class="p-2 border">Type</th>
                        <th class="p-2 border">Reference</th>
                        <th class="p-2 border">Amount</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <tr>
                            <td class="p-2 border">23/9/2939</td>
                            <td class="p-2 border capitalize">data</td>
                            <td class="p-2 border">dat-gf4564fg7yt67</td>
                            <td class="p-2 border">500</td>
                            <td class="p-2 border">Successfull
                                <span class="px-2 py-1 rounded text-white
                                   "> 
                                    
                                </span>
                            </td>
                        </tr>
                    
                        <!-- <tr>
                            <td colspan="5" class="text-center p-4">
                                No transactions found
                            </td>
                        </tr> -->
                  
                </tbody>
            </table>

    
    
</div>


            
        </div>


        

        <!-- Bottom Navigation -->

        <nav class="fixed bottom-0 left-0 w-full bg-white shadow-inner flex justify-around py-3">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-blue-600">
                <span class="text-xl">🏠</span>
                <span class="text-xs">Home</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="flex flex-col items-center text-gray-500 hover:text-blue-600">
                <span class="text-xl">📄</span>
                <span class="text-xs">History</span>
            </a>

            <a href="#" class="flex flex-col items-center text-gray-500 hover:text-blue-600">
                <span class="text-xl">👤</span>
                <span class="text-xs">Profile</span>
            </a>
        </nav>
    </div>
</x-app-layout>