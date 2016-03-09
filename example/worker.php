<?php

require __DIR__ . "/../vendor/autoload.php";

use G\Gearman\Builder;

$worker = Builder::createWorker();

$worker->on("worker.example", function ($response, \GearmanJob $job) {
    echo "Response: {$response} unique: {$response}\n";

    return strrev($response);
});

$worker->run();