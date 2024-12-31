<script>
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import '../../css/tasks.css';

export default {
	name: "TaskList",
	components: {
		AuthenticatedLayout,
		Head,
	},
	data() {
		return {
			tasks: [],
			newTask: {
				title: "",
				description: "",
			},
			successMessage: "",
			errorMessage: "",
			showHelp: false,
			isCompletedHidden: false,
		};
	},
	computed: {
		filteredTasks() {
			if (this.isCompletedHidden) {
				return this.tasks.filter((task) => !task.completed);
			}
			return this.tasks;
		},
	},
	mounted() {
		this.getTasks();
	},
	methods: {
		getTasks() {
			axios.get("/api/tasks").then((response) => {
				this.tasks = response.data;
			});
		},
		addTask() {
			axios
				.post("/api/tasks", this.newTask)
				.then((response) => {
					this.tasks.push(response.data);
					this.newTask.title = "";
					this.newTask.description = "";
					this.successMessage = "Task Added Successfully";
				})
				.catch((error) => {
					this.errorMessage = "Something went wrong: " + error;
				});
		},
		deleteTask(taskId) {
			axios.delete(`/api/tasks/${taskId}`).then((response) => {
				this.tasks = this.tasks.filter((task) => task.id !== taskId);
				this.successMessage = "Task Deleted Successfully";
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
			});
		},
		editTask(task) {
			task.editing = true;
		},
		updateTask(task) {
			axios.put(`/api/tasks/${task.id}`, {
				title: task.title,
				description: task.description,
				completed: task.completed,
			}).then((response) => {
				this.successMessage = "Task Updated Successfully";
				task.editing = false;
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
			});
		},
		cancelEditTask(task) {
			task.editing = false;
			this.getTasks();
		},
		markAsComplete(task) {
			axios.put(`/api/tasks/${task.id}/complete`).then((response) => {
				task.completed = true;
				this.successMessage = "Task Completed Successfully";
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
			});
		},
		toggleHelp() {
			this.showHelp = !this.showHelp;
		},
		toogleHideCompleted() {
			this.isCompletedHidden = !this.isCompletedHidden;
		},
	},
}
</script>

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
						<li><strong>Edit Task:</strong> Click <i class="fas fa-edit"></i> to modify any existing task. Make your changes and click <i class="fas fa-save"></i> again to save.</li>
						<li><strong>Complete Task:</strong> Click <i class="fas fa-check"></i> to mark a task as completed.</li>
						<li><strong>Delete Task:</strong> Click <i class="fas fa-trash"></i> to remove a task from the list.</li>
						<li><strong>Hide Task:</strong> Click <i class="fa-solid fa-eye-slash"></i> to hide completed tasks from the list, and <i class="fa-solid fa-eye"></i> to show them again.</li>
					</ul>
				</div>
			</div>
			<div class="w-full md:w-2/3 p-4">
				<div class="task-list-container">
					<h1 class="text-center text-2xl font-bold mb-4"><i class="fa-solid fa-lines-leaning"></i>  Task Manager </h1>
					<div v-if="successMessage" class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4"> 
						{{ successMessage }}
					</div>
					<div v-if="errorMessage" class="alert alert-danger bg-red-100 text-red-800 p-4 rounded mb-4">
						{{ errorMessage }}
					</div>

					<form @submit.prevent="addTask" class="form-group mb-4">
						<label for="title" class="block text-gray-700 font-bold mb-2">Title:</label>
						<input type="text" v-model="newTask.title" class="form-control w-full p-2 border border-gray-300 rounded mb-4" id="title" name="title" placeholder="Enter Task Title">
						<label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
						<textarea v-model="newTask.description" class="form-control w-full p-2 border border-gray-300 rounded mb-4" id="description" name="description" placeholder="Enter Task Description"></textarea>
						<button type="submit" class="btn btn-primary bg-blue-600 text-white py-2 px-4 rounded" :disabled="!newTask.title">Add Task</button>
					</form>
					<h1 class="text-center text-4xl font-bold"><i class="fa-solid fa-cubes"></i>  Task List 	<i @click="toogleHideCompleted" :class="isCompletedHidden ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i></h1>
					<table class="table-auto w-full border-collapse border border-gray-300">
						<thead>
							<tr class="bg-gray-200">
								<th class="border border-gray-300 p-2">Title</th>
								<th class="border border-gray-300 p-2">Description</th>
								<th class="border border-gray-300 p-2">Status</th>
								<th class="border border-gray-300 p-2">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="task in filteredTasks" :key="task.id">
								<td v-if="!task.editing" class="border border-gray-300 p-2">{{ task.title }}</td>
								<td v-if="!task.editing" class="border border-gray-300 p-2">{{ task.description }}</td>
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

<style scoped>
body {
  font-family: Arial, sans-serif;
}

.task-list-container {
  max-width: 800px;
  margin: 50px auto;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.task-list-container h1 {
  text-align: center;
  margin-bottom: 20px;
}

.task-list-container .form-group {
  margin-bottom: 20px;
}

.task-list-container .form-control {
  margin-bottom: 10px;
}


.task-list-container .btn {
  width: 100%;
}

.task-list-container table {
  width: 100%;
  margin-top: 20px;
}

.task-list-container th,
.task-list-container td {
  text-align: center;
  vertical-align: middle;
}

.task-list-container .badge {
  padding: 10px;
  font-size: 14px;
}

.task-list-container .alert {
  margin-top: 20px;
}

.alert {
	margin-top: 20px;
}

.alert-success {
	padding: 10px;
	background-color: #d4edda;
	border-color: #c3e6cb;
	color: #155724;
}

.info-icon {
  display: inline-block;
  cursor: pointer;
  font-size: 24px;
  color: #007bff;
  margin-bottom: 10px;
}

.info-area {
  background-color: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}

.info-area h2 {
  margin-top: 0;
}

.info-area ul {
  padding-left: 20px;
}

.info-area ul li {
  margin-bottom: 10px;
}

.action-icons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.action-icons i {
  cursor: pointer;
  font-size: 24px;
  padding: 10px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.action-icons i.disabled {
  pointer-events: none;
  opacity: 0.5;
}

.task-list-container label {
  display: inline-block;
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 5px;
  padding: 5px;
  border-radius: 4px;
}
</style>