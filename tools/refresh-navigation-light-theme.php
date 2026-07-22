<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$now = date('Y-m-d H:i:s');

$pdo->prepare('INSERT INTO `settings` (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)')
    ->execute(['key' => 'theme-zelio-default_theme_color_mode', 'value' => 'light']);

$pdo->prepare('UPDATE `pages` SET `status` = :status, `updated_at` = :updated_at WHERE `id` = :id')
    ->execute(['status' => 'draft', 'updated_at' => $now, 'id' => 5]);

$pdo->prepare('UPDATE `categories` SET `status` = :status, `updated_at` = :updated_at WHERE `id` = :id')
    ->execute(['status' => 'draft', 'updated_at' => $now, 'id' => 6]);

$home = $pdo->query('SELECT `content` FROM `pages` WHERE `id` = 1')->fetchColumn();
if (is_string($home)) {
    $home = preg_replace('~<shortcode>\[blog-posts\b.*?\[/blog-posts\]</shortcode>\s*~s', '', $home);

    $statement = $pdo->prepare('UPDATE `pages` SET `content` = :content, `updated_at` = :updated_at WHERE `id` = 1');
    $statement->execute(['content' => $home, 'updated_at' => $now]);
}

$menuItems = [
    1 => [
        ['Home', '/', 'Botble\\Page\\Models\\Page', 1],
        ['Services', '/services', 'Botble\\Page\\Models\\Page', 2],
        ['Portfolio', '/projects', 'Botble\\Page\\Models\\Page', 3],
        ['Pricing', '/pricing', 'Botble\\Page\\Models\\Page', 4],
        ['Contact', '/contact', 'Botble\\Page\\Models\\Page', 6],
    ],
    2 => [
        ['Головна', '/', 'Botble\\Page\\Models\\Page', 1],
        ['Послуги', '/services', 'Botble\\Page\\Models\\Page', 2],
        ['Портфоліо', '/projects', 'Botble\\Page\\Models\\Page', 3],
        ['Ціни', '/pricing', 'Botble\\Page\\Models\\Page', 4],
        ['Контакти', '/contact', 'Botble\\Page\\Models\\Page', 6],
    ],
];

$pdo->prepare('DELETE FROM `menu_nodes` WHERE `menu_id` IN (1, 2)')->execute();

$insertMenu = $pdo->prepare(
    'INSERT INTO `menu_nodes` (`menu_id`, `parent_id`, `reference_id`, `reference_type`, `url`, `position`, `title`, `css_class`, `target`, `has_child`, `created_at`, `updated_at`) '
    . 'VALUES (:menu_id, 0, :reference_id, :reference_type, :url, :position, :title, NULL, "_self", 0, :created_at, :updated_at)'
);

foreach ($menuItems as $menuId => $items) {
    foreach ($items as $position => [$title, $url, $referenceType, $referenceId]) {
        $insertMenu->execute([
            'menu_id' => $menuId,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
            'url' => $url,
            'position' => $position,
            'title' => $title,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

$footerMenus = [
    6 => [
        ['Home', 'https://poligonium.com'],
        ['Services', 'https://poligonium.com/services'],
        ['Portfolio', 'https://poligonium.com/projects'],
        ['Pricing', 'https://poligonium.com/pricing'],
        ['Contact', 'https://poligonium.com/contact'],
    ],
    18 => [
        ['Головна', 'https://poligonium.com'],
        ['Послуги', 'https://poligonium.com/services'],
        ['Портфоліо', 'https://poligonium.com/projects'],
        ['Ціни', 'https://poligonium.com/pricing'],
        ['Контакти', 'https://poligonium.com/contact'],
    ],
];

$updateWidget = $pdo->prepare('UPDATE `widgets` SET `data` = :data, `updated_at` = :updated_at WHERE `id` = :id');

foreach ($footerMenus as $id => $items) {
    $data = [
        'id' => 'Botble\\Widget\\Widgets\\CoreSimpleMenu',
        'items' => array_map(
            static fn (array $item): array => [
                ['key' => 'label', 'value' => $item[0]],
                ['key' => 'url', 'value' => $item[1]],
            ],
            $items
        ),
    ];

    $updateWidget->execute([
        'data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'updated_at' => $now,
        'id' => $id,
    ]);
}

echo "Navigation refreshed and light theme set.\n";
