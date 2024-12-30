<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
	{
		$tasks = Task::where('user_id', Auth::id())->get();
		return view('tasks.index', compact('tasks'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'title' => 'required|unique:tasks|max:255',
			'description' => 'nullable|string',
		]);

		$task = new Task();
		$task->user_id = Auth::id();
		$task->title = $request->title;
		$task->description = $request->description;
		$task->save();

		return redirect()->back()->with('success', 'Task created successfully.');
	}

	public function update(Request $request, $id)
	{
		$task = Task::findOrFail($id);

		$request->validate([
			'title' => 'required|unique:tasks,title,' . $task->id . '|max:255',
			'description' => 'nullable|string',
			'completed' => 'boolean',
		]);

		$task->title = $request->title;
		$task->description = $request->description;
		$task->completed = $request->completed;
		$task->save();

		return redirect()->back()->with('success', 'Task updated successfully.');
	}

	public function destroy($id)
	{
		$task = Task::findOrFail($id);
		$task->delete();

		return redirect()->back()->with('success', 'Task deleted successfully.');
	}
}
