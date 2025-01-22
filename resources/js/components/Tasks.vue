<template>
  <div class="tasks">
    <h1>To-Do List</h1>
    <form @submit.prevent="addTask" class="task-form">
      <input v-model="newTask" placeholder="Enter a task" />
      <button type="submit">Add</button>
    </form>
    <ul class="task-list">
      <li v-for="task in tasks" :key="task.id">
        <span
          :class="{ completed: task.is_completed }"
          @click="toggleTask(task)"
        >
          {{ task.description }}
        </span>
        <button @click="deleteTask(task.id)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Tasks',
  data() {
    return {
      tasks: [],
      newTask: '',
    };
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await axios.get('/api/tasks');
        this.tasks = response.data;
      } catch (error) {
        console.error('Error fetching tasks:', error);
      }
    },
    async addTask() {
      if (!this.newTask.trim()) return;
      try {
        const response = await axios.post('/api/tasks', {
          description: this.newTask,
        });
        this.tasks.push(response.data);
        this.newTask = '';
      } catch (error) {
        console.error('Error adding task:', error);
      }
    },
    async toggleTask(task) {
      try {
        const response = await axios.put(`/api/tasks/${task.id}`);
        task.is_completed = response.data.is_completed;
      } catch (error) {
        console.error('Error toggling task:', error);
      }
    },
    async deleteTask(id) {
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter((task) => task.id !== id);
      } catch (error) {
        console.error('Error deleting task:', error);
      }
    },
  },
  mounted() {
    this.fetchTasks();
  },
};
</script>

<style>
.tasks {
  margin: 20px;
}
.task-form {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}
.task-form input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.task-form button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.task-form button:hover {
  background-color: #0056b3;
}
.task-list {
  list-style: none;
  padding: 0;
}
.task-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 10px;
}
.task-list li .completed {
  text-decoration: line-through;
  color: #999;
  cursor: pointer;
}
.task-list li span {
  cursor: pointer;
}
.task-list li button {
  background-color: #dc3545;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
}
.task-list li button:hover {
  background-color: #c82333;
}
</style>
