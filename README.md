# 📚 Books Management System

Sistema completo de gestión de libros desarrollado con **Symfony 6.4** y **Vue.js 3**, que incluye una API REST para operaciones CRUD de libros y reseñas, con integración de base de datos MySQL y arquitectura moderna de frontend.

## ✨ Características Principales

- **📖 Gestión Libros**: Operaciones (Listar)
- **⭐ Sistema de Reseñas**: Los usuarios pueden calificar y comentar libros (1-5 estrellas)
- **🏗️ Arquitectura Limpia**: Patrón Repository, Service Layer y DTOs con validación
- **🎯 API REST Moderna**: Endpoints RESTful con serialización JSON y manejo de errores
- **🚀 Frontend Reactivo**: Interfaz Vue.js 3 + Vite con componentes reutilizables
- **💾 Base de Datos**: Migraciones estructuradas y fixtures con datos de ejemplo
- **🌐 CORS Configurado**: Comunicación frontend-backend sin problemas

## 🛠️ Stack Tecnológico

- **Backend**: Symfony 6.4, PHP 8.1+, Doctrine ORM
- **Frontend**: Vue.js 3 + Vite, Axios, Composition API
- **Base de Datos**: MySQL 8.0
- **Herramientas**: Composer, NPM, Docker (opcional)
- **Librerías**: NelmioCorsBundle, Doctrine Fixtures, Symfony Validator

## 📋 Requisitos del Sistema

### Backend
- **PHP**: 8.1 o superior
- **Composer**: Última versión estable
- **MySQL**: 8.0 o superior (o PostgreSQL 13+)
- **Extensiones PHP**: `pdo_mysql`, `intl`, `json`, `mbstring`

### Frontend
- **Node.js**: 16.0 o superior
- **NPM**: 8.0 o superior (o Yarn 1.22+)

### Opcional
- **Symfony CLI**: Para servidor de desarrollo mejorado
- **Docker**: Para entorno containerizado

## 📁 Estructura del Proyecto

```
books-project/
├── 🎨 frontend/                    # Vue.js 3 + Vite
│   ├── src/
│   │   ├── components/
│   │   │   └── BookList.vue        # Componente lista de libros
│   │   ├── composables/
│   │   │   └── useBooks.js         # Lógica reactiva de libros
│   │   ├── App.vue                 # Componente principal
│   │   ├── main.js                 # Punto de entrada
│   │   └── style.css               # Estilos globales
│   ├── public/                     # Assets estáticos
│   ├── package.json                # Dependencias Node.js
│   └── vite.config.js              # Configuración Vite
├── 🔧 src/                         # Backend Symfony
│   ├── Controller/
│   │   ├── BookController.php      # API REST libros
│   │   └── ReviewController.php    # API REST reseñas
│   ├── Entity/
│   │   ├── Book.php                # Entidad libro
│   │   └── Review.php              # Entidad reseña
│   ├── Repository/
│   │   ├── BookRepository.php      # Consultas personalizadas
│   │   └── ReviewRepository.php    # Acceso a datos reseñas
│   ├── Service/
│   │   ├── BookService.php         # Lógica de negocio libros
│   │   └── ReviewService.php       # Lógica de negocio reseñas
│   ├── Dto/
│   │   └── CreateReviewDto.php     # DTOs con validación
│   └── DataFixtures/
│       └── AppFixtures.php         # Datos de ejemplo
├── ⚙️ config/
│   └── packages/
│       └── nelmio_cors.yaml        # Configuración CORS
├── 🗄️ migrations/                  # Migraciones de BD
├── 📋 composer.json                # Dependencias PHP
└── 📖 README.md                    # Esta documentación
```

## 📡 API Endpoints Documentados

### 📚 Gestión de Libros

#### `GET /api/books` - Listar todos los libros
**Request:**
```bash
curl -X GET http://localhost:8080/api/books
```

**Response (200 OK):**
```json
[
  {
    "id": 7,
    "title": "El Arte de Programar",
    "author": "Donald Knuth",
    "published_year": 1968,
    "average_rating": 3.8
  },
  {
    "id": 8,
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "published_year": 2008,
    "average_rating": 3.5
  }
]
```

### ⭐ Gestión de Reseñas

#### `POST /api/reviews` - Crear una nueva reseña
**Request:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 7,
    "rating": 5,
    "comment": "Excelente libro sobre algoritmos y programación"
  }'
```

**Response (201 Created):**
```json
{
  "id": 15,
  "created_at": "2025-01-18 14:30:25"
}
```

**Errores de Validación (400 Bad Request):**
```json
{
  "error": "Validation failed",
  "details": [
    "book_id is required",
    "rating must be an integer between 1 and 5",
    "comment cannot be empty"
  ]
}
```


## 🚀 Instalación y Configuración

### 📦 Configuración del Backend

1. **Clonar el repositorio:**
   ```bash
   git clone <repository-url>
   cd books-project
   ```

2. **Configurar variables de entorno:**
   ```bash
   # Si existe .env.example, copiarlo:
   cp .env.example .env
   
   # O crear .env con la configuración de base de datos:
   echo 'DATABASE_URL="mysql://root:password@127.0.0.1:3306/books_db?serverVersion=8.0&charset=utf8mb4"' > .env
   ```

3. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

4. **Configurar la base de datos:**
   ```bash
   # Crear la base de datos
   php bin/console doctrine:database:create
   
   # Ejecutar migraciones
   php bin/console doctrine:migrations:migrate
   
   # Cargar datos de ejemplo
   php bin/console doctrine:fixtures:load
   ```

5. **Iniciar el servidor de desarrollo:**
   ```bash
   # Opción 1: Con Symfony CLI (recomendado)
   symfony server:start
   
   # Opción 2: Con servidor PHP integrado
   php -S localhost:8080 -t public/
   ```

   ✅ **Backend disponible en:** http://localhost:8080

### 🎨 Configuración del Frontend

1. **Navegar al directorio frontend:**
   ```bash
   cd frontend
   ```

2. **Instalar dependencias de Node.js:**
   ```bash
   npm install
   # O con yarn:
   # yarn install
   ```

3. **Iniciar el servidor de desarrollo:**
   ```bash
   npm run dev
   # O con yarn:
   # yarn dev
   ```

   ✅ **Frontend disponible en:** http://localhost:3000

### 🐳 Configuración con Docker (Alternativa)

Si prefieres usar Docker:

```bash
# Iniciar contenedores
docker-compose up -d

# Instalar dependencias
docker-compose exec php composer install

# Configurar base de datos
docker-compose exec php php bin/console doctrine:database:create
docker-compose exec php php bin/console doctrine:migrations:migrate
docker-compose exec php php bin/console doctrine:fixtures:load

# El backend estará disponible en http://localhost:8080
# Configurar frontend por separado como se indica arriba
```

## 🎨 Desarrollo Frontend

El frontend está construido con **Vue 3** y **Vite** para un desarrollo rápido y moderno.

### Scripts Disponibles
```bash
npm run dev      # Servidor de desarrollo con hot reload
npm run build    # Build de producción optimizado  
npm run preview  # Preview del build de producción
```

### Características del Frontend
- ✅ **Vue 3 Composition API** con `<script setup>`
- ✅ **Composable useBooks** para gestión de estado reactivo
- ✅ **Componente BookList** responsive con grid CSS
- ✅ **Manejo de errores** y estados de carga
- ✅ **Hot Module Replacement** para desarrollo instantáneo

## 🧪 Ejemplos de Uso y Pruebas

### ✅ Ejemplo Funcional Completo

**1. Obtener lista de libros:**
```bash
curl -X GET http://localhost:8080/api/books
```

**2. Crear una reseña para un libro:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 7,
    "rating": 4,
    "comment": "Un libro muy instructivo sobre algoritmos"
  }'
```

**3. Verificar que la reseña afecta el rating promedio:**
```bash
curl -X GET http://localhost:8080/api/books
# Verás que el average_rating del libro se ha actualizado
```

### ❌ Ejemplos de Errores de Validación

**Reseña sin rating:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 7,
    "comment": "Comentario sin rating"
  }'
```
**Response (400):**
```json
{
  "error": "Validation failed",
  "details": ["rating is required"]
}
```

**Rating fuera del rango:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 7,
    "rating": 10,
    "comment": "Rating inválido"
  }'
```
**Response (400):**
```json
{
  "error": "Validation failed",
  "details": ["rating must be an integer between 1 and 5"]
}
```

**Libro inexistente:**
```bash
curl -X POST http://localhost:8080/api/reviews \
  -H "Content-Type: application/json" \
  -d '{
    "book_id": 999,
    "rating": 5,
    "comment": "Libro que no existe"
  }'
```
**Response (400):**
```json
{
  "error": "Book not found"
}
```

## 🏗️ Arquitectura y Patrones

### Patrones Implementados
- **🗄️ Repository Pattern**: `BookRepository`, `ReviewRepository` para acceso a datos
- **🔧 Service Layer**: Lógica de negocio separada en `BookService`, `ReviewService`
- **📝 DTO Pattern**: `CreateReviewDto` con validación Symfony
- **🎯 MVC**: Controladores REST, entidades Doctrine, respuestas JSON
- **🔄 Composable Pattern**: `useBooks.js` para estado reactivo en Vue

### Validaciones Implementadas
- **Backend**: Symfony Validator con DTOs y constraints
- **Frontend**: Validación reactiva y manejo de errores
- **Base de Datos**: Constraints, índices y relaciones FK

## 📝 Información de Evaluación

### Branch y Commit
- **Branch evaluado**: `main` / `master`
- **Commit final**: `[Insertar hash del commit final aquí]`
- **Fecha de entrega**: Enero 2025

### Funcionalidades Implementadas
✅ **Backend Symfony 6.4**:
- API REST para libros con ratings promedio
- Sistema de reseñas completo (CRUD)
- DTOs con validación robusta
- Patrón Repository y Service Layer
- Migraciones y fixtures de datos
- CORS configurado para frontend

✅ **Frontend Vue 3 + Vite**:
- Listado de libros con ratings
- Componente BookList reactivo
- Composable useBooks para gestión de estado
- Interfaz responsive y moderna
- Manejo de errores y estados de carga

✅ **Arquitectura**:
- Separación clara frontend/backend
- Comunicación via API REST
- Validación en ambas capas
- Código limpio y bien estructurado

## 🤔 Pregunta de Escalabilidad

### ¿Qué cambiarías para escalar esta aplicación a cientos de miles de libros y usuarios?

**Respuesta técnica:**

1. **Base de Datos**:
   - **Indexación**: Índices en campos de búsqueda frecuente (title, author, genre)
   - **Particionamiento**: Dividir tablas por fecha o categoría
   - **Read Replicas**: Separar lecturas de escrituras
   - **Database Sharding**: Distribuir datos en múltiples bases

2. **Cache**:
   - **Redis/Memcached**: Cache de consultas frecuentes
   - **CDN**: Para assets estáticos del frontend
   - **Application Cache**: Cache de ratings calculados
   - **Query Result Cache**: Doctrine query cache

3. **API y Backend**:
   - **Paginación**: Implementar paginación en todos los endpoints
   - **Rate Limiting**: Limitar requests por usuario/IP
   - **Background Jobs**: Procesar cálculos pesados en cola (Symfony Messenger)
   - **API Versioning**: Mantener compatibilidad con versiones

4. **Frontend**:
   - **Lazy Loading**: Cargar componentes bajo demanda
   - **Virtual Scrolling**: Para listas muy largas
   - **State Management**: Vuex/Pinia para estado global
   - **Service Workers**: Cache offline y PWA

5. **Infraestructura**:
   - **Load Balancers**: Distribuir carga entre servidores
   - **Microservicios**: Separar libros, usuarios, reseñas
   - **Container Orchestration**: Kubernetes para escalado automático
   - **Monitoring**: APM tools (New Relic, DataDog)

6. **Optimizaciones Específicas**:
   - **Search Engine**: Elasticsearch para búsquedas complejas
   - **Image Optimization**: WebP, lazy loading de portadas
   - **Database Queries**: Evitar N+1 queries, usar eager loading
   - **Caching Strategy**: Cache warming para datos populares

**Arquitectura objetivo:**
```
Frontend (Vue.js) → CDN → Load Balancer → 
API Gateway → Microservicios (Symfony) → 
Cache Layer (Redis) → Database Cluster (MySQL/PostgreSQL)
```

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT.
