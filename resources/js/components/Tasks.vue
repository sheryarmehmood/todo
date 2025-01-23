<template>
  <div class="page-container">
    <div class="todo-container">
      <h1 class="title">ToDo List!</h1>

      <!-- Notification Section -->
      <div v-if="message.text" :class="`notification ${message.type}`">
        {{ message.text }}
      </div>

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
            <button class="complete-button" @click="toggleTask(task)" title="Mark as completed">✔</button>
            <button class="delete-button" @click="deleteTask(task.id)" title="Delete task">🗑</button>
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
      message: {
        text: "",
        type: "", 
      },
    };
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await axios.get("/api/tasks");
        this.tasks = response.data;
        this.showMessage("Tasks fetched successfully!", "success");
      } catch (error) {
        this.showMessage("Error fetching tasks.", "error");
        console.error(error);
      }
    },
    async addTask() {
        if (!this.newTask.trim()) {
            this.showMessage("Task description cannot be empty.", "error");
            return;
        }
        try {
            const response = await axios.post("/api/tasks", { description: this.newTask });
            this.tasks.push(response.data);
            this.newTask = "";
            this.showMessage("Task added successfully!", "success");
        } catch (error) {
            if (error.response && error.response.status === 422) {
            // Handle validation errors
            const validationErrors = error.response.data.errors;
            console.log(validationErrors);
                if (validationErrors.description) {
                this.showMessage(validationErrors.description[0], "error"); // Display the first error
            }
            } else {
            this.showMessage("An unexpected error occurred while adding the task.", "error");
            }
        }
    },

    async toggleTask(task) {
      try {
        const response = await axios.put(`/api/tasks/${task.id}`);
        task.is_completed = response.data.is_completed;
        this.showMessage("Task status updated successfully!", "success");
      } catch (error) {
        this.showMessage("Error updating task status.", "error");
      }
    },
    async deleteTask(id) {
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter((task) => task.id !== id);
        this.showMessage("Task deleted successfully!", "success");
      } catch (error) {
        this.showMessage("Error deleting task.", "error");
      }
    },
    showMessage(text, type) {
      this.message.text = text;
      this.message.type = type;
      setTimeout(() => {
        this.message.text = "";
      }, 3000); // Hide message after 3 seconds
    },
  },
  mounted() {
    this.fetchTasks();
  },
};
</script>

<style scoped>
body{
    margin:0 !important;
}
/* Full Page Green Background */
.page-container {
    background: url('/images/sunset.jpg');
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
    background-position: center bottom;
    background-size: cover;
}

/* To-Do List Container */
.todo-container {
  padding: 30px;
  border-radius: 15px;
  width: 700px;
  text-align: center;
  background: rgba(255, 255, 255, 0.1);
    overflow: hidden;
    border-top: 1px solid rgb(255 255 255 / 23%);
    border-left: 1px solid rgb(255 255 255 / 19%);
    backdrop-filter: blur(10px);

}

/* Title */
.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
  color: #000/* Dark text */
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
        border: 1px solid #ffffff5c;
        border-radius: 5px;
        font-size: 16px;
        margin-right: 10px;
        background: transparent;
        outline: none;
        color: #ffffffe9;
  &::placeholder{
    color: #ffffff7c;
  }
}

.add-button{
    background-color: #ffffff33;
    color: white;
    border: none;
    border-radius: 10%;
    width: 45px;
    height: 50px;
    font-size: 18px;
    cursor: pointer;
    box-shadow: inset 0.2em 0.2em 0.2em 0 rgb(255 255 255 / 20%), inset -0.2em -0.2em 0.2em 0 rgb(0 0 0 / 20%);
}

.add-button:hover {
  background-color: #00000026;
}

/* To-Do List */
.todo-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.todo-item {
  padding: 15px;
  border-radius: 5px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
        border-radius: 5px;
        font-size: 16px;
        margin-right: 10px;
        background: #ffffff33;
        outline: none;
        color: #ffffffe9;
        box-shadow: inset 0.2em 0.2em 0.2em 0 rgb(255 255 255 / 20%), inset -0.2em -0.2em 0.2em 0 rgb(0 0 0 / 20%) ;
        &:hover{
            background: #00000026;
        }
}

.todo-item .completed {
  text-decoration: line-through;
  color: #000;
}

/* To-Do Actions */
.todo-actions {
  width: 60px; /* Set a fixed width */
  display: flex;
}

.todo-actions button {
    background-color: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    margin-left: 0px;
}

.complete-button {
  color: #27ae60; 
}

.delete-button {
    color: #ff1800;
}

.complete-button:hover {
  color: #219150;
}

.delete-button:hover {
  color: #c0392b;
}

/* Notifications */
.notification {
  margin-bottom: 20px;
  padding: 10px;
  border-radius: 5px;
  text-align: center;
  font-weight: bold;
}

.notification.success {
  color: white;
background: rgba(0, 0, 0, 0.44);
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(12px);
-webkit-backdrop-filter: blur(12px);
}

.notification.error {
  background-color: #e74c3c; 
  color: white;
}
</style>
