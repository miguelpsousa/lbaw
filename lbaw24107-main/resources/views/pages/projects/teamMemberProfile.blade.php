@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-semibold text-gray-900 mb-6">Team Member: {{ $teamMember->username }}</h1>
    
    <!-- Profile Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <div class="flex items-center mb-6">
            <!-- Profile Picture -->
            @if ($teamMember->profile_picture)
                <img src="{{ asset('storage/' . $teamMember->profile_picture) }}" alt="{{ $teamMember->username }}'s Profile Picture" class="w-32 h-32 rounded-full border-2 border-gray-300 mr-6">
            @else
                <div class="w-32 h-32 rounded-full border-2 border-gray-300 mr-6 flex items-center justify-center bg-gray-200">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
            <!-- Username & Role -->
            <div>
                <h2 class="text-3xl font-semibold text-gray-800 inline">{{ $teamMember->username }}</h2>
                
                <!-- Role Display -->
                @if ($teamMember->invite_status != 'pending')
                    <span class="text-xl font-semibold text-indigo-600 ml-4">{{ $teamMember->pivot->role }}</span>
                @else
                    <span class="text-xl font-semibold text-gray-500 ml-4">Pending invitation...</span>
                @endif
            </div>
        </div>

        <!-- Invitation Status -->
        @if ($teamMember->invite_status == 'pending')
            <div class="text-sm text-yellow-500 mb-4">
                <p>Pending invitation...</p>
            </div>
        @endif

        <!-- Member Details -->
        <div class="space-y-4">
            <p class="text-lg text-gray-700"><strong>Email:</strong> {{ $teamMember->email }}</p>
            <p class="text-lg text-gray-700"><strong>Phone Number:</strong> {{ $teamMember->phone_number ?? 'Not provided' }}</p>
            <p class="text-lg text-gray-700"><strong>Status:</strong> {{ $teamMember->status ?? 'Active' }}</p>
        </div>
    </div>
</div>
@endsection
