<template>
  <div class="book-list">
    <div class="book-list-header">
      <h2>📚 Books Collection</h2>
      <button 
        @click="handleRefresh" 
        :disabled="loading"
        class="refresh-btn"
        :class="{ 'loading': loading }"
      >
        <span class="refresh-icon">🔄</span>
        {{ loading ? 'Loading...' : 'Refresh' }}
      </button>
    </div>

    <!-- Error State -->
    <div v-if="error" class="error-message">
      <div class="error-content">
        <span class="error-icon">⚠️</span>
        <div>
          <strong>Error loading books:</strong>
          <p>{{ error }}</p>
        </div>
      </div>
      <button @click="clearError" class="error-dismiss">×</button>
    </div>

    <!-- Loading State -->
    <div v-if="loading && !books.length" class="loading-state">
      <div class="loading-spinner"></div>
      <p>Loading books...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading && !books.length && !error" class="empty-state">
      <div class="empty-icon">📖</div>
      <h3>No books found</h3>
      <p>The library appears to be empty. Try refreshing or check if the API is running.</p>
      <button @click="handleRefresh" class="btn btn-primary">
        Load Books
      </button>
    </div>

    <!-- Books Grid -->
    <div v-else class="books-grid">
      <div 
        v-for="book in books" 
        :key="book.id" 
        class="book-card"
      >
        <div class="book-info">
          <h3 class="book-title">{{ book.title }}</h3>
          <p class="book-author">by {{ book.author }}</p>
          <div class="book-meta">
            <span class="book-year">
              <span class="meta-icon">📅</span>
              {{ book.published_year }}
            </span>
            <span class="book-rating">
              <span class="meta-icon">⭐</span>
              <span v-if="book.average_rating">
                {{ formatRating(book.average_rating) }}
              </span>
              <span v-else class="no-rating">No reviews</span>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Books Count -->
    <div v-if="books.length > 0" class="books-count">
      Showing {{ books.length }} book{{ books.length !== 1 ? 's' : '' }}
    </div>
  </div>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { useBooks } from '../composables/useBooks.js'

// Use the books composable
const { books, loading, error, loadBooks, refreshBooks, clearError } = useBooks()

// Load books when component is mounted
onMounted(() => {
  console.log('Component mounted, books length:', books.value.length)
  if (books.value.length === 0) {
    console.log('Loading books...')
    loadBooks()
  }
})

// Handle refresh button click
const handleRefresh = () => {
  refreshBooks()
}

// Format rating to show one decimal place
const formatRating = (rating) => {
  return parseFloat(rating).toFixed(1)
}
</script>

<style scoped>
.book-list {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.book-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.book-list-header h2 {
  margin: 0;
  color: #333;
  font-size: 2rem;
}

.refresh-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.2s;
}

.refresh-btn:hover:not(:disabled) {
  background: #0056b3;
  transform: translateY(-1px);
}

.refresh-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.refresh-btn.loading .refresh-icon {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Error State */
.error-message {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  background: #f8d7da;
  color: #721c24;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #f5c6cb;
  margin-bottom: 2rem;
}

.error-content {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
}

.error-icon {
  font-size: 1.25rem;
  flex-shrink: 0;
}

.error-dismiss {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #721c24;
  padding: 0;
  line-height: 1;
}

/* Loading State */
.loading-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c757d;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c757d;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-state h3 {
  margin-bottom: 0.5rem;
  color: #333;
}

.empty-state p {
  margin-bottom: 2rem;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

/* Books Grid */
.books-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.book-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border: 1px solid #e9ecef;
  transition: all 0.2s;
}

.book-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.book-info {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.book-title {
  margin: 0 0 0.5rem 0;
  color: #333;
  font-size: 1.25rem;
  line-height: 1.3;
  font-weight: 600;
}

.book-author {
  margin: 0 0 1rem 0;
  color: #6c757d;
  font-style: italic;
  font-size: 1rem;
}

.book-meta {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e9ecef;
  font-size: 0.9rem;
}

.book-year, .book-rating {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #495057;
}

.meta-icon {
  font-size: 0.85rem;
}

.no-rating {
  color: #adb5bd;
  font-style: italic;
}

.books-count {
  text-align: center;
  color: #6c757d;
  font-size: 0.9rem;
  padding: 1rem;
  border-top: 1px solid #e9ecef;
}

/* Button Styles */
.btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
  .book-list {
    padding: 1rem 0.5rem;
  }
  
  .book-list-header {
    flex-direction: column;
    align-items: stretch;
  }
  
  .book-list-header h2 {
    text-align: center;
    font-size: 1.75rem;
  }
  
  .books-grid {
    grid-template-columns: 1fr;
  }
  
  .book-meta {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}

@media (max-width: 480px) {
  .book-card {
    padding: 1rem;
  }
  
  .refresh-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
