## Peterson Henrique de Padua

## Carros

"Simple List of Car in PHP
"

### Dependências

- Docker
- Docker Compose

### To run

#### Clone Repository

```
$ git clone https://github.com/Pett97/CarrosListaPHP.git
$ cd CarrosListaPHP
docker composer up 

OR

./run up

```

#### Need Create the folder logs/nginx with access.log and error.log

```

```

#### Define the env variables

```
$ cp .env.example .env
```

#### Define the file database

```
$ touch ./database/brand.txt
$ touch ./database/cars.txt
$ chmod 777 ./database/cars.txt
$ chmod 777 ./database/brand.txt
```

#### Install the dependencies

```
$ docker compose run --rm composer install

./run composer
```

#### Up the containers

```
$ docker compose up -d
```
or
./run up


#### Run the tests

```
$ docker compose run --rm php ./vendor/bin/phpunit tests --color
or
./run test
```

#### Linters
```
./run phpcs
./run phpcbf
./run phpstan
```

