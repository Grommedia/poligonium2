<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$now = date('Y-m-d H:i:s');

$contacts = [
    'name_uk' => 'Білецький Андрій',
    'name_ru' => 'Білецкий Андрей',
    'phone_display' => '+380-98-223-2974',
    'phone_href' => 'tel:+380982232974',
    'email' => 'contact@poligonium.com',
    'telegram_display' => '@BeleckiyAndrey3D',
    'telegram_href' => 'https://t.me/BeleckiyAndrey3D',
    'location_uk' => 'Кременчук / Київ',
    'location_en' => 'Kremenchuk / Kyiv',
];

$contactShortcode = sprintf(
    '[contact-form display_fields="phone,email,subject,address" mandatory_fields="email,subject" style="1" title="Зв&#039;яжімося" quantity="4" label_1="Телефон" content_1="%s" icon_1="ti ti-phone" url_1="%s" label_2="Telegram" content_2="%s" icon_2="ti ti-brand-telegram" url_2="%s" label_3="Ім&#039;я" content_3="%s" icon_3="ti ti-user" url_3="%s" label_4="Локація" content_4="%s" icon_4="ti ti-map" url_4="https://maps.google.com/?q=Kremenchuk,Kyiv,Ukraine"][/contact-form]',
    $contacts['phone_display'],
    $contacts['phone_href'],
    $contacts['telegram_display'],
    $contacts['telegram_href'],
    $contacts['name_uk'],
    $contacts['telegram_href'],
    $contacts['location_uk']
);

$contactPageShortcode = str_replace('style="1"', 'style="2"', $contactShortcode);

$replaceContactForm = static function (string $content, string $replacement): string {
    return preg_replace('~<shortcode>\[contact-form\b.*?\[/contact-form\]</shortcode>~su', '<shortcode>' . $replacement . '</shortcode>', $content) ?? $content;
};

$replacePlainContactValues = static function (string $content) use ($contacts): string {
    $replacements = [
        '+1-234-567-8901' => $contacts['phone_display'],
        '+1 234 567 890' => $contacts['phone_display'],
        'tel:+1-234-567-8901' => $contacts['phone_href'],
        'contact@botble.com' => $contacts['email'],
        'Botble Technologies' => 'Poligonium',
        '0811 Erdman Prairie, Joaville CA' => $contacts['location_uk'],
        'Україна / дистанційна співпраця' => $contacts['location_uk'],
        'Україна / дистанційно' => $contacts['location_uk'],
        'Poligonium" icon_3="ti ti-user" url_3="https://poligonium.com"' => $contacts['telegram_display'] . '" icon_3="ti ti-brand-telegram" url_3="' . $contacts['telegram_href'] . '"',
    ];

    return strtr($content, $replacements);
};

$updatedPages = 0;
$pages = $pdo->query('SELECT `id`, `content` FROM `pages`')->fetchAll();

foreach ($pages as $page) {
    $content = (string) $page['content'];
    $updated = $replacePlainContactValues($content);

    if ((int) $page['id'] === 1) {
        $updated = $replaceContactForm($updated, $contactShortcode);
    }

    if ((int) $page['id'] === 6) {
        $updated = preg_replace('~\[contact-form\b.*?\[/contact-form\]~su', $contactPageShortcode, $updated) ?? $updated;
    }

    if ($updated !== $content) {
        $statement = $pdo->prepare('UPDATE `pages` SET `content` = :content, `updated_at` = :updated_at WHERE `id` = :id');
        $statement->execute([
            'content' => $updated,
            'updated_at' => $now,
            'id' => $page['id'],
        ]);
        $updatedPages++;
    }
}

$updatedWidgets = 0;
$widgets = $pdo->query('SELECT `id`, `data` FROM `widgets`')->fetchAll();

foreach ($widgets as $widget) {
    $data = (string) $widget['data'];
    $updated = $replacePlainContactValues($data);

    $decoded = json_decode($data, true);
    if (is_array($decoded)) {
        array_walk_recursive($decoded, static function (&$value, $key) use ($contacts): void {
            if (! is_string($value)) {
                return;
            }

            $normalizedKey = (string) $key;

            if (str_contains(strtolower($normalizedKey), 'phone') || str_contains($value, '+1-234') || str_contains($value, '+1 234')) {
                $value = str_starts_with($value, 'tel:') ? $contacts['phone_href'] : $contacts['phone_display'];
            } elseif (str_contains(strtolower($normalizedKey), 'telegram') || str_contains(strtolower($normalizedKey), 'social') || str_contains($value, 'Botble') || str_contains($value, 'Poligonium')) {
                $value = str_starts_with($value, 'http') ? $contacts['telegram_href'] : $contacts['telegram_display'];
            } elseif (str_contains(strtolower($normalizedKey), 'address') || str_contains(strtolower($normalizedKey), 'location') || str_contains($value, 'Україна')) {
                $value = $contacts['location_uk'];
            } elseif (str_contains($value, 'contact@botble.com')) {
                $value = $contacts['email'];
            }
        });

        $updated = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    if ($updated !== $data) {
        $statement = $pdo->prepare('UPDATE `widgets` SET `data` = :data, `updated_at` = :updated_at WHERE `id` = :id');
        $statement->execute([
            'data' => $updated,
            'updated_at' => $now,
            'id' => $widget['id'],
        ]);
        $updatedWidgets++;
    }
}

$settings = [
    'admin_email' => $contacts['email'],
    'contact_email' => $contacts['email'],
    'site_email' => $contacts['email'],
    'phone' => $contacts['phone_display'],
];

$statement = $pdo->prepare('INSERT INTO `settings` (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
foreach ($settings as $key => $value) {
    $statement->execute(['key' => $key, 'value' => $value]);
}

echo "Updated {$updatedPages} pages and {$updatedWidgets} widgets.\n";
