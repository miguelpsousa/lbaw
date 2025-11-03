@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-8 bg-gray-50">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold">Project Dashboard</h1>
            </div>

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-md border border-red-500">
                    {{ session('error') }}
                </div>
            @endif

        <!-- Project Overview Section -->
        <div class="mb-6 relative">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h4 class="text-xl font-semibold mb-4">Project Overview</h4>
                <p><strong>Name:</strong> {{ $project->name }}</p>
                <p><strong>Category:</strong> {{ $project->category->name }}</p>
                <p><strong>Status:</strong> {{ $project->status }}</p>
                <p><strong>Description:</strong> {{ $project->description }}</p>
                @if ($project->members->where('id', Auth::id())->first()->pivot->role == 'Project Coordinator' && $project->status != 'archived')
                    <a href="{{ url('/projects/' . $project->id . '/edit') }}" 
                       class="inline-block py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                        Edit
                    </a>
                    <a href="{{ url('/projects/' . $project->id . '/archive') }}" 
                    class="inline-block py-2 px-4 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700">
                        Archive
                    </a>
                @elseif ($project->status != 'archived')
                    <form action="{{ url('/projects/' . $project->id . '/leave') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                                class="py-2 px-4 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700">
                            Leave Project
                        </button>
                    </form>
                @endif
                <div class="absolute bottom-4 right-4 text-xl favorite-star" data-projectid="{{ $project->id }}">
                    @if($favorite)
                        <i class="fa-solid fa-star text-yellow-500 hover:text-yellow-600"></i>
                    @else
                        <i class="fa-regular fa-star text-gray-400 hover:text-gray-500"></i>
                    @endif
                </div>
            </div>

        </div>
        <!-- Team Members Button -->
        <div class="mb-6">
            <a href="{{ url('/projects/' . $project->id . '/team-members') }}" 
               class="inline-block py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                See Team Members
            </a>
        </div>

        <!-- Create New Task Button -->
        @if ($project->status != 'archived')
            <div class="mb-6">
                <a href="{{ url('/tasks/create/' . $project->id) }}" 
                   class="inline-block py-2 px-4 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                    Create New Task
                </a>
            </div>
        @endif

            <!-- Search Tasks -->
            <div class="mb-6">
                <input type="text" id="task-search" class="form-control w-full p-3 rounded-md border border-gray-300"
                       placeholder="Search tasks by name">
            </div>

            <!-- Tasks Table -->
            <div class="bg-white shadow-lg rounded-lg p-6 mb-12"> <!-- Increased margin-bottom -->
                <h4 class="text-xl font-semibold mb-4">Tasks</h4>
                <table class="min-w-full table-auto">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Task</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Responsible(s)</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="task-list">
                    @if($project->tasks && $project->tasks->isNotEmpty())
                        @foreach($project->tasks as $task)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    <a href="{{ url('/tasks/' . $task->id) }}" class="text-blue-600 hover:underline">{{ $task->name }}</a>
                                    @if ($project->members->where('id', Auth::id())->first()->pivot->role == 'Project Coordinator' || Auth::id() == $task->creator_id)
                                        <a href="{{ url('/tasks/' . $task->id . '/edit') }}" 
                                           class="inline-block ml-2 py-1 px-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $task->status }}</td>
                                <td class="px-4 py-2">
                                    @if ($task->responsibles->isNotEmpty())
                                        @foreach($task->responsibles as $responsible)
                                            <span class="responsible-username" data-task-id="{{ $task->id }}" data-user-id="{{ $responsible->id }}">{{ $responsible->username }}</span><br>
                                        @endforeach
                                        @if ($project->members->where('id', Auth::id())->first()->pivot->role == 'Project Coordinator')
                                        <button type="button" 
                                                class="py-1 px-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 assign-member-btn"
                                                data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $project->id }}">
                                            +
                                        </button>
                                        <!-- Modal -->
                                        <div class="hidden assign-member-modal" id="assign-member-modal-{{ $task->id }}">
                                            <input type="text" placeholder="Search members..." class="search-members-input w-full p-3 border border-gray-300 rounded-md shadow-sm" data-task-id="{{ $task->id }}" data-project-id="{{ $project->id }}">
                                            <ul class="mt-4 bg-white rounded-md shadow-md p-4 search-results-list" id="search-results-list-{{ $task->id }}">
                                                <!-- Search results inserted dynamically -->
                                            </ul>
                                        </div>
                                        @endif
                                    @else
                                        @if ($project->members->where('id', Auth::id())->first()->pivot->role == 'Project Coordinator')
                                            <button type="button" 
                                                    class="py-1 px-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 assign-member-btn"
                                                    data-task-id="{{ $task->id }}"
                                                    data-project-id="{{ $project->id }}">
                                                Assign member
                                            </button>
                                            <!-- Modal -->
                                            <div class="hidden assign-member-modal" id="assign-member-modal-{{ $task->id }}">
                                                <input type="text" placeholder="Search members..." class="search-members-input w-full p-3 border border-gray-300 rounded-md shadow-sm" data-task-id="{{ $task->id }}" data-project-id="{{ $project->id }}">
                                                <ul class="mt-4 bg-white rounded-md shadow-md p-4 search-results-list" id="search-results-list-{{ $task->id }}">
                                                    <!-- Search results inserted dynamically -->
                                                </ul>
                                            </div>
                                        @else
                                            <span>Not assigned</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if ($task->responsibles->contains(Auth::id()) && $task->status != 'Completed' && $project->status != 'archived')
                                        <form method="POST" action="{{ route('tasks.complete', $task->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="py-1 px-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                                Complete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center">No tasks available.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Forum Link -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold">
                    <a href="{{ url('/projects/' . $project->id . '/forum') }}" class="text-blue-600 hover:underline">Project Forum</a>
                </h1>
            </div>

            <!-- Recent Forum Messages Section -->
            <div class="mb-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h4 class="text-xl font-semibold mb-4">Recent Forum Messages</h4>
                    @foreach($recentMessages as $message)
                        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                            <div class="flex items-center mb-2">
                                <img src="{{ asset('storage/' . $message->user->profile_picture) }}" alt="Profile Picture" class="w-10 h-10 rounded-full">
                                <strong class="ml-2">{{ $message->user->username }}</strong>
                                <span class="ml-2 text-gray-500">{{ $message->getFormattedTimestamp() }}</span>
                            </div>
                            <p class="text-gray-700">{{ $message->message_text }}</p>
                            <!-- Display replies -->
                            @if($message->replies)
                                <div class="mt-4 space-y-4">
                                    @foreach($message->replies as $reply)
                                        <div class="p-4 bg-white rounded-lg shadow-inner">
                                            <div class="flex items-center mb-2">
                                                <img src="{{ asset('storage/' . $reply->user->profile_picture) }}" alt="Profile Picture" class="w-8 h-8 rounded-full">
                                                <strong class="ml-2">{{ $reply->user->username }}</strong>
                                                <span class="ml-2 text-gray-500">{{ $reply->getFormattedTimestamp() }}</span>
                                            </div>
                                            <p class="text-gray-700">{{ $reply->message_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                window.taskData = @json($project->tasks);
                window.currentUserId = {{ Auth::id() }};
            </script>
        </main>
    </div>
@vite('resources/js/tasks/assignMember.js')
@endsection
