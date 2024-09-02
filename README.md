# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. If not already done, install `make` with `sudo apt-get install build-essential` (contains other packages as well) or simply `sudo apt-get -y install make`
3. Run `make start` to build fresh images
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `make down` to stop the Docker containers.

## Makefile commands
Use these commands to simplify day to day usage of the project.
- `help`: Outputs this help screen
- `build`: Builds the Docker images
- `up`: Start the docker hub in detached mode (no logs)
- `start`: Build and start the containers
- `down`: Stop the docker hub
- `logs`: Show live logs
- `sh`: Connect to the FrankenPHP container using sh
- `bash`: Connect to the FrankenPHP container using bash
- `test`: Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
- `composer`: Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
- `vendor`: Install vendors according to the current composer.lock file
- `sf`: List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
- `cc`: Clear the cache
- `diff`: Create migration based on the diff between the current state of the database and entities
- `migrate`: Run migrations

# Testing

Database for testing should be set up as follows (run these commands in the php container):
- `bin/console --env=test doctrine:database:create`
- `bin/console --env=test doctrine:migrations:migrate`
