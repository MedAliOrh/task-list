<script>
import axios from "axios";
import '../../css/tasks.css';

export default {
	name: "TaskList",
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
		};
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
	},
}
</script>

<template>
	<div class="container mt-5">
		<div class="row">
			<div class="col-md-4">
				<div class="info-icon" @click="toggleHelp">
					<i class="fas fa-info-circle"></i>
				</div>
				<div class="info-area p-3 mb-3 bg-light rounded" v-show="showHelp">
					<h2 class="text-center mt-2 mb-2 fs-4">How the System Works</h2>
					<hr>
					<p>Welcome to the Task Management System. Here's how you can use it:</p>
					<hr class="mb-3">
					<ul>
						<li><strong>Add Task:</strong> Fill in the title (mendatory) and description, then click "Add Task" to create a new task.</li>
						<li><strong>Edit Task:</strong> Click <i class="fas fa-edit"></i> to modify any existing task. Make your changes and click <i class="fas fa-save"></i> again to save.</li>
						<li><strong>Complete Task:</strong> Click <i class="fas fa-check"></i> to mark a task as completed.</li>
						<li><strong>Delete Task:</strong> Click <i class="fas fa-trash"></i> to remove a task from the list.</li>
					</ul>
				</div>
			</div>
			<div class="col-md-8">
				<div class="task-list-container">
					<h1 class="text-center mt-3 fs-1"><i class="fa-solid fa-lines-leaning"></i>  Task Manager </h1>
					<div v-if="successMessage" class="alert alert-success">
						{{ successMessage }}
						{{ errorMessage }}
					</div>

					<form @submit.prevent="addTask" class="form-group">
						<label for="title">Title:</label>
						<input type="text" v-model="newTask.title" class="form-control" id="title" name="title" placeholder="Enter Task Title">
						<label for="description">Description:</label>
						<textarea v-model="newTask.description" class="form-control" id="description" name="description" placeholder="Enter Task Description"></textarea>
						<button type="submit" class="btn btn-primary" :disabled="!newTask.title">Add Task</button>
					</form>
					<h1 class="text-center mt-3 fs-1"><i class="fa-solid fa-cubes"></i>  Task List </h1>
					<table class="table table-bordered mt-3">
						<thead>
							<tr>
								<th>Title</th>
								<th>Description</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="task in tasks" :key="task.id">
								<td v-if="!task.editing">{{ task.title }}</td>
								<td v-if="!task.editing">{{ task.description }}</td>
								<td v-if="task.editing">
									<input type="text" v-model="task.title" required class="form-control">
								</td>
								<td v-if="task.editing">
									<textarea v-model="task.description" required class="form-control"></textarea>	
								</td>
								<td>
									<span v-if="task.completed" class="badge bg-success">Completed</span>
									<span v-else class="badge bg-warning">On Going</span>
								</td>
								<td>
									<div class="action-icons">
										<i v-if="!task.completed" @click="markAsComplete(task)" class="fas fa-check text-success"></i>
										<i v-if="!task.editing && !task.completed" @click="editTask(task)" class="fas fa-edit text-info"></i>
										<i v-if="task.editing" @click="updateTask(task)" :class="['fas', 'fa-save', 'text-success', { 'disabled': !task.title }]"></i>
										<i v-if="task.editing" @click="cancelEditTask(task)" class="fas fa-times text-secondary"></i>
										<i @click="deleteTask(task.id)" class="fas fa-trash text-danger"></i>
									</div>
								</td>
							</tr>
							<tr v-if="tasks.length === 0">
								<td colspan="3" class="text-center">No Task Found</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>

