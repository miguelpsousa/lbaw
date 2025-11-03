@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        @include('partials.sidebar')

        <main class="flex-1 p-8 bg-gray-100">
            <div class="flex justify-between items-center pb-6 border-b">
                <h1 class="text-2xl font-bold">{{ $project->name }} - Forum</h1>
            </div>

            <!-- Display success message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-md border border-green-500">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display messages -->
            @foreach($messages as $message)
                <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                    <div class="flex items-center mb-2">
                        <img src="{{ asset('storage/' . $message->user->profile_picture) }}" alt="Profile Picture" class="w-10 h-10 rounded-full">
                        <strong class="ml-2">{{ $message->user->username }}</strong>
                        <span class="ml-2 text-gray-500">{{ $message->getFormattedTimestamp() }}</span>
                        @if($message->user_id === auth()->id())
                            <button class="ml-2 text-indigo-600 hover:text-indigo-800" onclick="editMessage({{ $message->id }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        @endif
                        @if($message->user_id === auth()->id() || auth()->user()->is_admin)
                            <form action="{{ route('messages.destroy', $message->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                    <div id="message-content-{{ $message->id }}">
                        <p class="text-gray-700">{{ $message->message_text }}</p>
                    </div>
                    <div class="mt-2">
                        <!-- Reply form -->
                        <form action="{{ route('messages.store', $project->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $message->id }}">
                            <div class="form-group">
                                <textarea name="message_text" class="form-control w-full p-2 border rounded-lg" rows="2" placeholder="Reply to this message"></textarea>
                            </div>
                            <button type="submit" class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 mt-2">Reply</button>
                        </form>
                    </div>
                    <!-- Display replies -->
                    @if($message->replies)
                        <div class="mt-4 space-y-4">
                            @foreach($message->replies as $reply)
                                <div class="p-4 bg-gray-100 rounded-lg shadow-inner">
                                    <div class="flex items-center mb-2">
                                        <img src="{{ asset('storage/' . $reply->user->profile_picture) }}" alt="Profile Picture" class="w-8 h-8 rounded-full">
                                        <strong class="ml-2">{{ $reply->user->username }}</strong>
                                        <span class="ml-2 text-gray-500">{{ $reply->getFormattedTimestamp() }}</span>
                                        @if($reply->user_id === auth()->id())
                                            <button class="ml-2 text-indigo-600 hover:text-indigo-800" onclick="editMessage({{ $reply->id }})">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endif
                                        @if($reply->user_id === auth()->id() || auth()->user()->is_admin)
                                            <form action="{{ route('messages.destroy', $reply->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div id="message-content-{{ $reply->id }}">
                                        <p class="text-gray-700">{{ $reply->message_text }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Form to create a new message -->
            <div class="mt-6 p-4 bg-white rounded-lg shadow-md">
                <h5 class="text-xl font-semibold mb-4">Post a new message</h5>
                <form action="{{ route('messages.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="message_text" class="form-control w-full p-2 border rounded-lg" rows="3" placeholder="Write your message"></textarea>
                    </div>
                    <button type="submit" class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 mt-2">Post Message</button>
                </form>
            </div>
        </main>
    </div>
@endsection