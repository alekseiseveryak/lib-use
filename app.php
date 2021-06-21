#!/usr/bin/env php
<?php

use App\Main;

require_once __DIR__ . '/vendor/autoload.php';

const INTRO = <<<INTRO
    ___ __
   / (_) /_        __  __________
  / / / __ \______/ / / / ___/ _ \
 / / / /_/ /_____/ /_/ (__  )  __/
/_/_/_.___/      \__,_/____/\___/
INTRO;
echo INTRO . "\n\n";

$opt = getopt('p:', ['port:']);
$port = $opt['p'] ?? $opt['port'] ?? false;
if ($port) {
    $main = new Main((int)$port);
    $main->run();
} else {
    die('Отсутствует параметр -p (--port)');
}
