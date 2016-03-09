<?php

require __DIR__ . "/../vendor/autoload.php";

use G\Gearman\Client;
use G\GearmanServiceProvider;
use Silex\Application;

$app = new Application();

$client = new \GearmanClient();
$client->addServers("localhost:4730");
$app->register(new GearmanServiceProvider($client));

$app->get("/", function (Client $client) {
    return "Hello " . $client->doNormal("worker.example", "Gonzalo");
});

$app->run();
