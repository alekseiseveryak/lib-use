<?php

use App\Main;

require_once __DIR__ . '/vendor/autoload.php';

$main = new Main($argv[1] ?? '');
$main->run();
