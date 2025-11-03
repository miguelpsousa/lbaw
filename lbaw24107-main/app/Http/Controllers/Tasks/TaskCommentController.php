<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'comment_text' => 'required|max:250',
            'task_id' => 'required|exists:task,id',
        ]);

        TaskComment::create([
            'comment_text' => $request->comment_text,
            'user_id' => auth()->id(),
            'task_id' => $request->task_id,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

	public function edit($id)
	{
		$comment = TaskComment::findOrFail($id);
		if ($comment->user_id !== auth()->id()) {
			return redirect()->back()->with('error', 'You are not authorized to edit this comment.');
		}
		return view('task-comments.edit', compact('comment'));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'comment_text' => 'required|max:250',
		]);

		$comment = TaskComment::findOrFail($id);
		if ($comment->user_id !== auth()->id()) {
			return redirect()->back()->with('error', 'You are not authorized to edit this comment.');
		}

		$comment->update([
			'comment_text' => $request->comment_text,
		]);

		return redirect()->route('tasks.show', $comment->task_id)->with('success', 'Comment updated successfully!');
	}

	public function destroy($id)
	{
		$comment = TaskComment::findOrFail($id);
		if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
			return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
		}

		$comment->delete();

		return redirect()->back()->with('success', 'Comment deleted successfully!');
	}
}
