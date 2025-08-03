.PHONY: help build up down restart logs shell composer test test-coverage install clean

# Default target
help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# Docker commands
build: ## Build Docker containers
	docker-compose build

up: ## Start Docker containers
	docker-compose up -d

down: ## Stop Docker containers
	docker-compose down

restart: ## Restart Docker containers
	docker-compose restart

logs: ## Show Docker container logs
	docker-compose logs -f

shell: ## Access the app container shell
	docker-compose exec app bash

# Development commands
install: ## Install dependencies
	docker-compose run --rm composer install

update: ## Update dependencies
	docker-compose run --rm composer update

composer: ## Run composer command (usage: make composer CMD="require package")
	docker-compose run --rm composer $(CMD)

# Testing commands
test: ## Run tests
	docker-compose --profile testing run --rm pest

test-coverage: ## Run tests with coverage
	docker-compose --profile testing run --rm pest --coverage-html coverage

test-watch: ## Run tests in watch mode
	docker-compose --profile testing run --rm pest --watch

# Utility commands
clean: ## Clean up Docker resources
	docker-compose down -v
	docker system prune -f

reset: ## Reset the entire environment
	make clean
	make build
	make up
	make install

status: ## Show container status
	docker-compose ps