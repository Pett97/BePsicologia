# ProjetoBack-End
Projeto desenvolvido na disciplina de Desenvolvimento de Aplicações Back-End do Curso de Tecnologia em Sistemas para Internet
# Alunos 
Peterson Henrique de Padua
Rafael Rodrigures Padilha

### Dependências

- Docker
- Docker Compose

### To run

#### Clone Repository

```
$ git clone https://github.com/Pett97/BePsicologia.git
$ cd BePsicologia
```

#### Define the env variables

```
$ cp .env.example .env
```

#### Install the dependencies

```
$ ./run composer install
```

#### Up the containers

```
$ docker compose up -d
```

ou

```
$ ./run up -d
```

#### Create database and tables

```
$ ./run db:reset
```

#### Populate database

```
$ ./run db:populate
```

### Fixed uploads folder permission

```
sudo chown www-data:www-data public/assets/uploads
```

#### Run the tests

```
$ docker compose run --rm php ./vendor/bin/phpunit tests --color
```

ou

```
$ ./run test
```

#### Run the linters

[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer/)

```
$ ./run phpcs
```

[PHPStan](https://phpstan.org/)

```
$ ./run phpstan
```

Access [localhost](http://localhost)

### Teste de API

```shell
curl -H "Accept: application/json" localhost/problems
```
