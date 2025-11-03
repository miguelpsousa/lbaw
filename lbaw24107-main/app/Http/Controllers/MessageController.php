<?php

	namespace App\Http\Controllers;

	use App\Models\Message;
	use App\Models\Project;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;

	class MessageController extends Controller
	{
		public function index($projectId)
		{
			$project = Project::findOrFail($projectId);

			$messages = Message::where('project_id', $projectId)
				->whereNull('parent_id')
				->with('replies')
				->orderBy('created_at', 'asc')
				->get();

			return view('pages.projects.projectForum', compact('messages', 'project'));
		}

		public function store(Request $request, $projectId)
		{
			$request->validate([
				'message_text' => 'required|max:250',
				'parent_id' => 'nullable|exists:message,id',
			]);

			Message::create([
				'message_text' => $request->message_text,
				'user_id' => Auth::id(),
				'project_id' => $projectId,
				'parent_id' => $request->parent_id,
			]);

			return redirect()->route('messages.index', $projectId)->with('success', 'Message posted successfully.');
		}

		public function edit($id)
		{
			$message = Message::findOrFail($id);
			if ($message->user_id !== Auth::id()) {
				return redirect()->back()->with('error', 'You are not authorized to edit this message.');
			}
			return view('messages.edit', compact('message'));
		}

		public function update(Request $request, $id)
		{
			$request->validate([
				'message_text' => 'required|max:250',
			]);

			$message = Message::findOrFail($id);
			if ($message->user_id !== Auth::id()) {
				return redirect()->back()->with('error', 'You are not authorized to edit this message.');
			}

			$message->update([
				'message_text' => $request->message_text,
			]);

			return redirect()->route('messages.index', $message->project_id)->with('success', 'Message updated successfully.');
		}

		public function destroy($id)
		{
			$message = Message::findOrFail($id);
			if ($message->user_id !== Auth::id() && !Auth::user()->is_admin) {
				return redirect()->back()->with('error', 'You are not authorized to delete this message.');
			}

			$message->delete();

			return redirect()->route('messages.index', $message->project_id)->with('success', 'Message deleted successfully.');
		}
	}