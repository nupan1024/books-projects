#!/bin/bash

# Books Project Setup Script
echo "🚀 Setting up Books Management System..."

# Check if Docker is running
if ! sudo docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

echo "📦 Starting Docker containers..."
sudo docker compose up -d

echo "⏳ Waiting for database to be ready..."
sleep 15

echo "📚 Installing PHP dependencies..."
sudo docker compose exec php composer install

echo "🗄️  Running database migrations..."
sudo docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

echo "🌱 Loading sample data..."
sudo docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

echo "🧹 Clearing cache..."
sudo docker compose exec php php bin/console cache:clear

echo ""
echo "✅ Setup complete!"
echo ""
echo "🌐 API is available at: http://localhost:8080"
echo "📊 Database is available at: localhost:3306 (user: books, password: books)"
echo ""
echo "📖 Try these endpoints:"
echo "  GET  http://localhost:8080/api/books"
echo "  GET  http://localhost:8080/api/books/statistics"
echo "  GET  http://localhost:8080/api/books/genres"
echo ""
echo "📚 Happy coding!"
