<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$settings = $pdo->query("SELECT `key`, `value` FROM `settings` WHERE `key` LIKE '%social%' OR `value` LIKE '%facebook%' OR `value` LIKE '%twitter%' OR `value` LIKE '%youtube%' OR `value` LIKE '%linkedin%' OR `value` LIKE '%x.com%' ORDER BY `key`")->fetchAll();

foreach ($settings as $setting) {
    echo $setting['key'] . ' = ' . $setting['value'] . "\n";
}
