<template>
  <div id="app">
    <header class="header">
      <h1>📚 Books Project</h1>
      <p>A modern book management system</p>
    </header>

    <main class="main">
      <!-- Books List Component -->
      <BookList />
    </main>

    <footer class="footer">
      <p>&copy; 2025 Books Project. Built with Vue 3 + Vite</p>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import BookList from './components/BookList.vue'

onMounted(() => {
  console.log('Books Project Frontend is ready!')
})
</script>

<style scoped>
.header {
  text-align: center;
  padding: 2rem 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  margin-bottom: 0;
}

.header h1 {
  margin: 0;
  font-size: 2.5rem;
}

.header p {
  margin: 0.5rem 0 0 0;
  opacity: 0.9;
}

.main {
  flex: 1;
  background: #f5f5f5;
}

.footer {
  text-align: center;
  padding: 2rem;
  background: #f8f9fa;
  border-top: 1px solid #e9ecef;
  color: #6c757d;
}
</style>
