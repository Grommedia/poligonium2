<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$now = date('Y-m-d H:i:s');

$update = static function (string $table, int $id, array $values) use ($pdo, $now): void {
    $values['updated_at'] = $now;

    $sets = [];
    foreach ($values as $column => $_) {
        $sets[] = "`$column` = :$column";
    }

    $statement = $pdo->prepare(sprintf('UPDATE `%s` SET %s WHERE `id` = :id', $table, implode(', ', $sets)));
    $statement->execute([...$values, 'id' => $id]);
};

$update('categories', 2, [
    'name' => 'Відкриті 3D-інструменти',
    'description' => 'Матеріали про відкриті 3D-інструменти, корисні ресурси та внесок у спільноту.',
]);

$portfolioCategories = [
    1 => [
        'name' => '3D-моделювання',
        'description' => 'Створення 3D-моделей, сцен, персонажів і візуальних матеріалів під задачі бізнесу та продукту.',
    ],
    2 => [
        'name' => '3D-анімація',
        'description' => 'Анімація персонажів, продуктів, логотипів і сцен для реклами, презентацій та цифрових платформ.',
    ],
    3 => [
        'name' => 'Візуалізація продуктів',
        'description' => 'Фотореалістичні рендери, предметна візуалізація та підготовка матеріалів для каталогів, сайтів і маркетингу.',
    ],
    4 => [
        'name' => 'Технічна інтеграція',
        'description' => 'Оптимізація 3D-ассетів, підготовка до web/API, пайплайн, автоматизація та інтеграція у цифрові продукти.',
    ],
];

foreach ($portfolioCategories as $id => $values) {
    $update('pf_service_categories', $id, $values);
}

$englishPortfolioCategories = [
    1 => [
        'name' => 'Backend Development',
        'description' => 'Server-side development with robust, scalable architectures.',
    ],
    2 => [
        'name' => 'Frontend Development',
        'description' => 'Building interactive and responsive web interfaces using modern technologies.',
    ],
    3 => [
        'name' => 'Mobile App Development',
        'description' => 'Developing cross-platform mobile applications for Android and iOS.',
    ],
    4 => [
        'name' => 'DevOps & Cloud',
        'description' => 'Cloud-based services and infrastructure management with CI/CD practices.',
    ],
];

$statement = $pdo->prepare(
    'INSERT INTO `pf_service_categories_translations` (`lang_code`, `pf_service_categories_id`, `name`, `description`) '
    . 'VALUES (:lang_code, :id, :name, :description) '
    . 'ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `description` = VALUES(`description`)'
);

foreach ($englishPortfolioCategories as $id => $values) {
    $statement->execute([
        'lang_code' => 'en_US',
        'id' => $id,
        ...$values,
    ]);
}

$update('pf_packages', 1, [
    'action_label' => 'Почати',
]);

$update('pf_packages', 2, [
    'action_label' => 'Почати',
]);

echo "Ukrainian portfolio categories fixed.\n";
