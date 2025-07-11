# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PANDA API (Portfolio Arrangement: Nest of Digitalized Assets) is a Symfony 7.3+ based portfolio management API using Domain-Driven Design (DDD) with bounded contexts and hexagonal architecture.

## Key Commands

### Development with Docker
- `make install` - Complete setup (build, run, setup JWT keys)
- `make build` - Build Docker images
- `make run` - Start containers
- `make stop` - Stop containers
- `docker compose exec php bash` - Access PHP container shell

### Testing
- `vendor/bin/phpunit --testsuite unit` - Unit tests only
- `vendor/bin/phpunit --testsuite contract` - Contract tests only
- `vendor/bin/phpspec run` - PHPSpec behavioral tests
- `vendor/bin/behat --strict --no-interaction -f progress` - Behat acceptance tests

### Code Quality
- `composer static-analyze:phpstan` - Run PHPStan static analysis
- `composer static-analyze:code-style` - Check code style (dry-run)
- `composer fix:code-style` - Fix code style issues

## Architecture

### Bounded Contexts
The application is organized into distinct bounded contexts:

- **Account** - User management and authentication
- **Trade** - Asset and transaction management
- **Exchange** - Exchange rate management (live rates and historical logs)
- **Portfolio** - Portfolio and portfolio item management
- **Report** - Report generation and file management
- **Core** - Shared infrastructure and common patterns

### Anti-Corruption Layers (ACL)
- **PortfolioACL/Trade** - Handles portfolio updates when trade events occur
- **TradeACL/Exchange** - Ensures exchange rate integrity for transactions

### Architectural Patterns
- **Hexagonal Architecture** - Each bounded context follows ports and adapters pattern
- **CQRS** - Command Query Responsibility Segregation with separate handlers
- **Event-Driven** - Domain events for cross-context communication
- **Repository Pattern** - Abstract data access with Doctrine ORM implementation

### Layer Structure (per bounded context)
- `Application/` - Commands, queries, handlers, and application services
- `Domain/` - Business logic, entities, value objects, domain events
- `Infrastructure/` - External concerns (API, database, configuration)

### Key Infrastructure Components
- **Symfony 7.3+** - Framework
- **API Platform 4.0+** - REST API with OpenAPI documentation
- **Doctrine ORM** - Database persistence with PostgreSQL
- **JWT Authentication** - Token-based API authentication
- **Docker** - Containerized development environment

## Configuration Structure
Configuration is centralized in `src/Core/Infrastructure/Configuration/Symfony/` with package-specific configs in the `Package/` subdirectory.

## Testing Strategy
- **Unit Tests** (`tests/App/`) - Domain logic testing
- **Contract Tests** (`tests/Api/`) - API endpoint testing with fixtures
- **Behavioral Tests** (`spec/`) - PHPSpec behavior specification
- **Acceptance Tests** (`features/`, `tests/Behat/`) - Gherkin-based feature testing
- **Architecture Tests** (`tests/Architecture/`) - Enforce architectural boundaries

## Development Notes
- JWT keys must be generated after initial setup
- All tested API responses (contracts) have corresponding JSON files in `responses/api/`
- Database migrations are in `migrations/` directory
- All data fixtures loaded in API (contract) tests are in `fixtures/api/`
- PHPStan is configured with max level analysis
- Code style is enforced with PHP CS Fixer
