Collabsy - app for collaboration
========================

[HR] Aplikacija za kolaboraciju između učenika.

Requirements
------------

  * PHP 7.2.15 or higher;
  * PostgreSQL PHP extension
  * and the [usual Symfony application requirements][2].

Installation
------------

Clone this repository and run:

```bash
$ composer install --no-interaction
```

Then run latest migrations:

```bash
$ ./bin/console doctrine:migrations:migrate
```

Usage
-----

There's no need to configure anything to run the application. If you have
installed the [Symfony client][4] binary, run this command to run the built-in
web server and access the application in your browser at <http://localhost:8000>:

```bash
$ cd /.../bekdur/
$ symfony serve
```

If you don't have the Symfony client installed, run `php bin/console server:run`.
Alternatively, you can [configure a web server][3] like Nginx or Apache to run
the application.

[2]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[4]: https://symfony.com/download
