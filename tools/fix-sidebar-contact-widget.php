<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$now = date('Y-m-d H:i:s');

$widgetData = [
    1 => [
        'bio' => 'I am open to new 3D projects, production work, animation, VFX, and visual ideas.',
        'details' => [
            'Phone' => [
                ['key' => 'label', 'value' => 'Phone'],
                ['key' => 'value', 'value' => '+380-98-223-2974'],
                ['key' => 'icon', 'value' => 'ti ti-phone'],
                ['key' => 'url', 'value' => 'tel:+380982232974'],
            ],
            'Telegram' => [
                ['key' => 'label', 'value' => 'Telegram'],
                ['key' => 'value', 'value' => '@BeleckiyAndrey3D'],
                ['key' => 'icon', 'value' => 'ti ti-brand-telegram'],
                ['key' => 'url', 'value' => 'https://t.me/BeleckiyAndrey3D'],
            ],
            'Name' => [
                ['key' => 'label', 'value' => 'Name'],
                ['key' => 'value', 'value' => 'Білецький Андрій'],
                ['key' => 'icon', 'value' => 'ti ti-user'],
                ['key' => 'url', 'value' => 'https://t.me/BeleckiyAndrey3D'],
            ],
            'Location' => [
                ['key' => 'label', 'value' => 'Location'],
                ['key' => 'value', 'value' => 'Kremenchuk / Kyiv'],
                ['key' => 'icon', 'value' => 'ti ti-map'],
                ['key' => 'url', 'value' => 'https://maps.google.com/?q=Kremenchuk,Kyiv,Ukraine'],
            ],
        ],
    ],
    13 => [
        'bio' => 'З радістю беруся за нові 3D-проєкти та допомагаю перетворити ідею на виразну візуальну історію.',
        'details' => [
            'Phone' => [
                ['key' => 'label', 'value' => 'Телефон'],
                ['key' => 'value', 'value' => '+380-98-223-2974'],
                ['key' => 'icon', 'value' => 'ti ti-phone'],
                ['key' => 'url', 'value' => 'tel:+380982232974'],
            ],
            'Telegram' => [
                ['key' => 'label', 'value' => 'Telegram'],
                ['key' => 'value', 'value' => '@BeleckiyAndrey3D'],
                ['key' => 'icon', 'value' => 'ti ti-brand-telegram'],
                ['key' => 'url', 'value' => 'https://t.me/BeleckiyAndrey3D'],
            ],
            'Name' => [
                ['key' => 'label', 'value' => 'Ім’я'],
                ['key' => 'value', 'value' => 'Білецький Андрій'],
                ['key' => 'icon', 'value' => 'ti ti-user'],
                ['key' => 'url', 'value' => 'https://t.me/BeleckiyAndrey3D'],
            ],
            'Location' => [
                ['key' => 'label', 'value' => 'Локація'],
                ['key' => 'value', 'value' => 'Кременчук / Київ'],
                ['key' => 'icon', 'value' => 'ti ti-map'],
                ['key' => 'url', 'value' => 'https://maps.google.com/?q=Kremenchuk,Kyiv,Ukraine'],
            ],
        ],
    ],
];

$statement = $pdo->prepare('UPDATE `widgets` SET `data` = :data, `updated_at` = :updated_at WHERE `id` = :id');
foreach ($widgetData as $id => $data) {
    $statement->execute([
        'id' => $id,
        'data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'updated_at' => $now,
    ]);
}

$menuStatement = $pdo->prepare('UPDATE `widgets` SET `data` = JSON_SET(`data`, "$.id", :menu_id), `updated_at` = :updated_at WHERE `id` = :id');
$menuStatement->execute(['id' => 6, 'menu_id' => 'footer-menu-en', 'updated_at' => $now]);
$menuStatement->execute(['id' => 18, 'menu_id' => 'footer-menu-uk', 'updated_at' => $now]);

echo "Sidebar contact widgets updated.\n";
