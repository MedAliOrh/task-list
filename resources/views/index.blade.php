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
	
</body>
</html>