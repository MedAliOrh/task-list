<script>
import axios from "axios";

export default {
	data() {
		return {
			tasks: [],
			newTask: {
				title: "",
				description: "",
			},
			successMessage: "",
			errorMessage: "",
		};
	},
	mounted() {
		this.getTasks();
	},
	methods: {
		getTasks() {
			axios.get("/tasks").then((response) => {
				this.tasks = response.data;
			});
		},
		addTask() {
			axios
				.post("/tasks", this.newTask)
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
			axios.delete(`/tasks/${taskId}`).then((response) => {
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
			axios.put(`/tasks/${task.id}`, {
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
			axios.put(`/tasks/${task.id}/complete`).then((response) => {
				task.completed = true;
				this.successMessage = "Task Completed Successfully";
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
			});
		},
	},
}
</script>
<template>
	<div>
		<h1>Task List :</h1>
		<div v-if="successMessage" class="alert alert-success">
			{{ successMessage }}
			{{ errorMessage }}
		</div>

		<form @submit.prevent="addTask" class="form-group">
			<label for="title">Title:</label>
			<input type="text" v-model="newTask.title" class="form-control" id="title" name="title" placeholder="Enter Task Title">
			<label for="description">Description:</label>
			<textarea v-model="newTask.description" class="form-control" id="description" name="description" placeholder="Enter Task Description"></textarea>
			<button @click.prevent="addTask" type="submit" class="btn btn-primary">Add Task</button>
		</form>

		<table class="table table-bordered mt-3">
			<thead>
				<tr>
					<th>Title</th>
					<th>Description</th>
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
						<button v-if="!task.completed" @click="markAsComplete(task)" class="btn btn-primary">Complete</button>
						<button v-if="!task.editing" @click="editTask(task)" class="btn btn-info">Edit</button>
						<button v-if="task.editing" @click="updateTask(task)" class="btn btn-success">Update</button>
						<button v-if="task.editing" @click="cancelEditTask(task)" class="btn btn-secondary">Cancel</button>
						<button @click="deleteTask(task.id)" class="btn btn-danger">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

