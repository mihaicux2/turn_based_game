# Hero Game

HeroGame implementation.

In order to run this app, you'll need [PHP](https://www.php.net/) 7 and an [Apache](https://httpd.apache.org/) webserver installed on your computer.

You can use [Docker](https://www.docker.com/) with the provided `docker-compose.yml` and `Dockerfile` files.

To run the docker stack, use the following commands:

```sh
# build the docker image
docker-compose build
# start the docker containers
docker-compose up -d
```

This will install and run Docker containers for PHP + Apache, MySQL and PhpMyAdmin.
After starting the containers, the application should be up and running on port 8001:
* [http://localhost:8001](http://localhost:8001)

You can access PhpMyAdmin on port 8000:
* [http://localhost:8000](http://localhost:8000)


Auto-generated source docs can be found here:
* [http://www.localhost.com:8001/docs/html/index.xhtml](http://www.localhost.com:8001/docs/html/index.xhtml)


In order to run the application, you should first install it's dependencies using [composer](https://getcomposer.org/).
You can regenerate the documentation using [PHPDOX](http://phpdox.de/).
You can run the test scenarios using [PHPUnit](https://phpunit.de/)

If you're running the app in Docker, you'll need first to connect to the PHP container:
```sh
# using docker-compose
docker-compose exec www /bin/bash
# or, using docker
#     $ docker exec -it apache_container /bin/bash
```

```sh
# install PHP dependencies
composer install
# re-generate the source docs
phpdox
# run test suite
phpunit --bootstrap vendor/autoload.php tests/
```

<!-- MySQL Client:

```sh
$ docker-compose exec db mysql -u root -p 
```

Enjoy ! -->
