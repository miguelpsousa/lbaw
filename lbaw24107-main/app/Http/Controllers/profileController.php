<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\TaskResponsible;
use App\Models\TaskComment;
use App\Models\ProjectMember;
use App\Models\Message;
use App\Models\Notification;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Hash;



class profileController extends Controller
{
    //Show the profile page
    public function show($id)
    {
        // Fetch the user by ID
        $user = User::findOrFail($id);

        // Pass the user data to the view
        return view('pages.profile', compact('user'));
    }

    public function edit(User $user){
        if (Auth::user()->id !== $user->id && !Auth::user()->is_admin) {
            return redirect()->route('pages.profile', $user->id);
        }

        return view('pages.editProfile', compact('user'));
    }

    public function update(Request $request, User $user){

        if (Auth::user()->id !== $user->id && !Auth::user()->is_admin) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'Unauthorized access to update profile.');
        }

        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:30', 'unique:account,username,' . $user->id],
            'email' => ['required', 'email', 'unique:account,email,' . $user->id],
            'biography' => ['nullable', 'string', 'max:250'],
            'phone_number' => ['nullable', 'string', 'min:9', 'max:15', 'unique:account,phone_number,' . $user->id],
            'status' => ['nullable', 'string', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Adjust max size as needed
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicturePath;
        }

        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->biography = $validatedData['biography'] ?? null;
        $user->phone_number = $validatedData['phone_number'] ?? null;
        $user->status = $validatedData['status'] ?? null;

        if ($validatedData['password']) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        return redirect()->route('profile.show', $user->id);
    }

    public function deleteProfilePicture(User $user)
    {
        if (Auth::user()->id !== $user->id) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'Unauthorized access to delete profile picture.');
        }

        // Delete the profile picture file if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
        }

        return redirect()->route('profile.show', $user->id)
            ->with('success', 'Profile picture deleted successfully.');
    }
    public function deleteUser(User $user) {
        // Find the deleted_user account
        $deletedUser = User::where('username', 'deleted_user')->first();
        if (!$deletedUser) {
            return false;
        }
    
        // Reassign user content to the deleted_user
        DB::transaction(function () use ($user, $deletedUser) {
            // Reassign tasks created by the user
            Task::where('creator_id', $deletedUser->id)
                ->delete(); // Remove conflicting tasks first
            Task::where('creator_id', $user->id)
                ->update(['creator_id' => $deletedUser->id]);
    
            // Reassign task responsibilities
            TaskResponsible::where('user_id', $deletedUser->id)
                ->whereIn('task_id', TaskResponsible::where('user_id', $user->id)->pluck('task_id'))
                ->delete(); // Remove conflicts
            TaskResponsible::where('user_id', $user->id)
                ->update(['user_id' => $deletedUser->id]);
    
            // Reassign task comments
            TaskComment::where('user_id', $deletedUser->id)
                ->whereIn('task_id', TaskComment::where('user_id', $user->id)->pluck('task_id'))
                ->delete(); // Remove conflicts
            TaskComment::where('user_id', $user->id)
                ->update(['user_id' => $deletedUser->id]);
    
            // Reassign project memberships
            ProjectMember::where('user_id', $deletedUser->id)
                ->whereIn('project_id', ProjectMember::where('user_id', $user->id)->pluck('project_id'))
                ->delete(); // Remove conflicts
            ProjectMember::where('user_id', $user->id)
                ->update(['user_id' => $deletedUser->id]);
    
            // Reassign messages sent by the user
            Message::where('user_id', $deletedUser->id)
                ->whereIn('id', Message::where('user_id', $user->id)->pluck('id'))
                ->delete(); // Remove conflicts
            Message::where('user_id', $user->id)
                ->update(['user_id' => $deletedUser->id]);
    
            // Reassign notifications sent/received by the user
            Notification::where('sender_id', $deletedUser->id)
                ->whereIn('id', Notification::where('sender_id', $user->id)->pluck('id'))
                ->delete(); // Remove conflicts
            Notification::where('sender_id', $user->id)
                ->update(['sender_id' => $deletedUser->id]);
    
            Notification::where('receiver_id', $deletedUser->id)
                ->whereIn('id', Notification::where('receiver_id', $user->id)->pluck('id'))
                ->delete(); // Remove conflicts
            Notification::where('receiver_id', $user->id)
                ->update(['receiver_id' => $deletedUser->id]);
    
            // Reassign settings to the deleted_user
            UserSettings::where('user_id', $deletedUser->id)
                ->delete(); // Remove conflicting settings
            UserSettings::where('user_id', $user->id)
                ->update(['user_id' => $deletedUser->id]);
    
            // Finally, delete the user
            $user->delete();
        });
    
        return true;
    }
    
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        if(!$user){
            return redirect()->route('dashboard')
                ->with('error', 'User not found.');
        }
        
        if (Auth::user()->is_admin !== true && Auth::user()->id != $userId) {
            Log::info("message");
            return redirect()->route('profile.show', $userId)
                ->with('error', 'Unauthorized access to delete user.');
        }

        // Delete the profile picture file if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        Log::info("message");
        $success = $this->deleteUser($user);

        if(Auth::user()->id == $userId) {
            Auth::logout();
            return redirect()->route('login')
                ->with('success', 'Account deleted successfully.');
        }
        else{
            if($success){
                return redirect()->route('users.index')
                    ->with('success', 'User deleted successfully.');
            }
            else{
                return redirect()->route('users.index')
                    ->with('error', 'Failed to delete user.');
            }
        }
        
    }

    public function index()
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access to view users.');
        }

        $users = User::all();

        return view('pages.users', compact('users'));
    }

    public function userProjects($id)
    {
        $user = User::findOrFail($id);
        $projects = $user->projects()->get(); // Fetch only the projects of the specific user

        return view('pages.userProjects', compact('user', 'projects'));
    }
    
    public function create(){
        if (!Auth::user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        return view('pages.createUser');
    }

    public function store(Request $request){
        if (!Auth::user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
    
        $request->validate([
            'username' => 'required|string|max:30|unique:account',
            'email' => 'required|email|max:250|unique:account',
            'password' => 'required|min:8|max:25|confirmed'
        ]);
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'biography' => 'placeholder'
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }
}

    
    

