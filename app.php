<?php

namespace App;

require_once __DIR__ . '/vendor/autoload.php';

$input = $_POST['string'] ?? null;
$main = new Main($input);
$main->run();
