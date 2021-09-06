<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../index.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(false, true, true);

$app->get('/schedule/', 'get_schedule');

$app->run();
