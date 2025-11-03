@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
@include('partials.sidebar')

<div class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Project Members</h1>

    <!-- Members List -->
    <div class="members-list space-y-4">
        @foreach($teamMembers as $member)
        <a href="{{ url('/projects/' . $projectId . '/team-members/' . $member->id ) }}" class="block">
            <div class="member bg-white p-4 rounded-lg shadow-md flex justify-between items-center hover:bg-gray-100 transition duration-200">
                <div class="member-name text-lg font-semibold text-gray-800">
                    <p>{{ $member->username }}</p>
                </div>
                
                @if($member->pivot->invite_status == 'pending')
                    <div class="member-status text-sm text-yellow-500">
                        <p>Pending invitation...</p>
                    </div>
                @elseif($member->pivot->role == 'Project Coordinator')
                    <div class="member-status text-sm text-indigo-600 flex items-center">
                        <p>Project Coordinator</p>
                        <i class="fa-solid fa-crown ml-2 text-yellow-500"></i>
                    </div>
                @else
                    <div class="member-status text-sm text-gray-600">
                        @if ($userInProject->pivot->role == 'Project Coordinator')
                        <a href="{{ url('/projects/' . $projectId . '/team-members/' . $member->id . '/promote') }}" 
                           class="inline-block py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                            Promote to coordinator
                        </a>
                            <a href="{{ url('/projects/' . $projectId . '/team-members/' . $member->id . '/remove') }}" 
                               class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition">
                                Remove
                            </a>
                        @else
                            <p>Member</p>
                        @endif
                    </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    <!-- Add Member Section (Only for Project Coordinator) -->
    @if($userInProject->pivot->role == 'Project Coordinator')
        <div class="add-member mt-8" data-projectid="{{$projectId}}">
            <label for="add-member" class="block text-lg font-medium text-gray-700 mb-2">Add Member</label>
            <input type="text" placeholder="Username" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <div class="search-results space-y-2 mt-2">
                <!-- Insert search results dynamically here -->
            </div>
        </div>
    @endif
</div>
</div>


@endsection
