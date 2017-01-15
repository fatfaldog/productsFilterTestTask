<?php
require(__DIR__ . '/config/app.php');
require(__DIR__ . '/migrations/products.php');

$db = new mysqli(
    $config['db']['host'],
    $config['db']['user'],
    $config['db']['password'],
    $config['db']['database']
);

echo 'installing database...' . PHP_EOL;
$migration = new Migration($db);
$migration->up();
echo 'install complete!' . PHP_EOL;
