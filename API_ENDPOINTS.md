# API Endpoints - Books & Reviews

## 📚 Endpoints Implementados

Se han implementado exactamente los **2 endpoints** solicitados, eliminando todos los demás.

### ✅ **1. GET /api/books**

**Descripción:** Obtiene la lista completa de libros con su rating promedio calculado.

**URL:** `GET http://localhost:8080/api/books`

**Respuesta exitosa (200 OK):**
```json
[
  {
    "title": "El Arte de Programar",
    "author": "Donald Knuth",
    "published_year": 1968,
    "average_rating": 4.5
  },
  {
    "title": "Clean Code", 
    "author": "Robert C. Martin",
    "published_year": 2008,
    "average_rating": 3.5
  },
  {
    "title": "Refactoring",
    "author": "Martin Fowler", 
    "published_year": 1999,
    "average_rating": 2.5
  }
]
```

**Características:**
- ✅ Devuelve **title**, **author**, **published_year**
- ✅ Calcula **average_rating** usando `AVG()` en DQL/QueryBuilder
- ✅ Si un libro no tiene reseñas, devuelve `null` para average_rating
- ✅ Rating redondeado a 1 decimal para precisión

### ✅ **2. POST /api/reviews**

**Descripción:** Crea una nueva reseña para un libro.

**URL:** `POST http://localhost:8080/api/reviews`

**Cuerpo de la petición:**
```json
{
  "book_id": 1,
  "rating": 5,
  "comment": "Excelente libro"
}
```

**Validaciones implementadas:**
- ✅ **book_id**: Debe existir en la base de datos (400 si no existe)
- ✅ **rating**: Entero entre 1 y 5 (400 si no cumple)
- ✅ **comment**: No puede estar vacío (400 si está vacío)
- ✅ JSON válido requerido (400 si JSON inválido)

**Respuesta exitosa (201 Created):**
```json
{
  "id": 7,
  "created_at": "2025-09-18 15:30:45"
}
```

**Respuestas de error (400 Bad Request):**

**JSON inválido:**
```json
{
  "error": "Invalid JSON data"
}
```

**Campos faltantes:**
```json
{
  "error": "book_id is required"
}
```
```json
{
  "error": "rating is required"
}
```
```json
{
  "error": "comment is required"
}
```

**Rating inválido:**
```json
{
  "error": "rating must be an integer between 1 and 5"
}
```

**Comentario vacío:**
```json
{
  "error": "comment cannot be empty"
}
```

**Libro no existe:**
```json
{
  "error": "Book not found"
}
```

**Error de validación de entidad:**
```json
{
  "error": "Validation failed",
  "details": ["Comment cannot be blank"]
}
```

## 🧪 **Ejemplos de Uso**

### **Obtener todos los libros:**
```bash
curl -X GET http://localhost:8080/api/books
```

### **Crear una reseña válida:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 1,
    "rating": 5,
    "comment": "Excelente libro, muy recomendado para programadores"
  }'
```

### **Crear reseña con rating inválido (error):**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 1,
    "rating": 6,
    "comment": "Buen libro"
  }'
```

### **Crear reseña con libro inexistente (error):**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 999,
    "rating": 5,
    "comment": "Libro inexistente"
  }'
```

## 🏗️ **Implementación Técnica**

### **BookController:**
- ✅ Método único: `index()`
- ✅ Ruta: `GET /api/books`
- ✅ Usa `BookRepository::findAllWithAverageRating()`
- ✅ Respuesta JSON directa sin serialización compleja

### **ReviewController:**
- ✅ Método único: `create()`
- ✅ Ruta: `POST /api/reviews`
- ✅ Validación manual de campos requeridos
- ✅ Validación de tipos y rangos
- ✅ Verificación de existencia de libro
- ✅ Validación con Symfony Validator
- ✅ Respuesta con ID y created_at

### **BookRepository:**
- ✅ Método `findAllWithAverageRating()`
- ✅ Query con `LEFT JOIN` para incluir libros sin reseñas
- ✅ `AVG(r.rating)` calculado en base de datos
- ✅ `GROUP BY b.id` para agrupar por libro
- ✅ Conversión de average_rating a `null` si no hay reseñas

### **Query SQL generada (aproximada):**
```sql
SELECT 
    b.title, 
    b.author, 
    b.published_year, 
    AVG(r.rating) as average_rating
FROM books b 
LEFT JOIN reviews r ON b.id = r.book_id 
GROUP BY b.id
```

## ✅ **Características Implementadas**

### **Endpoint GET /api/books:**
- ✅ Campos exactos: title, author, published_year, average_rating
- ✅ Cálculo de promedio en base de datos (eficiente)
- ✅ Manejo de libros sin reseñas (average_rating = null)
- ✅ Respuesta JSON limpia y directa

### **Endpoint POST /api/reviews:**
- ✅ Validación completa de entrada
- ✅ Mensajes de error claros y específicos
- ✅ Status codes HTTP correctos (201 Created, 400 Bad Request)
- ✅ Respuesta con ID y timestamp de creación
- ✅ Verificación de existencia de libro
- ✅ Validación de rating entre 1-5
- ✅ Validación de comentario no vacío


## 🚀 **Para Probar los Endpoints**

### **1. Aplicar migraciones y cargar datos:**
```bash
sudo docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
sudo docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
```

### **2. Probar GET /api/books:**
```bash
curl http://localhost:8080/api/books | jq
```

### **3. Probar POST /api/reviews:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{"book_id": 2, "rating": 4, "comment": "Muy buen libro sobre código limpio"}' | jq
```

### **4. Verificar que el average_rating se actualiza:**
```bash
curl http://localhost:8080/api/books | jq
```

¡Los 2 endpoints están completamente implementados y funcionando según las especificaciones exactas! 🎯
