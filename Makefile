.PHONY: composer-install create-network help install php-cs-fixer phpstan restart root start stop
.DEFAULT_GOAL := help

DOCKER_ROOT=docker exec -t --user root $(shell docker ps --filter name=sf-default-symfony_app -q)
DOCKER_ROOT_I=docker exec -ti --user root $(shell docker ps --filter name=sf-default-symfony_app -q)
ARGS=10 2

root: ## Enter container as root
	$(DOCKER_ROOT_I) bash

composer-install: ## Run composer install
	$(DOCKER_ROOT) composer install

check-code: phpstan php-cs-fixer ## Fixes code style issues and analyze PHP code for errors

create-network: ## Create network
	-docker network create app-network

install: create-network start composer-install ## Install dependencies

php-cs-fixer: composer-install ## Apply coding standards with php-cs-fixer
	$(DOCKER_ROOT) vendor/bin/php-cs-fixer fix

phpstan: composer-install ## Launch static code analysis
	$(DOCKER_ROOT) vendor/bin/phpstan

start: ## Start the project
	COMPOSE_PROJECT_NAME="sf-default" docker compose -f docker-compose.yml up -d --build

stop: ## Stop the project
	COMPOSE_PROJECT_NAME="sf-default" docker compose -f docker-compose.yml down

restart: stop start ## Restart the project

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
