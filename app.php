#!/usr/bin/env php
<?php

namespace App;

require_once __DIR__ . '/vendor/autoload.php';

set_time_limit(0);

const ROOT = __DIR__;
const WELCOME = <<<WELCOME
    ___ __
   / (_) /_        __  __________
  / / / __ \______/ / / / ___/ _ \
 / / / /_/ /_____/ /_/ (__  )  __/
/_/_/_.___/      \__,_/____/\___/
WELCOME;

$main = new Main();
$main->run();
