@extends('layouts.app')

@section('content')
<div class="p-8">
<h1 class="text-2xl font-bold mb-6 text-center">Edit Profile</h1>

<div class="max-w-lg mx-auto p-8 bg-white rounded-lg shadow-lg">
    <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        {{ csrf_field() }}
        @method('PUT')

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" value="{{ $user->username }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Biography -->
        <div>
            <label for="biography" class="block text-sm font-medium text-gray-700">Biography</label>
            <input name="biography" value="{{ $user->biography }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></input>
        </div>

        <!-- Phone Number -->
        <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Profile Picture -->
        <div>
            <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Update Profile</button>
        </div>
    </form>
</div>
</div>




@endsection
