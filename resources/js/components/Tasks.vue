<template>
  <div class="page-container">
    <div class="todo-container">
      <h1 class="title">ToDo List!</h1>


      <!-- Notification Section -->
      <div v-if="message.text" :class="`notification ${message.type}`">
        {{ message.text }}
      </div>


       <!-- Add Task Button -->
       <button @click="openAddModal" class="add-button">
        <i class="fa-solid fa-plus"></i>
       </button>


     
      <ul class="todo-list">
       
        <li v-for="task in tasks" :key="task.id" class="todo-item">
          <span :class="{ completed: task.is_completed }">{{ task.description }}</span>
          <div class="todo-actions">
            <button
              class="complete-button"
              @click="toggleTask(task)"
              :title="task.is_completed ? 'Mark as incomplete' : 'Mark as completed'"
            >
            <i :class="task.is_completed ? 'fas fa-undo' : 'fas fa-check'"></i>
            </button>
            <button
                      class="view-button"
                      @click="openViewModal(task)"
                      title="View task"
                    >
                    <i class="fa-regular fa-eye"></i>
            </button>
            <button 
                      class="delete-button" 
                      @click="deleteTask(task.id)" 
                      title="Delete task"
                      >
                      <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </li>


      </ul>


    <!-- Add Task Modal -->
    <div v-if="showAddModal" class="modal">
      <div class="modal-content">
        <h2>Add Task</h2>
        <div v-if="validationmessage.text" :class="`notification ${validationmessage.type}`">
        {{ validationmessage.text }}
      </div>
        <form @submit.prevent="addTask">
          <input
            v-model="newTask.title"
            type="text"
            placeholder="Task Title"
            required
          />
          <textarea
            v-model="newTask.description"
            placeholder="Task Description"
            required
          ></textarea>
          <div class="modal-buttons">
            <button type="submit" class="add-task-button">Add Task</button>
            <button type="button" @click="closeAddModal" class="cancel-button">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>


      <!-- View Task Modal -->
    <div v-if="showViewModal" class="modal">
      <div class="modal-content">
        <h2>Task Details</h2>
        <p><strong>Title:</strong> {{ currentTask.title }}</p>
        <p><strong>Description:</strong> {{ currentTask.description }}</p>
        <button type="button" @click="closeViewModal" class="close-button">
          Close
        </button>
      </div>
    </div>
    </div>
  </div>
</template>



<script>
import axios from "axios";
import '@fortawesome/fontawesome-free/css/all.css';

export default {
  name: "Tasks",
  data() {
     return {
      tasks: [],
      newTask: { title: "", description: "" },
      currentTask: {},
      showAddModal: false,
      showViewModal: false,
      message: {
        text: "",
        type: "",
      },
      validationmessage: {
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
      if (!this.newTask.title || !this.newTask.description) {
        this.showMessage("All fields are required.", "error");
        return;
      }
      try {
        const response = await axios.post("/api/tasks", this.newTask);
        this.tasks.push(response.data);
        this.newTask = { title: "", description: "" };
        this.closeAddModal();
        this.showMessage("Task added successfully!", "success");
      } catch (error) {
            if (error.response && error.response.status === 422) { 
              // this.closeAddModal();
            // Handle validation errors
            const validationErrors = error.response.data.errors;
            console.log(validationErrors);
            if (validationErrors.title) {
        this.showValidationMessage(validationErrors.title[0], "error"); // Display title error
      } else if (validationErrors.description) {
        this.showValidationMessage(validationErrors.description[0], "error"); // Display description error
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
    showValidationMessage(text, type) {
      this.validationmessage.text = text;
      this.validationmessage.type = type;
      setTimeout(() => {
        this.validationmessage.text = "";
      }, 3000); // Hide message after 3 seconds
    },
    openAddModal() {
      this.showAddModal = true;
    },
    closeAddModal() {
      this.showAddModal = false;
      this.newTask = { title: "", description: "" };
    },
    openViewModal(task) {
      this.currentTask = task;
      this.showViewModal = true;
    },
    closeViewModal() {
      this.showViewModal = false;
      this.currentTask = {};
    },
  },
  mounted() {
    this.fetchTasks();
  },
};
</script>


<style scoped>
body{
    margin: 0 !important;
    padding: 0;
    box-sizing: border-box;
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
  background: rgb(0 0 0 / 29%);
    overflow: hidden;
    border-top: 1px solid rgb(255 255 255 / 23%);
    border-left: 1px solid rgb(255 255 255 / 19%);
    backdrop-filter: blur(10px);


}


/* Title */
.title {
  font-family: "Roboto", serif;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
  color: #fff;
  display: inline-block;
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
        font-family: "Roboto", serif;
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
    font-family: "Roboto", serif;
    cursor: pointer;
    background-color: #28a745;
    box-shadow: inset 0.2em 0.2em 0.2em 0 rgb(255 255 255 / 20%), inset -0.2em -0.2em 0.2em 0 rgb(0 0 0 / 20%);
    position: absolute;
    top: 37px;
    right: 30px;
}


.add-button:hover {
  background-color: #218838;
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
        font-family: "Roboto", serif;
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
  font-weight: bold;
}


/* To-Do Actions */
.todo-actions {
  width: 100px; /* Set a fixed width */
  display: flex;
  justify-content: flex-end;
}


.todo-actions button {
    background-color: transparent;
    border: none;
    font-size: 15px;
    cursor: pointer;
    margin-left: 0px;
}


.complete-button {
  color: #fff;
}


.delete-button {
    color: #fff;
}

.view-button{
  color: #fff;
}


/* Notifications */
.notification {
  margin-bottom: 20px;
  padding: 10px;
  border-radius: 5px;
  text-align: center;
  font-weight: bold;
  font-family: "Roboto", serif;
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


/* Modal Styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  /* background: rgba(0, 0, 0, 0.7); */
  background: #ffffff33;
  display: flex;
  justify-content: center;
  align-items: center;
}


.modal-content {
  /* background: #fff; */
  /* background: #ffffff33; */
  background: #4d0000;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  text-align: center;
  color: #ffffffe9;
  font-family: "Roboto", serif;
}


.modal-content input,
.modal-content textarea {
  width: 95%;
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-family: "Roboto", serif;
}


.modal-buttons {
  display: flex;
  justify-content: space-between;
  font-family: "Roboto", serif;
}


.add-task-button {
  background: #0ca41f;
  color: white;
  border: none;
  padding: 10px;
  font-family: "Roboto", serif;
  border-radius: 5px;
}


.add-task-button:hover {
  cursor: pointer;
}


.cancel-button,
.close-button {
  background: #da1417;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  font-family: "Roboto", serif;
}


.cancel-button:hover {
  cursor: pointer;
} 


.close-button:hover {
  cursor: pointer;
}
</style>



