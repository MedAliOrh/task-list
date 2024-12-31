<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Task List</title>
</head>
<body>
	<h1>Task List :</h1>
	@if (session('success'))
		<p>{{ session('success') }}</p>
	@endif
	<form action="/tasks" method="POST">
		@csrf
		<label for="title">Title</label>
		<input type="text" name="title" id="title">
		<label for="description">Description</label>
		<input type="text" name="description" id="description">
		<button type="submit">Add Task</button>
	</form>
	<ul>
		@foreach ($tasks as $task)
			<li>
				{{ $task->title }} - {{ $task->description }}
				@if ($task->completed)
					(<span style="color: green;">Completed</span>)
				@else
					(<span style="color: red;">Not Completed</span>)
					<form action="/tasks/{{ $task->id }}/complete" method="POST">
						@csrf
						@method('PUT')
						<button type="submit">Complete</button>
					</form>
				@endif
				<form action="/tasks/{{ $task->id }}" method="POST">
					@csrf
					@method('DELETE')
					<button type="submit">Delete</button>
				</form>
			</li>
		@endforeach
	</ul>
</body>
</html>