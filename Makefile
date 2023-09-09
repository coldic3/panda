all: install
install: build run setup

build:
	@echo "Building Docker image..."
	docker compose build

run:
	@echo "Running Docker containers..."
	docker compose up -d

stop:
	@echo "Stopping Docker containers..."
	docker compose down

uninstall:
	@echo "Stopping and removing Docker containers..."
	docker compose down
	@echo "Removing images..."
	docker compose rm -f
	docker rmi $(shell docker compose images -q) || true

setup:
	@echo "Generate JWT keys..."
	docker compose exec php sh -c '\
		set -e\
		apk add openssl\
		php bin/console lexik:jwt:generate-keypair\
		setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt\
		setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt\
	'
