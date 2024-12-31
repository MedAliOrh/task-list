<script src="../tasks.js"></script>
<style src="../../css/tasks.css"></style>

<template>
<AuthenticatedLayout>
	<template #header>
		<h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Task Manager</h2>
	</template>

	<Head title="Task Manager" />

	<div class="py-12">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
	<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
	<div class="p-6 bg-white border-b border-gray-200"></div>
	<div class="container mx-auto mt-5">
		<div class="flex flex-wrap">
			<div class="w-full md:w-4/3 p-4">
				<div class="info-icon cursor-pointer text-2xl text-blue-500 mb-4" @click="toggleHelp">
					<i class="fas fa-info-circle"></i>
				</div>
				<div class="info-area p-3 mb-3 bg-grey-100 rounded" v-show="showHelp">
					<h2 class="text-center text-lg font-semibold mb-2">How the System Works</h2>
					<hr>
					<p>Welcome to the Task Management System. Here's how you can use it:</p>
					<hr class="mb-3">
					<ul class="list-disc pl-5">
						<li><strong>Add Task:</strong> Fill in the title (mendatory) and description, then click "Add Task" to create a new task.</li>
						<li><strong>Edit Task:</strong> Click <i class="fas fa-edit"></i> to modify any existing task. Make your changes and click <i class="fas fa-save"></i> to save.</li>
						<li><strong>Complete Task:</strong> Click <i class="fas fa-check"></i> to mark a task as completed.</li>
						<li><strong>Delete Task:</strong> Click <i class="fas fa-trash"></i> to remove a task from the list.</li>
						<li><strong>Hide Task:</strong> Click <i class="fa-solid fa-eye-slash"></i> to hide completed tasks from the list, and <i class="fa-solid fa-eye"></i> to show them again.</li>
					</ul>
				</div>
			</div>
			<div v-if="successMessage" class="alert bg-green-100 text-center text-green-800 p-4 rounded mb-4 w-[30%]"> 
				<i class="fa-solid fa-pen-ruler pr-12"></i>
				{{ successMessage }}
			</div>
			<div v-if="errorMessage" class="alert alert-danger bg-red-100 text-center text-red-800 p-4 rounded mb-4 w-[30%]">
				<i class="fa-solid fa-circle-exclamation pr-12"></i>
				{{ errorMessage }}
			</div>
			<div class="w-full">
				<div class="task-list-container w-full h-full">
					<h1 class="text-center text-2xl font-bold mb-4 text-4xl"><i class="fa-solid fa-lines-leaning "></i>  Task Manager </h1>

					<form @submit.prevent="addTask" class="form-group mb-4">
						<label for="title" class="block text-gray-700 font-bold mb-2">Title:</label>
						<input type="text" v-model="newTask.title" class="form-control w-full p-2 border border-gray-300 rounded mb-4" id="title" name="title" placeholder="Enter Task Title">
						<label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
						<textarea v-model="newTask.description" class="form-control w-full p-2 border border-gray-300 rounded mb-4" id="description" name="description" placeholder="Enter Task Description"></textarea>
						<button type="submit" :class="['btn', 'btn-primary', 'bg-blue-600', 'text-white', 'py-2', 'px-4', 'rounded', {'opacity-50 cursor-not-allowed': !newTask.title}]">Add Task</button>
					</form>
					<h1 class="text-center text-4xl font-bold"><i class="fa-solid fa-cubes"></i>  Task List </h1>
					<h3>	<i @click="toogleHideCompleted" :class="isCompletedHidden ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i> Completed tasks</h3>
					<table class="table-auto w-full border-collapse border border-gray-300">
						<thead>
							<tr class="bg-gray-200">
								<th class="border border-gray-300 p-2 w-1/6">Title</th>
								<th class="border border-gray-300 p-2 w-1/2">Description</th>
								<th class="border border-gray-300 p-2 w-1/10">Status</th>
								<th class="border border-gray-300 p-2 w-1/5">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="task in filteredTasks" :key="task.id" class="bg-white h-[100px]">
								<td v-if="!task.editing" class="border border-gray-300 p-2 overflow-x-auto whitespace-nowrap">{{ task.title }}</td>
								<td v-if="!task.editing" class="border border-gray-300 p-2 overflow-x-auto whitespace-nowrap">
									<span v-if="!task.description" class="text-gray-500">No Description</span>
									<span v-else>{{ task.description }}</span>
								</td>
								<td v-if="task.editing" class="border border-gray-300 p-2">
									<input type="text" v-model="task.title" required class="form-control w-full p-2 border border-gray-300 rounded">
								</td>
								<td v-if="task.editing" class="border border-gray-300 p-2">
									<textarea v-model="task.description" required class="form-control w-full p-2 border border-gray-300 rounded"></textarea>	
								</td>
								<td class="border border-gray-300 p-2">
									<span v-if="task.completed" class="badge bg-green-500 text-white py-1 px-2 rounded">Completed</span>
									<span v-else class="badge bg-yellow-500 text-white py-1 px-2 rounded">On Going</span>
								</td>
								<td class="border border-gray-300 p-2">
									<div class="action-icons flex justify-center space-x-2">
										<i v-if="!task.completed" @click="markAsComplete(task)" class="fas fa-check text-green-500 cursor-pointer"></i>
										<i v-if="!task.editing && !task.completed" @click="editTask(task)" class="fas fa-edit text-blue-500 cursor-pointer"></i>
										<i v-if="task.editing" @click="updateTask(task)" :class="['fas', 'fa-save', 'text-blue-500', { 'opacity-50 cursor-not-allowed': !task.title }]"></i>
										<i v-if="task.editing" @click="cancelEditTask(task)" class="fas fa-times text-gray-500 cursor-pointer"></i>
										<i @click="deleteTask(task.id)" class="fas fa-trash text-red-500 cursor-pointer"></i>
									</div>
								</td>
							</tr>
							<tr v-if="tasks.length === 0">
								<td colspan="4" class="text-center p-4">No Task Found</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
</AuthenticatedLayout>
</template>
