<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$telegram = json_encode([
    [
        ['key' => 'name', 'value' => 'Telegram'],
        ['key' => 'icon', 'value' => 'ti ti-brand-telegram'],
        ['key' => 'url', 'value' => 'https://t.me/BeleckiyAndrey3D'],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$statement = $pdo->prepare('INSERT INTO `settings` (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');

foreach (['theme-zelio-social_links', 'theme-zelio-en_US-social_links'] as $key) {
    $statement->execute(['key' => $key, 'value' => $telegram]);
}

echo "Social links replaced with Telegram.\n";
