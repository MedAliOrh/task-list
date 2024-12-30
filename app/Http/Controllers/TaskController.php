<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
