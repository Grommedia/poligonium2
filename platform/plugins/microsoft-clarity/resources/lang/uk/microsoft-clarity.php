<?php

return [
    'name' => 'Microsoft Clarity',
    'menu_name' => 'Microsoft Clarity',
    'settings' => [
        'title' => 'Microsoft Clarity',
        'description' => 'Підключення поведінкової аналітики, теплових карт і записів сесій Microsoft Clarity.',
        'enabled' => 'Увімкнути Clarity на сайті',
        'enabled_help' => 'Коли ввімкнено і вказано Project ID, скрипт Clarity додається на публічні сторінки сайту.',
        'project_id' => 'Project ID',
        'project_id_help' => 'ID проєкту з Microsoft Clarity. Його можна вставити вручну або підключити через вікно Clarity нижче.',
        'tracking_mode' => 'Режим підключення',
        'manual_mode' => 'Тільки Project ID',
        'script_mode' => 'Повний HTML-код',
        'tracking_code' => 'Повний код Clarity',
        'tracking_code_help' => 'Необов’язково. Використовуйте, якщо хочете вставити повний скрипт із Clarity вручну.',
        'exclude_admin' => 'Не відстежувати адмін-панель',
        'exclude_admin_help' => 'Рекомендовано залишити ввімкненим.',
    ],
    'dashboard' => [
        'title' => 'Поведінкова аналітика сайту',
        'description' => 'Clarity показує записи сесій, теплові карти, популярні сторінки і місця, де користувачі застрягають.',
        'project_connected' => 'Проєкт підключено',
        'project_missing' => 'Project ID ще не вказано',
        'project_hint' => 'Підключіть існуючий проєкт або створіть новий у Microsoft Clarity. Після збереження скрипт автоматично з’явиться на сайті.',
        'open_settings' => 'Налаштування',
        'open_clarity' => 'Відкрити Clarity',
        'manual_project' => 'Ручне підключення',
        'manual_project_help' => 'Якщо iframe не передав ID автоматично, вставте Project ID сюди.',
        'save_project' => 'Зберегти Project ID',
        'embed_title' => 'Microsoft Clarity Dashboard',
    ],
];
