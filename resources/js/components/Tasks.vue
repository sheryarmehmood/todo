<template>
  <div class="page-container">
    <div class="todo-container">
      <h1 class="title">ToDo List!</h1>
      <form @submit.prevent="addTask" class="todo-form">
        <input
          v-model="newTask"
          type="text"
          placeholder="Add your new ToDo..."
          class="todo-input"
        />
        <button type="submit" class="add-button">+</button>
      </form>
      <ul class="todo-list">
        <li v-for="task in tasks" :key="task.id" class="todo-item">
          <span :class="{ completed: task.is_completed }">{{ task.description }}</span>
          <div class="todo-actions">
            <button class="complete-button" @click="toggleTask(task)">✔</button>
            <button class="edit-button" @click="editTask(task)">✎</button>
            <button class="delete-button" @click="deleteTask(task.id)">🗑</button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Tasks",
  data() {
    return {
      tasks: [],
      newTask: "",
    };
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await axios.get("/api/tasks");
        this.tasks = response.data;
      } catch (error) {
        console.error("Error fetching tasks:", error);
      }
    },
    async addTask() {
      if (!this.newTask.trim()) return;
      try {
        const response = await axios.post("/api/tasks", { description: this.newTask });
        this.tasks.push(response.data);
        this.newTask = "";
      } catch (error) {
        console.error("Error adding task:", error);
      }
    },
    async toggleTask(task) {
      try {
        const response = await axios.put(`/api/tasks/${task.id}`);
        task.is_completed = response.data.is_completed;
      } catch (error) {
        console.error("Error toggling task:", error);
      }
    },
    async deleteTask(id) {
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter((task) => task.id !== id);
      } catch (error) {
        console.error("Error deleting task:", error);
      }
    },
    editTask(task) {
      this.newTask = task.description;
      this.deleteTask(task.id);
    },
  },
  mounted() {
    this.fetchTasks();
  },
};
</script>

<style scoped>
/* Full Page Green Background */
.page-container {
  background-color: #1abc9c; /* Green background */
  min-height: 100vh; /* Full height of the viewport */
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  box-sizing: border-box;
}

/* To-Do List Container */
.todo-container {
  background-color: #ecf0f1; /* Light grey background */
  padding: 30px;
  border-radius: 15px;
  width: 400px;
  text-align: center;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

/* Title */
.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
  color: #2c3e50; /* Dark text */
}

/* To-Do Form */
.todo-form {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.todo-input {
  padding: 10px;
  flex: 1;
  border: 1px solid #bdc3c7;
  border-radius: 5px;
  font-size: 16px;
  margin-right: 10px;
}

.add-button {
  background-color: #8e44ad; /* Purple button */
  color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  font-size: 18px;
  cursor: pointer;
}

.add-button:hover {
  background-color: #732d91;
}

/* To-Do List */
.todo-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.todo-item {
  background-color: #ffffff; /* White background for items */
  padding: 10px;
  border-radius: 5px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

.todo-item .completed {
  text-decoration: line-through;
  color: #7f8c8d; /* Grey color for completed tasks */
}

/* To-Do Actions */
.todo-actions button {
  background-color: transparent;
  border: none;
  font-size: 18px;
  cursor: pointer;
  margin-left: 5px;
}

.complete-button {
  color: #27ae60; /* Green for complete */
}

.edit-button {
  color: #f1c40f; /* Yellow for edit */
}

.delete-button {
  color: #e74c3c; /* Red for delete */
}

.complete-button:hover {
  color: #219150;
}

.edit-button:hover {
  color: #d4ac0d;
}

.delete-button:hover {
  color: #c0392b;
}
</style>
