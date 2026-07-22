<?php

namespace Botble\Courses\Support;

class CourseOptions
{
    public static function levels(): array
    {
        return [
            'beginner' => trans('plugins/courses::courses.level_beginner'),
            'intermediate' => trans('plugins/courses::courses.level_intermediate'),
            'advanced' => trans('plugins/courses::courses.level_advanced'),
            'professional' => trans('plugins/courses::courses.level_professional'),
        ];
    }

    public static function software(): array
    {
        return [
            'blender' => 'Blender',
            'houdini' => 'Houdini',
            'houdini_apex' => 'Houdini APEX',
            'houdini_copernicus' => 'Houdini Copernicus',
            'zbrush' => 'ZBrush',
            'unreal_engine_5' => 'Unreal Engine 5',
            'unity' => 'Unity',
            'maya' => 'Maya',
            'cinema_4d' => 'Cinema 4D',
            '3ds_max' => '3ds Max',
            'substance_painter' => 'Substance Painter',
            'substance_designer' => 'Substance Designer',
            'photoshop' => 'Adobe Photoshop',
            'illustrator' => 'Adobe Illustrator',
            'after_effects' => 'After Effects',
            'nuke' => 'Nuke',
            'fusion' => 'Fusion',
            'davinci_resolve' => 'DaVinci Resolve',
            'auto_rig_pro' => 'Auto-Rig Pro',
            'rigify' => 'Rigify',
            'ai_tools' => trans('plugins/courses::courses.software_ai_tools'),
        ];
    }

    public static function skills(): array
    {
        return [
            'modeling' => trans('plugins/courses::courses.skill_modeling'),
            'procedural_modeling' => trans('plugins/courses::courses.skill_procedural_modeling'),
            'sculpting' => trans('plugins/courses::courses.skill_sculpting'),
            'retopology' => trans('plugins/courses::courses.skill_retopology'),
            'uv' => trans('plugins/courses::courses.skill_uv'),
            'shading' => trans('plugins/courses::courses.skill_shading'),
            'texturing' => trans('plugins/courses::courses.skill_texturing'),
            'rigging' => trans('plugins/courses::courses.skill_rigging'),
            'facial_rigging' => trans('plugins/courses::courses.skill_facial_rigging'),
            'animation' => trans('plugins/courses::courses.skill_animation'),
            'simulations' => trans('plugins/courses::courses.skill_simulations'),
            'vfx' => trans('plugins/courses::courses.skill_vfx'),
            'rendering' => trans('plugins/courses::courses.skill_rendering'),
            'compositing' => trans('plugins/courses::courses.skill_compositing'),
            'vr' => trans('plugins/courses::courses.skill_vr'),
            'product_ads' => trans('plugins/courses::courses.skill_product_ads'),
        ];
    }

    public static function saleStatuses(): array
    {
        return [
            'standard' => trans('plugins/courses::courses.sale_status_standard'),
            'early_access' => trans('plugins/courses::courses.sale_status_early_access'),
            'released' => trans('plugins/courses::courses.sale_status_released'),
            'closed' => trans('plugins/courses::courses.sale_status_closed'),
        ];
    }

    public static function visibilityModes(): array
    {
        return [
            'catalog' => trans('plugins/courses::courses.visibility_catalog'),
            'hidden' => trans('plugins/courses::courses.visibility_hidden'),
        ];
    }

    public static function priceTypes(): array
    {
        return [
            'paid' => trans('plugins/courses::courses.price_type_paid'),
            'free' => trans('plugins/courses::courses.price_type_free'),
        ];
    }

    public static function salesModes(): array
    {
        return [
            'immediate' => trans('plugins/courses::courses.sales_mode_immediate'),
            'scheduled' => trans('plugins/courses::courses.sales_mode_scheduled'),
            'preorder' => trans('plugins/courses::courses.sales_mode_preorder'),
            'closed' => trans('plugins/courses::courses.sales_mode_closed'),
        ];
    }

    public static function accessModes(): array
    {
        return [
            'immediate' => trans('plugins/courses::courses.access_mode_immediate'),
            'scheduled' => trans('plugins/courses::courses.access_mode_scheduled'),
            'gradual' => trans('plugins/courses::courses.access_mode_gradual'),
        ];
    }

    public static function publicationStates(): array
    {
        return [
            'draft' => 'Черновик',
            'published' => 'Опубликован',
            'hidden' => 'Скрыт',
            'scheduled' => 'Запланирован',
        ];
    }

    public static function studentRanks(): array
    {
        return [
            'newcomer' => trans('plugins/courses::courses.rank_newcomer'),
            'apprentice' => trans('plugins/courses::courses.rank_apprentice'),
            'maker' => trans('plugins/courses::courses.rank_maker'),
            'artist' => trans('plugins/courses::courses.rank_artist'),
            'mentor' => trans('plugins/courses::courses.rank_mentor'),
        ];
    }

    public static function galleryStatuses(): array
    {
        return [
            'draft' => trans('plugins/courses::courses.gallery_status_draft'),
            'pending' => trans('plugins/courses::courses.gallery_status_pending'),
            'approved' => trans('plugins/courses::courses.gallery_status_approved'),
            'rejected' => trans('plugins/courses::courses.gallery_status_rejected'),
        ];
    }

    public static function defaultCategories(): array
    {
        return [
            [
                'name' => '3D-моделювання',
                'slug' => '3d-modeling',
                'description' => 'Персонажі, пропси, продукти, світи та підготовка моделей для production.',
                'order' => 10,
            ],
            [
                'name' => 'Скульптинг персонажів',
                'slug' => 'character-sculpting',
                'description' => 'High-poly скульптинг, форми, деталізація, підготовка персонажів.',
                'order' => 20,
            ],
            [
                'name' => 'Ригінг',
                'slug' => 'rigging',
                'description' => 'Скелети, контролери, деформації, facial rig та підготовка до анімації.',
                'order' => 30,
            ],
            [
                'name' => 'Анімація',
                'slug' => 'animation',
                'description' => 'Пози, рух, актинг, цикли, product motion та персонажна анімація.',
                'order' => 40,
            ],
            [
                'name' => 'VFX',
                'slug' => 'vfx',
                'description' => 'Симуляції, процедурні ефекти, Houdini workflows, рендер і композитинг.',
                'order' => 50,
            ],
            [
                'name' => 'VR / Unreal Engine 5',
                'slug' => 'vr-unreal-engine-5',
                'description' => 'VR-світи, шоуруми, музеї, інтерактивні сцени та Unreal Engine 5.',
                'order' => 60,
            ],
            [
                'name' => 'Реклама продуктів',
                'slug' => 'product-commercials',
                'description' => '3D-ролики для продуктів, прототипів, презентацій та рекламних кампаній.',
                'order' => 70,
            ],
            [
                'name' => 'Композитинг',
                'slug' => 'compositing',
                'description' => 'After Effects, Nuke, фінальний збір кадру, кольорокорекція та шари.',
                'order' => 80,
            ],
        ];
    }
}
