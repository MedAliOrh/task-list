// Code for TaskList.vue component
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

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
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
				this.successMessage = "";
			});
		},
		addTask() {
			if (this.isTitleDuplicated(this.newTask.title)) {
				this.errorMessage = "Task title already exists";
				this.successMessage = "";
				return;
			}
			axios
				.post("/api/tasks", this.newTask)
				.then((response) => {
					this.tasks.push(response.data);
					this.newTask.title = "";
					this.newTask.description = "";
					this.successMessage = "Task Added Successfully";
					this.errorMessage = "";
				})
				.catch((error) => {
					this.errorMessage = "Something went wrong: " + error;
					this.successMessage = "";
				});
		},
		deleteTask(taskId) {
			axios.delete(`/api/tasks/${taskId}`).then((response) => {
				this.tasks = this.tasks.filter((task) => task.id !== taskId);
				this.successMessage = "Task Deleted Successfully";
				this.errorMessage = "";
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
				this.successMessage = "";
			});
		},
		editTask(task) {
			task.editing = true;
		},
		updateTask(task) {
			if (this.isTitleDuplicated(task.title, task.id)) {
				this.errorMessage = "Task title already exists";
				this.successMessage = "";
				return;
			}
			axios.put(`/api/tasks/${task.id}`, {
				title: task.title,
				description: task.description,
				completed: task.completed,
			}).then((response) => {
				this.successMessage = "Task Updated Successfully";
				this.errorMessage = "";
				task.editing = false;
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
				this.successMessage = "";
			});
		},
		isTitleDuplicated(title, id = null) {
			return this.tasks.some((task) => task.title === title && task.id !== id);
		},
		cancelEditTask(task) {
			task.editing = false;
			this.getTasks();
		},
		markAsComplete(task) {
			axios.put(`/api/tasks/${task.id}/complete`).then((response) => {
				task.completed = true;
				this.successMessage = "Task Completed Successfully";
				this.errorMessage = "";
			})
			.catch((error) => {
				this.errorMessage = "Something went wrong: " + error;
				this.successMessage = "";
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
