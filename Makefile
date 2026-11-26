default: help

.PHONY: help
help: ## Display this help screen
	@grep -hP '^\w.*?:.*##.*$$' $(MAKEFILE_LIST) | sort -u | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: build
build: ## Build the PHP containers
	docker compose -f .docker/docker-compose.yml build

.PHONY: bash
bash: ## Run bash in the PHP 8.2 container
	docker compose -f .docker/docker-compose.yml run --rm php bash

.PHONY: wp/setup
wp/setup: ## Install WordPress with SQLite in tests/_wordpress
	@php tests/bin/setup-wordpress.php

.PHONY: unit
unit: ## Run the unit tests
	@php vendor/bin/codecept run unit

.PHONY: integration
integration: wp/setup ## Run the integration tests on WordPress
	@php vendor/bin/codecept run integration

.PHONY: test
test: unit integration ## Run all the tests

.PHONY: stan
stan: ## Run PHPStan analysis
	@php vendor/bin/phpstan analyse

.PHONY: ecs
ecs: ## Run Easy Coding Standard
	@php vendor/bin/ecs check

.PHONY: ecs/fix
ecs/fix: ## Fix coding standard issues
	@php vendor/bin/ecs check --fix

.PHONY: rector
rector: ## Run Rector with dry-run
	@php vendor/bin/rector process --dry-run

.PHONY: qa
qa: ecs stan test ## Run all QA tools
