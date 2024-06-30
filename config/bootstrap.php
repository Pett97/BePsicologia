<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
//use Core\Constants\Constants;
use Core\Env\EnvLoader;
use Core\Erros\ErrorsHandler;
use Core\Router\Router;

//Constants::rootPath();
ErrorsHandler::init();
EnvLoader::init();
Router::init();

//require "routes.php";
