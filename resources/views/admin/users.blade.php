<x-admin-layout>
<table class="w-full bg-white shadow rounded">
<tr>
<th>Name</th><th>Email</th><th>Role</th>
</tr>
@foreach($users as $user)
<tr>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
</tr>
@endforeach
</table>
</x-admin-layout>