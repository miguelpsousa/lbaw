
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-semibold text-gray-900 mb-6">All Users</h1>
    
    <table class="min-w-full bg-white">
        <div class="mb-4">
            <a href="{{ route('users.create') }}" class="py-2 px-4 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">Create User</a>
        </div>
        <thead>
            <tr>
                <th class="py-2 px-4 border-b border-gray-200">ID</th>
                <th class="py-2 px-4 border-b border-gray-200">Username</th>
                <th class="py-2 px-4 border-b border-gray-200">Email</th>
                <th class="py-2 px-4 border-b border-gray-200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $user->id }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $user->username }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b border-gray-200">
                        <a href="{{ route('profile.show', $user->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        <form action="{{ route('profile.destroy', $user->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection