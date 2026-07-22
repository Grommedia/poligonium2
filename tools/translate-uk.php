<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=poligonium;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$now = date('Y-m-d H:i:s');

$update = static function (string $table, array $values, string $where, array $params = []) use ($pdo): void {
    $sets = [];

    foreach ($values as $column => $_) {
        $sets[] = "`$column` = :$column";
    }

    $statement = $pdo->prepare(sprintf('UPDATE `%s` SET %s WHERE %s', $table, implode(', ', $sets), $where));
    $statement->execute([...$values, ...$params]);
};

$serviceDetail = static function (string $title): string {
    return <<<HTML
<h5 class="fs-5 fw-medium">Опис</h5>
<p class="text-300">{$title} виконується як повний виробничий цикл: від підготовки технічного завдання та стилю до фінальних файлів, готових для гри, реклами, презентації або віртуального виробництва.</p>
<h5 class="fs-5 fw-medium mt-4">Що входить</h5>
<ul>
    <li><p class="text-dark fw-bold">Аналіз задачі: <span class="text-300 fw-medium">узгодження стилю, референсів, формату, дедлайнів і технічних вимог.</span></p></li>
    <li><p class="text-dark fw-bold">3D-виробництво: <span class="text-300 fw-medium">моделювання, скульптинг, UV, текстури, матеріали, освітлення та підготовка сцени.</span></p></li>
    <li><p class="text-dark fw-bold">Оптимізація: <span class="text-300 fw-medium">адаптація моделей під рендер, Unreal Engine, Unity, WebGL або інший потрібний пайплайн.</span></p></li>
    <li><p class="text-dark fw-bold">Фінальна передача: <span class="text-300 fw-medium">експорт у потрібних форматах, прев'ю, рендери та рекомендації щодо використання.</span></p></li>
</ul>
HTML;
};

$homeContent = <<<'HTML'
<shortcode>[hero-banner style="1" title="Вітаю &lt;span&gt;у моєму&lt;/span&gt; 3D світі" subtitle="Мене звати Андрій Білецький" description="Я 3D-художник, що спеціалізується на моделюванні, анімації та візуальних ефектах. Створюю стилізовані й реалістичні ассети для ігор, реклами, відео та віртуального виробництва, працюючи з усім 3D-пайплайном." primary_button_text="Завантажити CV" primary_button_link="/storage/main/resume/cv.pdf" primary_button_icon="ti ti-download" open_primary_link_in_the_new_tab="yes" open_secondary_link_in_the_new_tab="no" below_button_text="...мої навички" quantity="6" name_1="Blender 3D" image_1="main/icon/blender-1.png" name_2="Houdini" image_2="main/icon/houdini.png" name_3="iClone 8" image_3="main/icon/iclone8-128-128.png" name_4="Photoshop" image_4="main/icon/phot128-128.png" name_5="Unreal Engine" image_5="main/icon/unreal126.png" name_6="After Effects" image_6="main/icon/ae.png" background_image="code/general/static-bg.png" background_image_dark="code/general/hero-bg-dark.png" right_image="main/i-am-4.png" right_image_shape="main/3d-astronaut-on-transparent-background-free-png.png" filter_gray_image_in_dark_mode="yes" enable_lazy_loading="no"][/hero-banner]</shortcode>
<shortcode>[stats-counter style="1" quantity="6" label_1="Років професійної практики" count_1="12" icon_1="ti ti-crown" label_2="Значущих проєктів" count_2="250" icon_2="ti ti-device-desktop" label_3="Задоволених клієнтів" count_3="680" icon_3="ti ti-heart-spark" label_4="Курсів з 3D" count_4="2" icon_4="ti ti-award" label_5="Авторських мультфільмів" count_5="3" label_6="Учнів-професіоналів" count_6="8" background_image="code/general/static-bg.png" enable_lazy_loading="no"][/stats-counter]</shortcode>
<shortcode>[services style="1" title="3D-виробництво для ігор, реклами та вебу" subtitle="Послуги" service_ids="1,2,3,4,5,6" bottom_text="Є задача для 3D? &lt;span class='text-dark'&gt;Зберемо стиль, ассети та фінальний рендер&lt;/span&gt; без зайвої бюрократії. &lt;br&gt;&lt;a href='/contact' class='text-primary-2'&gt;Написати мені&lt;/a&gt;" show_image_on_hover="yes" enable_lazy_loading="yes"][/services]</shortcode>
<shortcode>[experience title="+12 &lt;span&gt;років&lt;/span&gt; практики &lt;span&gt;від CAD-моделювання&lt;br /&gt;до 3D, VFX, реклами та курсів&lt;/span&gt;" subtitle="Досвід" role_title="3D Artist / Generalist, ФОП Білецький Андрій" role_description="Починав із CAD-моделювання в Inventor і Fusion 360, з 2016 року перейшов у 3D-моделювання, персонажів, анімацію та VFX. З 2023 року працюю як ФОП Білецький Андрій, веду комерційні 3D-проєкти та розвиваю навчальні курси. У планах на 2027-2028 роки — окремі курси з ригінгу персонажів і Houdini Rigging." experiences_quantity="7" experiences_date_1="2014 - 2016" experiences_title_1="CAD-моделювання: Inventor, Fusion 360" experiences_logo_1="code/experiences/google.png" experiences_date_2="2016 - 2022" experiences_title_2="Перехід у 3D-моделювання" experiences_logo_2="code/experiences/x.png" experiences_date_3="2023 - сьогодні" experiences_title_3="ФОП Білецький Андрій" experiences_logo_3="code/experiences/amazon.png" experiences_date_4="2024 - сьогодні" experiences_title_4="Професійна діяльність і створення курсів" experiences_logo_4="code/experiences/paypal.png" experiences_date_5="2025 - сьогодні" experiences_title_5="Blender, Houdini, VFX і реклама" experiences_logo_5="code/experiences/google.png" experiences_date_6="2026 - сьогодні" experiences_title_6="Мультиплікація, VFX та рекламний продакшн" experiences_logo_6="code/experiences/x.png" experiences_date_7="2027 - 2028" experiences_title_7="План: ригінг і Houdini Rigging" experiences_logo_7="code/experiences/amazon.png" skills_quantity="7" skills_name_1="Inventor" skills_name_2="Fusion 360" skills_name_3="Blender" skills_name_4="Houdini" skills_name_5="Unreal Engine" skills_name_6="VFX" skills_name_7="Rigging" background_image="code/general/services-bg.png" enable_lazy_loading="yes"][/experience]</shortcode>
<shortcode>[resume style="1" resume_1_title="Навчання" resume_1_title_icon="ti ti-school" resume_1_quantity="4" resume_1_time_1="2018 - 2019" resume_1_title_1="3D-моделювання та текстурування" resume_1_subtitle_1="Професійна практика" resume_1_time_2="2019 - 2020" resume_1_title_2="Анімація персонажів" resume_1_subtitle_2="Blender / iClone" resume_1_time_3="2020 - 2021" resume_1_title_3="VFX та процедурні симуляції" resume_1_subtitle_3="Houdini" resume_1_time_4="2021 - 2022" resume_1_title_4="Realtime-графіка" resume_1_subtitle_4="Unreal Engine" resume_2_title="Досвід" resume_2_title_icon="ti ti-stars" resume_2_quantity="7" resume_2_time_1="2014 - 2016" resume_2_title_1="CAD-моделювання" resume_2_subtitle_1="Autodesk Inventor / Fusion 360" resume_2_time_2="2016 - 2022" resume_2_title_2="3D-моделювання" resume_2_subtitle_2="Персонажі, ассети, візуалізація" resume_2_time_3="2023 - сьогодні" resume_2_title_3="ФОП Білецький Андрій" resume_2_subtitle_3="Комерційні 3D-проєкти та продакшн" resume_2_time_4="2024 - сьогодні" resume_2_title_4="Створення курсів" resume_2_subtitle_4="Професійна діяльність ФОП" resume_2_time_5="2025 - сьогодні" resume_2_title_5="Курси Blender та Houdini" resume_2_subtitle_5="Мультиплікація, VFX і реклама" resume_2_time_6="2026 - сьогодні" resume_2_title_6="Поточна професійна діяльність" resume_2_subtitle_6="3D, VFX, реклама, анімаційний напрям" resume_2_time_7="2027 - 2028" resume_2_title_7="План: курси з ригінгу" resume_2_subtitle_7="Ригінг персонажів і Houdini Rigging" enable_lazy_loading="yes"][/resume]</shortcode>
<shortcode>[projects style="2" title="Останні роботи" subtitle="Проєкти" project_ids="1,2,3,4" background_image="code/general/projects-bg.png" enable_lazy_loading="yes"][/projects]</shortcode>
<shortcode>[skills style="2" title="Мої навички" subtitle="Інструменти" quantity="9" name_1="Blender" image_1="code/skills/1.png" name_2="Houdini" image_2="code/skills/2.png" name_3="Cinema 4D" image_3="code/skills/3.png" name_4="Unreal Engine" image_4="code/skills/4.png" name_5="Photoshop" image_5="code/skills/5.png" name_6="After Effects" image_6="code/skills/6.png" name_7="Substance Painter" image_7="code/skills/7.png" name_8="iClone" image_8="code/skills/8.png" name_9="ZBrush" image_9="code/skills/9.png" list_quantity="5" list_label_1="Моделювання" list_content_1="Hard surface, персонажі, props, оптимізація" list_label_2="Текстурування" list_content_2="UV, PBR-матеріали, look development" list_label_3="Анімація" list_content_3="Ріггінг, motion, персонажна анімація" list_label_4="VFX" list_content_4="Houdini-симуляції, частинки, процедурні сцени" list_label_5="Рендер" list_content_5="Світло, композиція, фінальна подача" enable_lazy_loading="yes"][/skills]</shortcode>
<shortcode>[blog-posts paginate="3" style="1" title="Останні матеріали" subtitle="Блог" number_posts_per_row="3" enable_lazy_loading="yes"][/blog-posts]</shortcode>
<shortcode>[contact-form display_fields="phone,email,subject,address" mandatory_fields="email,subject" style="1" title="Зв'яжімося" quantity="4" label_1="Телефон" content_1="+380-98-223-2974" icon_1="ti ti-phone" url_1="tel:+380982232974" label_2="Telegram" content_2="@BeleckiyAndrey3D" icon_2="ti ti-brand-telegram" url_2="https://t.me/BeleckiyAndrey3D" label_3="Ім'я" content_3="Білецький Андрій" icon_3="ti ti-user" url_3="https://t.me/BeleckiyAndrey3D" label_4="Локація" content_4="Кременчук / Київ" icon_4="ti ti-map" url_4="https://maps.google.com/?q=Kremenchuk,Kyiv,Ukraine"][/contact-form]</shortcode>
HTML;

$pages = [
    1 => ['name' => 'Головна', 'description' => '3D Studio Poligonium: моделювання, персонажі, анімація, VFX та візуальні рішення для бізнесу.', 'content' => $homeContent],
    2 => ['name' => 'Послуги', 'description' => '3D-моделювання, персонажі, ригінг, анімація, рекламні ролики, VR-шоуруми та Unreal Engine 5 проєкти з орієнтовними цінами у гривні.'],
    3 => ['name' => 'Портфоліо', 'description' => 'Добірка 3D-проєктів Poligonium: персонажі, візуалізації, анімація та комерційні роботи.'],
    4 => ['name' => 'Ціни', 'description' => 'Гнучкі пакети для 3D-виробництва: від базової задачі до комплексного продакшну.', 'content' => '[pricing-plans package_ids=&quot;1,2&quot; enable_lazy_loading=&quot;no&quot;][/pricing-plans]' . PHP_EOL . '[faqs title=&quot;Поширені запитання&quot; category_ids=&quot;1&quot; enable_lazy_loading=&quot;no&quot;][/faqs]'],
    5 => ['name' => 'Курси', 'description' => 'Матеріали, уроки та нотатки про 3D-графіку, Blender, Houdini, анімацію і продакшн.'],
    6 => ['name' => 'Контакти', 'description' => 'Напишіть Poligonium, щоб обговорити 3D-проєкт, персонажа, анімацію або візуалізацію.', 'content' => '[contact-form style=&quot;2&quot; display_fields=&quot;phone,email,subject,address&quot; mandatory_fields=&quot;email,subject&quot; title=&quot;Зв&#039;яжімося&quot; quantity=&quot;4&quot; label_1=&quot;Телефон&quot; content_1=&quot;+380-98-223-2974&quot; icon_1=&quot;ti ti-phone&quot; url_1=&quot;tel:+380982232974&quot; label_2=&quot;Telegram&quot; content_2=&quot;@BeleckiyAndrey3D&quot; icon_2=&quot;ti ti-brand-telegram&quot; url_2=&quot;https://t.me/BeleckiyAndrey3D&quot; label_3=&quot;Ім&#039;я&quot; content_3=&quot;Білецький Андрій&quot; icon_3=&quot;ti ti-user&quot; url_3=&quot;https://t.me/BeleckiyAndrey3D&quot; label_4=&quot;Локація&quot; content_4=&quot;Кременчук / Київ&quot; icon_4=&quot;ti ti-map&quot; url_4=&quot;https://maps.google.com/?q=Kremenchuk,Kyiv,Ukraine&quot;][/contact-form]'],
    7 => ['name' => 'Політика cookies', 'description' => 'Інформація про використання cookies на сайті Poligonium.', 'content' => '<h3>Використання cookies</h3><p>Сайт використовує cookies, щоб коректно працювати, зберігати сесію користувача та захищати форми від небажаних запитів.</p><h4>Обов’язкові дані</h4><p>Ці cookies потрібні для технічної роботи сайту, тому їх не можна вимкнути без втрати базової функціональності.</p><p>- Session Cookie: допомагає сайту розпізнавати поточну сесію.</p><p>- XSRF-Token Cookie: використовується Laravel для захисту форм і перевірки справжності запитів.</p>'],
];

foreach ($pages as $id => $data) {
    $data['updated_at'] = $now;
    $update('pages', $data, 'id = :id', ['id' => $id]);
}

$menuTitles = [
    10 => 'Категорії',
    14 => 'Мої сервіси',
    15 => 'Портфоліо',
    16 => 'Ціни на послуги',
    17 => 'Курси',
    18 => 'Контакти',
    19 => 'Послуги 3D та 2D',
    22 => 'Кар’єрний шлях',
];

foreach ($menuTitles as $id => $title) {
    $update('menu_nodes', ['title' => $title, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$categoryDescriptions = [
    2 => ['Open Source Contributions', 'Матеріали про відкриті 3D-інструменти, корисні ресурси та внесок у спільноту.'],
    3 => ['Уроки', 'Практичні уроки з Blender, Houdini, анімації, рендеру та 3D-пайплайну.'],
    4 => ['Огляди технологій', 'Огляди інструментів, плагінів і технологій для сучасного 3D-виробництва.'],
    5 => ['Особистий блог', 'Нотатки про досвід, творчий процес, роботу з клієнтами та розвиток у 3D.'],
    6 => ['Кар’єрний шлях', 'Досвід, висновки та історії з професійного шляху у 3D-графіці.'],
    7 => ['Творчі виклики', 'Експерименти, вправи та задачі для розвитку 3D-навичок.'],
    8 => ['Портфоліо дизайну', 'Добірка візуальних рішень, стилів і презентаційних робіт.'],
    9 => ['Співпраця', 'Матеріали про командну роботу, брифінг, комунікацію та запуск проєктів.'],
];

foreach ($categoryDescriptions as $id => [$name, $description]) {
    $update('categories', ['name' => $name, 'description' => $description, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$services = [
    1 => ['3D API та технічна інтеграція', 'Підготовка 3D-ассетів і сцен для інтеграції у веб, застосунки, ігрові рушії та інтерактивні презентації.'],
    2 => ['3D для вебу та інтерактиву', 'Створення оптимізованих 3D-моделей, анімацій і візуальних елементів для сайтів, лендингів та презентацій.'],
    3 => ['3D для мобільних застосунків', 'Легкі та оптимізовані 3D-ассети для мобільних застосунків, AR-сцен, ігор і product-візуалізації.'],
    4 => ['3D-персонажі', 'Створення стилізованих і реалістичних персонажів: скульптинг, ретопологія, текстури, ріггінг та анімація.'],
    5 => ['Хмарна 3D-візуалізація', 'Підготовка матеріалів для онлайн-перегляду, презентацій, каталогів і сервісів з інтерактивною 3D-графікою.'],
    6 => ['Керування 3D-ассетами', 'Оптимізація, структурування, експорт і технічна підготовка бібліотек моделей для стабільної роботи у продакшні.'],
];

foreach ($services as $id => [$name, $description]) {
    $update('pf_services', ['name' => $name, 'description' => $description, 'content' => $serviceDetail($name), 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$projectContent = <<<HTML
<h5 class="fs-5 fw-medium">Опис</h5>
<p class="text-300">Проєкт виконано з фокусом на сильну візуальну подачу, чисту 3D-форму та готовність матеріалів до реального використання у бізнесі, рекламі або цифровому продукті.</p>
<h5 class="fs-5 fw-medium mt-4">Ключові задачі</h5>
<ul>
    <li><p class="text-dark fw-bold">Візуальна концепція: <span class="text-300 fw-medium">підбір стилю, композиції, матеріалів і настрою сцени.</span></p></li>
    <li><p class="text-dark fw-bold">3D-виробництво: <span class="text-300 fw-medium">моделювання, текстурування, освітлення, рендер і фінальна обробка.</span></p></li>
    <li><p class="text-dark fw-bold">Технічна підготовка: <span class="text-300 fw-medium">оптимізація файлів, експорт і передача матеріалів у потрібних форматах.</span></p></li>
</ul>
HTML;

$projects = [
    1 => ['3D CRM-візуалізація', 'Візуальна система для презентації складного цифрового продукту через зрозумілі 3D-сцени.', 'Корпоративний клієнт'],
    2 => ['Освітня 3D-платформа', 'Набір 3D-матеріалів і візуальних рішень для навчального продукту.', 'Освітній стартап'],
    3 => ['3D-концепт мобільного банкінгу', 'Серія преміальних 3D-візуалізацій для фінансового застосунку.', 'Фінансова компанія'],
    4 => ['Хмарна 3D-інфраструктура', 'Візуальна презентація технічної інфраструктури через 3D-графіку та анімацію.', 'Технологічна компанія'],
];

foreach ($projects as $id => [$name, $description, $client]) {
    $update('pf_projects', ['name' => $name, 'description' => $description, 'content' => $projectContent, 'client' => $client, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$packages = [
    1 => ['Базовий', 'Для невеликих задач, тестових ідей і стартових 3D-матеріалів.', 'від $49', "Потрібне технічне завдання або референси\nБазове 3D-моделювання чи правки\nПідготовка рендеру або прев'ю\nДистанційна співпраця\nРобочі дні\n1 місяць підтримки"],
    2 => ['Бізнес', 'Для комплексних 3D-проєктів, рекламних матеріалів і продакшну.', 'від $99', "Допомога з брифом і референсами\nПовний 3D-пайплайн під задачу\nРендери, анімація або підготовка ассетів\nДистанційна співпраця\nПріоритетна робота з проєктом\n3 місяці підтримки\nФінальні файли у потрібних форматах\nРекомендації щодо використання"],
];

foreach ($packages as $id => [$name, $description, $price, $features]) {
    $update('pf_packages', ['name' => $name, 'description' => $description, 'price' => $price, 'features' => $features, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$update('faq_categories', ['name' => 'Послуги', 'updated_at' => $now], 'id = 1');
$update('faq_categories', ['name' => 'Вартість і оплата', 'updated_at' => $now], 'id = 2');
$update('faq_categories', ['name' => 'Підтримка після здачі', 'updated_at' => $now], 'id = 3');

$contactWidget = [
    'bio' => 'З радістю беруся за нові 3D-проєкти та допомагаю перетворити ідею на виразну візуальну історію.',
    'details' => [
        'Phone' => [['key' => 'label', 'value' => 'Телефон'], ['key' => 'value', 'value' => '+380-98-223-2974']],
        'Email' => [['key' => 'label', 'value' => 'Email'], ['key' => 'value', 'value' => 'contact@poligonium.com']],
        'Website' => [['key' => 'label', 'value' => 'Сайт'], ['key' => 'value', 'value' => 'https://poligonium.com']],
        'Address' => [['key' => 'label', 'value' => 'Локація'], ['key' => 'value', 'value' => 'Кременчук / Київ']],
    ],
];

$update('widgets', ['data' => json_encode($contactWidget, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'updated_at' => $now], 'id = 13');
$update('widgets', ['data' => json_encode(['name' => 'Соцмережі'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'updated_at' => $now], 'id = 14');
$update('widgets', ['data' => json_encode(['name' => 'Соцмережі'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'updated_at' => $now], 'id = 17');

$footerMenu = [
    'id' => 'Botble\\Widget\\Widgets\\CoreSimpleMenu',
    'items' => [
        [['key' => 'label', 'value' => 'Головна'], ['key' => 'url', 'value' => 'https://poligonium.com']],
        [['key' => 'label', 'value' => 'Послуги'], ['key' => 'url', 'value' => 'https://poligonium.com/services']],
        [['key' => 'label', 'value' => 'Ціни'], ['key' => 'url', 'value' => 'https://poligonium.com/pricing']],
        [['key' => 'label', 'value' => 'Курси'], ['key' => 'url', 'value' => 'https://poligonium.com/blog']],
        [['key' => 'label', 'value' => 'Контакти'], ['key' => 'url', 'value' => 'https://poligonium.com/contact']],
    ],
];

$update('widgets', ['data' => json_encode($footerMenu, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'updated_at' => $now], 'id = 18');

$update('settings', ['value' => 'Poligonium.com - 3D студія | Blender, Cinema 4D, Houdini | Візуальні рішення для бізнесу', 'updated_at' => $now], '`key` = :key', ['key' => 'theme-zelio-site_title']);
$update('settings', ['value' => '3D Studio Poligonium: моделювання, персонажі, анімація, VFX, рендер і візуальні рішення для бізнесу, реклами та цифрових продуктів.', 'updated_at' => $now], '`key` = :key', ['key' => 'theme-zelio-seo_description']);

$faqs = [
    1 => ['Що входить у 3D-послуги?', 'Залежно від задачі я можу виконати моделювання, скульптинг, текстурування, ріггінг, анімацію, VFX, рендер і підготовку файлів до передачі.'],
    2 => ['Як 3D-візуалізація допоможе бізнесу?', 'Якісна 3D-графіка допомагає краще показати продукт, підсилити рекламу, зробити презентацію зрозумілішою та виділити бренд серед конкурентів.'],
    3 => ['Які саме роботи можна замовити?', 'Можна замовити 3D-персонажів, предметну візуалізацію, анімацію, ассети для ігор, VFX-сцени, рендери для реклами та інтерактивні 3D-матеріали.'],
    4 => ['Як формується вартість?', 'Вартість залежить від складності моделі, деталізації, кількості правок, формату фінальних файлів і термінів. Після короткого брифу я пропоную оптимальний пакет.'],
    5 => ['З якими напрямами ви працюєте?', 'Працюю з іграми, рекламою, продуктами, архівізом, презентаціями, навчальними матеріалами та авторськими творчими проєктами.'],
    6 => ['Чи можна побачити приклади робіт?', 'Так, приклади доступні у портфоліо. За потреби я також можу показати релевантні кейси під вашу задачу.'],
    7 => ['Як проходить співпраця?', 'Спочатку узгоджуємо задачу, референси, стиль і технічні вимоги. Далі я готую проміжні прев’ю, збираю фідбек і доводжу матеріал до фінального результату.'],
    8 => ['Скільки часу займає типовий проєкт?', 'Невеликі задачі можуть займати кілька днів, комплексні персонажі або анімації - від кількох тижнів. Точний термін залежить від обсягу робіт.'],
    9 => ['Хто працює над проєктом?', 'Основну 3D-частину виконую я. Якщо задача потребує додаткових спеціалістів, це обговорюється окремо до старту.'],
    10 => ['Як ви працюєте з конфіденційними матеріалами?', 'Усі матеріали клієнта використовуються тільки для виконання проєкту. За потреби можна узгодити NDA або окремі умови нерозголошення.'],
    11 => ['Чи є підтримка після здачі?', 'Так, після передачі файлів можливі консультації, дрібні уточнення та технічна допомога в межах узгодженого пакета.'],
    12 => ['Що буде, якщо проєкт потрібно скасувати?', 'Умови скасування залежать від етапу роботи. Зазвичай оплачується вже виконана частина, а всі невикористані домовленості узгоджуються окремо.'],
    13 => ['Чи оптимізуєте ви моделі для продуктивності?', 'Так, можу підготувати low-poly, LOD, оптимізовані текстури, правильні формати експорту та матеріали для рушіїв або вебу.'],
    14 => ['Чи працюєте дистанційно?', 'Так, усі етапи можна вести онлайн: бриф, прев’ю, правки, передача файлів і фінальна консультація.'],
    15 => ['Чи можна інтегрувати 3D у сайт або застосунок?', 'Так, я готую ассети для WebGL, інтерактивних презентацій, мобільних застосунків і рушіїв на кшталт Unreal Engine або Unity.'],
    16 => ['Чим відрізняється ваш підхід?', 'Я поєдную художню якість із технічною підготовкою: матеріали мають не лише гарно виглядати, а й працювати у потрібному середовищі.'],
    17 => ['Як ви стежите за актуальністю інструментів?', 'Постійно тестую нові інструменти, пайплайни та підходи у Blender, Houdini, Unreal Engine, AI-assisted workflow та суміжних напрямах.'],
    18 => ['Як контролюється якість?', 'Я перевіряю модель, текстури, масштаб, матеріали, рендер і експортні файли. На ключових етапах надсилаю прев’ю, щоб узгодити результат до фіналу.'],
];

foreach ($faqs as $id => [$question, $answer]) {
    $update('faqs', ['question' => $question, 'answer' => $answer, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$postContent = <<<HTML
<h5>Практичний підхід до 3D-виробництва</h5>
<h5 class="fs-5 fw-medium">Зрозумійте задачу перед стартом</h5>
<p class="text-300">Перед початком роботи важливо визначити стиль, референси, технічні вимоги, формат фінальних файлів і сценарій використання матеріалу.</p>
<h5 class="fs-5 fw-medium mt-4">Працюйте поетапно</h5>
<p class="text-300">Найкращий результат дає послідовний процес: блокінг, деталізація, матеріали, світло, тестовий рендер, правки та фінальний експорт.</p>
<h5 class="fs-5 fw-medium mt-4">Думайте про фінальне середовище</h5>
<p class="text-300">Модель для реклами, гри, сайту або анімації має різні вимоги. Саме тому оптимізація, структура сцени й формат передачі мають бути частиною роботи з першого етапу.</p>
HTML;

$posts = [
    1 => ['Як зібрати повний 3D-пайплайн для проєкту', 'Практичний погляд на етапи 3D-виробництва: від брифу та референсів до фінального рендеру або передачі ассетів.'],
    2 => ['Мій шлях у 3D: досвід, помилки та розвиток', 'Особисті висновки про роботу з 3D-графікою, навчання, комерційні задачі та пошук власного стилю.'],
    3 => ['5 інструментів, які варто знати 3D-художнику', 'Добірка корисних інструментів для моделювання, текстурування, анімації, рендеру та оптимізації.'],
    4 => ['Як я будую портфоліо 3D-робіт', 'Поради щодо структури портфоліо, вибору кейсів, презентації процесу та підготовки робіт для клієнтів.'],
    5 => ['Як підготувати 3D-візуалізацію для адаптивного сайту', 'Що врахувати під час оптимізації моделей, рендерів і інтерактивних елементів для вебу.'],
    6 => ['Чому 3D-спільнота важлива для розвитку', 'Про користь відкритих ресурсів, обміну досвідом, фідбеку та участі у творчих 3D-челенджах.'],
    7 => ['Blender для початківців: з чого стартувати', 'Базова дорожня карта для тих, хто хоче впевнено почати працювати з Blender і не загубитися в інструментах.'],
    8 => ['Як організувати бібліотеку 3D-ассетів', 'Поради щодо структури файлів, назв, текстур, форматів експорту та повторного використання матеріалів.'],
    9 => ['Як інтегрувати 3D у цифровий продукт', 'Коротко про WebGL, рушії, формати файлів і підготовку моделей для інтерактивних сценаріїв.'],
    10 => ['Найкращі практики для зрозумілої 3D-презентації', 'Як композиція, світло, матеріали та ракурс допомагають краще пояснити продукт або ідею.'],
    11 => ['Уроки з перших комерційних 3D-проєктів', 'Що важливо знати про бриф, дедлайни, правки, комунікацію та передачу фінальних файлів.'],
    12 => ['Як почати робити 3D-проєкти для портфоліо', 'Покроковий підхід до вибору теми, планування сцени, виконання та оформлення кейсу.'],
    13 => ['Оптимізація 3D-сцен для швидкого рендеру', 'Практичні способи прискорити рендер і не втратити якість фінального зображення.'],
    14 => ['Мої улюблені 3D-проєкти та чому вони працюють', 'Огляд рішень, які допомагають 3D-роботам виглядати професійно та переконливо.'],
    15 => ['Тренди 3D-графіки у 2024 році', 'Короткий огляд напрямів, які впливають на 3D-продакшн: realtime, procedural, AI-assisted workflow та інтерактив.'],
];

foreach ($posts as $id => [$name, $description]) {
    $update('posts', ['name' => $name, 'description' => $description, 'content' => $postContent, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$tags = [
    1 => 'Botble CMS',
    2 => '3D-графіка',
    3 => 'Open Source',
    4 => 'Веб-дизайн',
    5 => '3D-інтеграція',
    6 => 'Повний пайплайн',
    7 => 'Український 3D-художник',
    8 => 'Портфоліо',
];

foreach ($tags as $id => $name) {
    $update('tags', ['name' => $name, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

$testimonials = [
    1 => ['Олена Коваль', '“Робота була дуже комфортною: уважність до деталей, чітка комунікація та якісний фінальний результат зробили процес справді спокійним.”', 'Задоволена клієнтка'],
    2 => ['Максим Руденко', '“3D-візуалізація допомогла краще подати продукт і швидко пояснити ідею команді та партнерам. Результат перевершив очікування.”', 'Підприємець'],
    3 => ['Ірина Шевченко', '“Проєкт виконано акуратно, зі смаком і вчасно. Особливо сподобалося, що всі правки були враховані без зайвої плутанини.”', 'Маркетинг-менеджер'],
    4 => ['Данило Мельник', '“Від першого брифу до фінальних файлів усе було структуровано. 3D-матеріали одразу можна було використовувати в презентації.”', 'Продюсер проєкту'],
];

foreach ($testimonials as $id => [$name, $content, $company]) {
    $update('testimonials', ['name' => $name, 'content' => $content, 'company' => $company, 'updated_at' => $now], 'id = :id', ['id' => $id]);
}

echo "Ukrainian content updated.\n";
