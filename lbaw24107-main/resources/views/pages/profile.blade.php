@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-semibold text-gray-900 mb-6">Profile of {{ $user->username }}</h1>
    
    <!-- Profile Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <div class="flex items-center mb-6">
            <!-- Profile Picture -->
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->username }}'s Profile Picture" class="w-32 h-32 rounded-full border-2 border-gray-300 mr-6">
                <form action="{{ route('profile.deletePicture', $user->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-block px-6 py-3 bg-red-600 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-red-700 transition-all duration-300 ease-in-out">
                        Delete Profile Picture
                    </button>
                </form>
            @else
                <div class="w-32 h-32 rounded-full border-2 border-gray-300 mr-6 flex items-center justify-center bg-gray-200">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
            <!-- Username & Bio -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">{{ $user->username }}</h2>
                <p class="text-gray-600 mt-2">{{ $user->biography ?? 'No biography available.' }}</p>
            </div>
        </div>

        <!-- User Details -->
        <div class="space-y-4">
            <p class="text-lg text-gray-700"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="text-lg text-gray-700"><strong>Phone Number:</strong> {{ $user->phone_number ?? 'Not provided' }}</p>
        </div>
    </div>

    <!-- Edit Profile Button -->
    @if (Auth::user()->id == $user->id || Auth::user()->is_admin)
    <div class="user_options flex space-x-4"> <!-- Flex container for buttons -->
        <a href="{{ url('/profile/' . $user->id . '/edit') }}" class="inline-block px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out">
            Edit Profile
        </a>
        <form action="{{ route('profile.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block px-6 py-3 bg-red-600 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-red-700 transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-trash mr-2" aria-hidden="true"></i> Delete Account
            </button>
        </form>
        @if(Auth::user()->is_admin)
            <a href="{{ route('profile.userProjects', $user->id) }}" class="inline-block px-6 py-3 bg-green-600 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-green-700 transition-all duration-300 ease-in-out">
                Manage Projects
            </a>
        @endif
    </div>
    @endif
</div>
@endsection
