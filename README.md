<div style="text-align: justify;">
WIKI - https://github.com/Pett97/BePsicologia/wiki

## Peterson Henrique de Padua
## Rafael Rodrigutes Padilha

## BePsicologia

"
BePsicologia - Sistema para gerenciamento de consultas 
"


### Dependências

- Docker
- Docker Compose

### To run

#### Clone Repository

```
$ git clone https://github.com/Pett97/BePsicologia.git
$ cd BePsicologia
docker composer up 

```

#### Need Create the folder logs/nginx with access.log and error.log

```

```

#### Define the env variables

```
$ cp .env.example .env
```


#### Install the dependencies

```
$ docker compose run --rm composer install
OR
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
### Database
```
./run db:reset
./run db:populate

```

