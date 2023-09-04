# PANDA API
Portfolio Arrangement: Nest of Digitalized Assets

## Installation with Docker 🐋

> **_NOTE:_** The installation assumes that you have [Docker](https://docs.docker.com/get-docker/)
> and [Docker Compose](https://docs.docker.com/compose/install/) installed on your machine.

1. Clone the repo:
   ```sh
   git clone git@github.com:coldic3/panda.git
   ```

2. Download the latest version of Docker images:
    ```sh
    docker compose pull --include-deps
    ```

3. Build Docker images:
    ```sh
   docker compose build --no-cache
   ```

4. Start the containers:
    ```sh
    docker compose up -d
    ```

5. Generate the public and private keys used for signing JWT tokens:
    ```sh
    docker compose exec php sh -c '
        set -e
        apk add openssl
        php bin/console lexik:jwt:generate-keypair
        setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
        setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    '
   ```

