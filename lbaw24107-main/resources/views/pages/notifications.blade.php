@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Your Notifications</h1>

    @foreach ($notifications as $notification)
        <div class="notification bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
            <p class="text-lg text-gray-800 mb-4">{{ $notification->notification_text }}</p>
            <p class="text-sm text-gray-600 mt-4">{{ $notification->created_at->format('d/m/Y H:i') }}</p>

            @if($notification->notification_type == 'invitation')
                @if($notification->response == 'pending')
                    <form action="{{ route('notifications.respond') }}" method="POST" class="flex space-x-4">
                        @csrf
                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                        
                        <button type="submit" name="response" value="accept" 
                                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                            Accept
                        </button>

                        <button type="submit" name="response" value="decline" 
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                            Decline
                        </button>
                    </form>
                @else
                    <p class="response-status mt-4 text-sm text-gray-600 italic">
                        @if($notification->response === 'accepted')
                            <span class="text-green-600">You have accepted the invitation.</span>
                        @elseif($notification->response === 'declined')
                            <span class="text-red-600">You have declined the invitation.</span>
                        @endif
                    </p>
                @endif
            @endif
        </div>
    @endforeach
</div>
@endsection
