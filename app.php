#!/usr/bin/env php
<?php

namespace App;

require_once __DIR__ . '/vendor/autoload.php';

const ROOT = __DIR__;
const INTRO = <<<INTRO
    ___ __
   / (_) /_        __  __________
  / / / __ \______/ / / / ___/ _ \
 / / / /_/ /_____/ /_/ (__  )  __/
/_/_/_.___/      \__,_/____/\___/
INTRO;
echo INTRO . "\n\n";

$config = new Config();
$config->load();
$port = $config->params->port ?? false;

if ($port) {
    $main = new Main((int)$port);
    $main->run();
} else {
    die('Невозможно получить номер порта' . PHP_EOL);
}
