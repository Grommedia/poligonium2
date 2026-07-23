<?php

use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('plg_academy_categories')) {
            Schema::create('plg_academy_categories', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('color', 30)->nullable();
                $table->integer('order')->default(0);
                $table->string('status', 60)->default(BaseStatusEnum::PUBLISHED);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('plg_academy_articles')) {
            Schema::create('plg_academy_articles', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('plg_academy_categories')->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('cover_image')->nullable();
                $table->string('difficulty')->nullable();
                $table->string('software')->nullable();
                $table->string('skills')->nullable();
                $table->string('cta_label')->nullable();
                $table->string('cta_url')->nullable();
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->unsignedInteger('reading_time')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->integer('order')->default(0);
                $table->timestamp('published_at')->nullable();
                $table->string('status', 60)->default(BaseStatusEnum::PUBLISHED);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('plg_academy_categories_translations')) {
            Schema::create('plg_academy_categories_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_academy_categories_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();

                $table->primary(['lang_code', 'plg_academy_categories_id'], 'plg_academy_categories_trans_primary');
            });
        }

        if (! Schema::hasTable('plg_academy_articles_translations')) {
            Schema::create('plg_academy_articles_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_academy_articles_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();

                $table->primary(['lang_code', 'plg_academy_articles_id'], 'plg_academy_articles_trans_primary');
            });
        }

        $this->seedDefaults();
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_academy_articles_translations');
        Schema::dropIfExists('plg_academy_categories_translations');
        Schema::dropIfExists('plg_academy_articles');
        Schema::dropIfExists('plg_academy_categories');
    }

    private function seedDefaults(): void
    {
        if (DB::table('plg_academy_categories')->exists()) {
            return;
        }

        $now = Carbon::now();

        $categories = [
            [
                'name' => 'Основи 3D-моделювання',
                'slug' => 'osnovy-3d-modeliuvannia',
                'description' => 'Базові матеріали для старту у Blender, Houdini та виробничій логіці 3D.',
                'icon' => 'ti ti-cube',
                'color' => '#2563eb',
                'order' => 0,
            ],
            [
                'name' => 'Персонажі та ригінг',
                'slug' => 'personazhi-ta-ryhing',
                'description' => 'Створення персонажів, підготовка до анімації, пози, міміка та пайплайн.',
                'icon' => 'ti ti-mood-smile',
                'color' => '#f97316',
                'order' => 10,
            ],
            [
                'name' => 'VFX та реклама',
                'slug' => 'vfx-ta-reklama',
                'description' => 'Практика роликів, продуктова анімація, композитинг і візуальні ефекти.',
                'icon' => 'ti ti-sparkles',
                'color' => '#16a34a',
                'order' => 20,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('plg_academy_categories')->insert(array_merge($category, [
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $categoryIds = DB::table('plg_academy_categories')->pluck('id', 'slug');

        $articles = [
            [
                'category_id' => $categoryIds['osnovy-3d-modeliuvannia'] ?? null,
                'name' => 'Що таке 3D-моделювання і з чого почати',
                'slug' => 'shcho-take-3d-modeliuvannia-i-z-choho-pochaty',
                'description' => 'Короткий практичний вступ: що робить 3D-моделер, які програми потрібні та як не загубитися на старті.',
                'content' => '<p>3D-моделювання — це створення цифрових обʼєктів, персонажів, реквізиту, техніки та середовищ, які можна використовувати у рекламі, мультфільмах, іграх, VR або навчальних матеріалах.</p><p>Починати краще не з десятка програм одночасно, а з одного зрозумілого маршруту: форма, пропорції, матеріали, світло, рендер і базова анімація. Для старту достатньо Blender, а Houdini та ZBrush варто підключати тоді, коли зʼявляються складні задачі.</p><p>В Академії Polygonium ми збираємо матеріали так, щоб людина бачила не тільки кнопки в програмі, а й логіку виробництва: як народжується ідея, як модель стає чистою, як вона отримує матеріали, риг і рух.</p>',
                'difficulty' => 'Початковий',
                'software' => 'Blender, Houdini',
                'skills' => '3D-моделювання, пайплайн, рендер',
                'cta_label' => 'Перейти до курсів',
                'cta_url' => '/courses',
                'reading_time' => 4,
                'is_featured' => true,
                'order' => 0,
            ],
            [
                'category_id' => $categoryIds['personazhi-ta-ryhing'] ?? null,
                'name' => 'Як створюється 3D-персонаж: від концепту до анімації',
                'slug' => 'yak-stvoriuietsia-3d-personazh-vid-kontseptu-do-animatsii',
                'description' => 'Розбираємо шлях персонажа: концепт, скульптинг, ретопологія, текстури, ригінг, пози, анімація і рендер.',
                'content' => '<p>Сильний 3D-персонаж не починається з полігонів. Спочатку формується характер: хто він, як рухається, які деталі одягу чи тіла підкреслюють образ. Потім іде скульптинг або high-poly модель, де зʼявляється пластика форми.</p><p>Далі модель готують до виробництва: ретопологія робить сітку керованою, текстурування додає матеріали, а ригінг перетворює статичну модель на персонажа, здатного рухатися. Для рекламного ролика чи мультфільму важливий не тільки гарний рендер, а й виразність поз, міміки та таймінгу.</p><p>Саме тому портфоліо персонажів Polygonium показує не одну красиву картинку, а цілий шлях від ідеї до готового медійного героя.</p>',
                'difficulty' => 'Середній',
                'software' => 'Blender, ZBrush, Substance 3D Painter, Houdini',
                'skills' => 'персонаж, ригінг, текстурування, анімація',
                'cta_label' => 'Дивитися портфоліо персонажів',
                'cta_url' => '/projects',
                'reading_time' => 5,
                'is_featured' => true,
                'order' => 10,
            ],
            [
                'category_id' => $categoryIds['vfx-ta-reklama'] ?? null,
                'name' => '3D-анімація для реклами: чому продукту потрібен рух',
                'slug' => '3d-animatsiia-dlia-reklamy-chomu-produktu-potriben-rukh',
                'description' => 'Як 3D-ролик пояснює продукт, показує процеси, підсилює довіру і працює краще за статичну картинку.',
                'content' => '<p>Рекламна 3D-анімація дає можливість показати те, що складно зняти камерою: внутрішню будову пристрою, монтаж матеріалів, роботу механізму або фантазійний світ бренду.</p><p>У таких проєктах важливі три речі: зрозумілий сценарій, точне моделювання продукту і ритм монтажу. Глядач має швидко зрозуміти, що відбувається, чому це корисно і чому продукт виглядає переконливо.</p><p>У VFX Showreel Polygonium зібрані ролики для продуктів, технологій, рекламних кампаній та візуалізацій, де 3D працює як комерційний інструмент.</p>',
                'difficulty' => 'Просунутий',
                'software' => 'Blender, Houdini, After Effects, Nuke, Unreal Engine',
                'skills' => 'VFX, продуктова анімація, композитинг, motion design',
                'cta_label' => 'Відкрити VFX Showreel',
                'cta_url' => '/vfx-showreel',
                'reading_time' => 4,
                'is_featured' => false,
                'order' => 20,
            ],
        ];

        foreach ($articles as $article) {
            DB::table('plg_academy_articles')->insert(array_merge($article, [
                'status' => BaseStatusEnum::PUBLISHED,
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
};
