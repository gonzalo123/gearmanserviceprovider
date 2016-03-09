<?php

require __DIR__ . "/../vendor/autoload.php";

use G\Gearman\Client;
use G\GearmanServiceProvider;
use Silex\Application;

$app = new Application();

$app->register(new GearmanServiceProvider());

$app->get("/", function (Client $client) {
    return "Hello " . $client->doNormal("worker.example", "Gonzalo");
});

$app->run();
