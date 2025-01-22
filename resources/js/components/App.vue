<template>
  <div>
    <nav>
      <router-link to="/">Home</router-link>
      <router-link to="/tasks">Tasks</router-link>
    </nav>
    <router-view></router-view> <!-- Where child components render -->
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style>
/* Basic Navigation Styling */
nav {
  display: flex;
  gap: 20px;
  padding: 10px;
  background-color: #f8f9fa;
  border-bottom: 1px solid #ddd;
}
nav a {
  text-decoration: none;
  color: #333;
  font-weight: bold;
}
nav a.router-link-active {
  color: #007bff;
}
</style>
