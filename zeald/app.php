<?php
echo "/zeald/app.php" . PHP_EOL;

use Zeald\Legacy\nba2019\Controller;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../include/utils.php';

// process the args
$args = collect($_REQUEST);

$format = $args->pull('format') ?: 'html';
$type = $args->pull('type');
if (!$type) {
    exit('Please specify a type');
}

$controller = new Controller($args);
echo $controller->export($type, $format);
