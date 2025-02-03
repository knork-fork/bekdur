Collabsy - app for collaboration
========================

[HR] Aplikacija za kolaboraciju između učenika.

Requirements
------------

  * Docker, docker-compose

Installation
------------

Clone this repository and start container:

```bash
docker-compose up --build -d
```

Run composer install:

```bash
docker/composer install --no-interaction
```

Then run latest migrations:

```bash
docker/console doctrine:migrations:migrate
```

**NOTE: Default vendor folder was changed from ./vendor to /home/bekdur/symfony_vendor (check 2415b69).**
*Edit: for docker environment this means composer install must be run every time container is started.*

Usage
-----

By default app should be available @ localhost:62000