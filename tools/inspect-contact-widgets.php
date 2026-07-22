<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$widgets = $pdo->query('SELECT `id`, `widget_id`, `sidebar_id`, `position`, `data` FROM `widgets` ORDER BY `sidebar_id`, `position`, `id`')->fetchAll();

foreach ($widgets as $widget) {
    $data = (string) $widget['data'];

    if (
        str_contains($data, '+380-98-223-2974')
        || str_contains($data, 'contact@poligonium.com')
        || str_contains($data, 'https://poligonium.com')
        || str_contains($data, 'Кременчук')
        || str_contains($data, 'Phone')
        || str_contains($data, 'Телефон')
    ) {
        echo "ID: {$widget['id']} | widget: {$widget['widget_id']} | sidebar: {$widget['sidebar_id']} | pos: {$widget['position']}\n";
        echo $data . "\n\n";
    }
}
