<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$content = $pdo->query('SELECT `content` FROM `pages` WHERE `id` = 1')->fetchColumn();

if (! is_string($content)) {
    throw new RuntimeException('Home page content was not found.');
}

$updated = preg_replace(
    [
        '/\sprimary_button_text="[^"]*"/u',
        '/\sprimary_button_link="[^"]*"/u',
        '/\sprimary_button_url="[^"]*"/u',
        '/\sprimary_button_icon="[^"]*"/u',
        '/\sopen_primary_link_in_the_new_tab="[^"]*"/u',
    ],
    '',
    $content,
    -1,
    $count
);

if (! is_string($updated)) {
    throw new RuntimeException('Could not update home page content.');
}

$statement = $pdo->prepare('UPDATE `pages` SET `content` = :content, `updated_at` = :updated_at WHERE `id` = 1');
$statement->execute([
    'content' => $updated,
    'updated_at' => date('Y-m-d H:i:s'),
]);

echo "Removed {$count} CV button attributes from the home hero.\n";
