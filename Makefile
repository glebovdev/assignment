.PHONY: up down build rebuild logs ps clean help

# Default target executed when no arguments are given to make.
default: help

# Start the application
up:
	@docker-compose --env-file .env.docker up -d
	@echo "Application is running at http://localhost"

# Stop the application
down:
	@docker-compose --env-file .env.docker down
	@echo "Application has been stopped"

# Build or rebuild the Docker images
build:
	@docker-compose --env-file .env.docker build
	@echo "Docker images have been built"

# Rebuild and restart the application
rebuild: down build up

# View logs
logs:
	@docker-compose --env-file .env.docker logs -f

# Show the status of the containers
ps:
	@docker-compose --env-file .env.docker ps

# Run database migrations
migrate:
	@docker-compose --env-file .env.docker exec app php artisan migrate --force
	@echo "Database migrations completed"

# Seed the database
seed:
	@docker-compose --env-file .env.docker exec app php artisan db:seed
	@echo "Database seeding completed"

# Enter the app container shell
shell:
	@docker-compose --env-file .env.docker exec app bash

# Remove all containers, volumes, and images
clean:
	@docker-compose --env-file .env.docker down -v
	@echo "Containers and volumes removed"

# Display this help message
help:
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@echo "  up        Start the application (default)"
	@echo "  down      Stop the application"
	@echo "  build     Build or rebuild the Docker images"
	@echo "  rebuild   Rebuild and restart the application"
	@echo "  logs      View logs"
	@echo "  ps        Show the status of the containers"
	@echo "  migrate   Run database migrations"
	@echo "  seed      Seed the database"
	@echo "  shell     Enter the app container shell"
	@echo "  clean     Remove all containers and volumes"
	@echo "  help      Display this help message" 