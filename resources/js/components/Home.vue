<template>
  <div class="home">
    <h1>Welcome to the To-Do App</h1>
    <p>Organize your tasks efficiently.</p>
    <router-link to="/tasks" class="btn">Go to Tasks</router-link>
  </div>
</template>

<script>
export default {
  name: 'Home',
};
</script>

<style>
.home {
  text-align: center;
  margin-top: 50px;
}
.btn {
  display: inline-block;
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
}
.btn:hover {
  background-color: #0056b3;
}
</style>
