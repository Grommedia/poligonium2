<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseCategory;
use Botble\Courses\Models\CourseChapter;
use Botble\Courses\Models\CourseLesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$courseData = [
    'name' => 'Моделювання та анімація чарівного павука',
    'description' => 'Практичний курс зі створення стилізованого 3D-павука: скляна колба як тіло, металеві лапки, магічні деталі, матеріали, риг і базова анімація для короткого ролика.',
    'content' => '<p>На курсі ми пройдемо повний невеликий production-процес: від ідеї та блокінгу форми до фінальної моделі, матеріалів скла й металу, простого ригу та анімаційного руху павука.</p><p>Головний обʼєкт курсу - чарівний павук, у якого тіло зроблене зі скляної хімічної колби, а лапки з металевих механічних елементів. Такий проєкт добре показує роботу з формою, матеріалами, деталізацією та підготовкою моделі до анімації.</p><p>Курс підійде для тих, хто хоче не просто повторити модель, а зрозуміти логіку створення невеликого персонажа або пропса для портфоліо.</p>',
];

$chapters = [
    [
        'name' => 'Концепт і підготовка форми',
        'description' => 'Визначаємо образ павука, збираємо референси, розкладаємо модель на прості частини та готуємо план роботи.',
        'order' => 10,
        'free' => true,
        'lessons' => [
            ['Ідея: павук зі скляною колбою', 18, true, 'Розбираємо задум: силует, характер, матеріали, референси скла, металу й магічних деталей.'],
            ['Блокінг тіла та лапок', 28, false, 'Створюємо базові пропорції: колба, сегменти, опори лапок, первинний силует павука.'],
        ],
    ],
    [
        'name' => 'Моделювання павука',
        'description' => 'Збираємо чисту модель: скляне тіло, металеві лапки, кріплення, декоративні магічні елементи.',
        'order' => 20,
        'free' => false,
        'lessons' => [
            ['Скляна хімічна колба як тіло', 34, false, 'Моделюємо форму колби, товщину скла, горлечко, внутрішній обʼєм і місця кріплення.'],
            ['Металеві механічні лапки', 42, false, 'Створюємо лапки з шарнірами, трубками, пластинами та дрібними технічними деталями.'],
            ['Деталі, декоративні елементи і чистка моделі', 30, false, 'Додаємо магічні акценти, перевіряємо силует, прибираємо зайве й готуємо модель до матеріалів.'],
        ],
    ],
    [
        'name' => 'Матеріали, світло і рендер',
        'description' => 'Налаштовуємо матеріали скла, металу, внутрішнього світіння та базову презентацію моделі.',
        'order' => 30,
        'free' => false,
        'lessons' => [
            ['Скло, метал і магічне світіння', 36, false, 'Робимо прозоре скло, металеві поверхні, емісію та баланс між декоративністю й читабельністю.'],
            ['Сцена, освітлення і тестовий рендер', 24, false, 'Ставимо просту студійну сцену, перевіряємо матеріали й робимо тестові кадри для контролю якості.'],
        ],
    ],
    [
        'name' => 'Риг і базова анімація',
        'description' => 'Готуємо простий риг лапок і робимо короткий анімаційний рух для демонстрації персонажа.',
        'order' => 40,
        'free' => false,
        'lessons' => [
            ['Простий риг лапок і перша анімація', 33, false, 'Створюємо керування лапками, налаштовуємо базовий рух і готуємо павука до короткого ролика.'],
        ],
    ],
];

$course = null;

DB::transaction(function () use (&$course, $courseData, $chapters): void {
    $category = CourseCategory::query()->where('slug', '3d-modeling')->first() ?: CourseCategory::query()->first();

    $course = Course::query()->firstOrNew(['slug' => 'magic-spider-modeling-animation']);
    $course->fill([
        'category_id' => $category?->id,
        'name' => $courseData['name'],
        'description' => $courseData['description'],
        'content' => $courseData['content'],
        'image' => null,
        'intro_video' => null,
        'difficulty' => 'intermediate',
        'software' => ['blender', 'houdini', 'photoshop', 'ai_tools'],
        'skills' => ['modeling', 'texturing', 'rigging', 'animation', 'rendering'],
        'duration_minutes' => 0,
        'lesson_count' => 0,
        'price' => 0,
        'currency' => 'UAH',
        'is_featured' => true,
        'is_free_preview' => true,
        'order' => 10,
        'status' => BaseStatusEnum::PUBLISHED,
    ]);
    $course->save();

    CourseLesson::query()->where('course_id', $course->id)->delete();
    CourseChapter::query()->where('course_id', $course->id)->delete();

    foreach ($chapters as $chapterData) {
        $chapter = CourseChapter::query()->create([
            'course_id' => $course->id,
            'name' => $chapterData['name'],
            'description' => $chapterData['description'],
            'order' => $chapterData['order'],
            'is_free_preview' => $chapterData['free'],
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        foreach ($chapterData['lessons'] as $index => $lessonData) {
            CourseLesson::query()->create([
                'course_id' => $course->id,
                'chapter_id' => $chapter->id,
                'name' => $lessonData[0],
                'description' => $lessonData[3],
                'content' => '<p>' . e($lessonData[3]) . '</p><p>Після завантаження відео цей урок можна доповнити матеріалами, таймкодами, файлами проєкту та домашнім завданням.</p>',
                'video_path' => null,
                'video_embed' => null,
                'duration_minutes' => $lessonData[1],
                'order' => $chapterData['order'] + $index + 1,
                'is_free_preview' => $lessonData[2],
                'requires_access' => ! $lessonData[2],
                'status' => BaseStatusEnum::PUBLISHED,
            ]);
        }
    }

    $course->forceFill([
        'lesson_count' => CourseLesson::query()->where('course_id', $course->id)->count(),
        'duration_minutes' => CourseLesson::query()->where('course_id', $course->id)->sum('duration_minutes'),
    ])->save();

    if (Schema::hasTable('plg_courses_translations')) {
        DB::table('plg_courses_translations')->updateOrInsert(
            ['lang_code' => 'uk', 'plg_courses_id' => $course->id],
            $courseData
        );

        DB::table('plg_courses_translations')->updateOrInsert(
            ['lang_code' => 'en', 'plg_courses_id' => $course->id],
            [
                'name' => 'Modeling and Animation of a Magic Spider',
                'description' => 'A practical course on creating a stylized magic spider with a glass flask body, metal legs, materials, rigging and basic animation.',
                'content' => '<p>A practical production-style course for creating a stylized magic spider from concept to animation.</p>',
            ]
        );
    }
});

$course->refresh()->load(['category', 'chapters.lessons']);

echo json_encode([
    'id' => $course->id,
    'name' => $course->name,
    'slug' => $course->slug,
    'category' => $course->category->name,
    'lesson_count' => $course->lesson_count,
    'duration_minutes' => $course->duration_minutes,
    'admin_edit_url' => route('courses.courses.edit', $course->id),
    'public_url' => route('courses.public.show', $course->slug),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
