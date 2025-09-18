import { ref, reactive, computed } from 'vue'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8080/api'

const state = reactive({
  books: [],
  loading: false,
  error: null
})

export function useBooks() {
  // Create computed references to the reactive state
  const books = computed(() => state.books)
  const loading = computed(() => state.loading)
  const error = computed(() => state.error)

  // Function to load books from the API
  const loadBooks = async () => {
    state.loading = true
    state.error = null

    try {
      const response = await axios.get(`${API_BASE_URL}/books`)
      
      if (response.data && Array.isArray(response.data)) {
        state.books = response.data
        console.log('Books loaded:', state.books.length, 'books')
      } else {
        console.warn('Unexpected API response structure:', response.data)
        state.books = []
      }
    } catch (err) {
      if (err.response) {
        state.error = `Server error: ${err.response.status} - ${err.response.data?.message || err.response.statusText}`
      } else if (err.request) {
        state.error = 'Network error: Unable to connect to the server. Please check if the API is running.'
      } else {
        state.error = `Error: ${err.message}`
      }
      
      console.error('Error loading books:', err)
      state.books = []
    } finally {
      state.loading = false
    }
  }

  const refreshBooks = () => {
    return loadBooks()
  }

  const clearError = () => {
    state.error = null
  }

  return {
    books,
    loading,
    error,
    loadBooks,
    refreshBooks,
    clearError
  }
}
