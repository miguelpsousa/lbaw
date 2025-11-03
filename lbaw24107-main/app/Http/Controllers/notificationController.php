<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class notificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->where('read_status', false)->count();

        return view('pages.notifications', compact('notifications', 'unreadCount'));
    }

    public function respond(Request $request)
    {   
        if(!(auth()->check())){
            return redirect('/login');
        }
        $request->validate([
            'notification_id' => 'required|exists:notification,id',
            'response' => 'required|in:accept,decline',
        ]);

        $notification = Notification::find($request->notification_id);

        if ($request->response === 'accept') {
            // Update invite status to accepted
            DB::table('project_member')
                ->where('user_id', $notification->receiver_id)
                ->where('project_id', $notification->project_id)
                ->update(['invite_status' => 'accepted']);
            $notification->update(['response' => 'accepted','read_status' => true]);
        } else {
            // Remove the invite entry
            DB::table('project_member')
                ->where('user_id', $notification->receiver_id)
                ->where('project_id', $notification->project_id)
                ->delete();
            $notification->update(['response' => 'declined','read_status' => true]);
        }

        // Mark notification as read

        return redirect()->route('notifications')->with('success', 'Response submitted.');
    }

    public function markAllRead()
    {
        Notification::where('receiver_id', Auth::id())
            ->where('read_status', false)
            ->update(['read_status' => true]);

        return redirect()->route('notifications')->with('success', 'All notifications marked as read.');
    }
}
