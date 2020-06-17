FFE-COMPET Application
========================

The "FFE-COMPET Application" is an Equestrian contests management application.

Requirements
------------

  * PHP 7.2.5 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements][2].

Installation
------------

Install the `FFE-COMPET` binary on your computer and run
this command:

```bash
$ git clone https://github.com/Marin-Fa/FFE-COMPET.git
```

Usage
-----

Create database:

```bash
$ php bin/console d:d:c
```

Generate migrations:

```bash
$ php bin/console d:m:m -n
```

Generate fixtures:

```bash
$ php bin/console d:f:l -n
```

Launch the webserver:

```bash
$ cd FFE-COMPET
$ symfony serve
```

If you don't have the Symfony binary installed, you can run built-in PHP web server:

```bash
$ php -S localhost:8000 -t public/
```

Or [configure a web server][3] like Nginx or
Apache to run the application.


[1]: https://symfony.com/doc/current/best_practices.html
[2]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[4]: https://symfony.com/download
