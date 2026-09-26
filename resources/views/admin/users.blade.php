<x-admin-layout>
<table class="w-full bg-gray-100 shadow rounded">
<tr class="w-full bg-purple-400 text-white">
<th>Name</th>
<th>Email</th>
<th>Balance</th>
<th>Role</th>
</tr>
@foreach($users as $user)
<tr class="w-full border-t text-lg text-center">
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td> ₦{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</td>
<td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
</tr>
@endforeach
</table>
</x-admin-layout>