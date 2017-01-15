<?php
require(__DIR__ . '/config/app.php');
require(__DIR__ . '/migrations/products.php');

$db = new mysqli(
    $config['db']['host'],
    $config['db']['user'],
    $config['db']['password'],
    $config['db']['database']
);

echo 'cleanup database...' . PHP_EOL;
$migration = new Migration($db);
$migration->down();
echo 'uninstall complete!' . PHP_EOL;
