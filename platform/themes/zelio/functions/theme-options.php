<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\Facades\Theme;
use Botble\Theme\ThemeOption\Fields\ColorField;
use Botble\Theme\ThemeOption\Fields\MediaImageField;
use Botble\Theme\ThemeOption\Fields\SelectField;
use Botble\Theme\ThemeOption\Fields\TextareaField;
use Botble\Theme\ThemeOption\Fields\TextField;
use Botble\Theme\ThemeOption\Fields\UiSelectorField;
use Botble\Theme\ThemeOption\ThemeOptionSection;

app('events')->listen(RenderingThemeOptionSettings::class, function (): void {
    theme_option()
        ->setField(
            ColorField::make()
                ->sectionId('styles')
                ->name('primary_color')
                ->label(__('Primary color'))
                ->defaultValue('#6e4ef2')
        )
        ->setField(
            ColorField::make()
                ->sectionId('styles')
                ->name('gradient_color')
                ->label(__('Gradient color'))
                ->defaultValue('#8c71ff')
        )
        ->setSection(
            ThemeOptionSection::make('footer')
                ->title(__('Footer'))
                ->icon('ti ti-brush')
                ->priority(5)
                ->fields([
                    MediaImageField::make()
                        ->sectionId('opt-text-subsection-general')
                        ->name('footer_background')
                        ->label(__('Footer background')),
                    TextField::make()
                        ->name('poligonium_footer_owner_uk')
                        ->label('Футер: власник / автор (UA)')
                        ->defaultValue('Білецький Андрій'),
                    TextField::make()
                        ->name('poligonium_footer_owner_en')
                        ->label('Footer: owner / author (EN)')
                        ->defaultValue('Biletskyi Andrii'),
                    TextField::make()
                        ->name('poligonium_footer_phone')
                        ->label('Футер: телефон')
                        ->defaultValue('+380-98-223-2974'),
                    TextField::make()
                        ->name('poligonium_footer_telegram')
                        ->label('Футер: Telegram')
                        ->defaultValue('@BeleckiyAndrey3D'),
                    TextField::make()
                        ->name('poligonium_footer_location_uk')
                        ->label('Футер: локація (UA)')
                        ->defaultValue('Кременчук / Київ, Україна'),
                    TextField::make()
                        ->name('poligonium_footer_location_en')
                        ->label('Footer: location (EN)')
                        ->defaultValue('Kremenchuk / Kyiv, Ukraine'),
                    TextareaField::make()
                        ->name('poligonium_footer_directions_uk')
                        ->label('Футер: напрями (UA)')
                        ->defaultValue("3D-моделювання персонажів\nРигінг та анімація\nРекламні ролики продуктів\nVFX та синематики\nVR / Unreal Engine\nPoligonium School"),
                    TextareaField::make()
                        ->name('poligonium_footer_directions_en')
                        ->label('Footer: directions (EN)')
                        ->defaultValue("Character modeling\nRigging and animation\nProduct commercials\nVFX and cinematics\nVR / Unreal Engine\nPoligonium School"),
                    TextField::make()
                        ->name('poligonium_footer_rights_uk')
                        ->label('Футер: права (UA)')
                        ->defaultValue('Усі права захищено.'),
                    TextField::make()
                        ->name('poligonium_footer_rights_en')
                        ->label('Footer: rights (EN)')
                        ->defaultValue('All rights reserved.'),
                    TextField::make()
                        ->name('poligonium_footer_made_uk')
                        ->label('Футер: нижня фраза (UA)')
                        ->defaultValue('Створено в Україні'),
                    TextField::make()
                        ->name('poligonium_footer_made_en')
                        ->label('Footer: bottom phrase (EN)')
                        ->defaultValue('Created in Ukraine'),
                ])
        )
        ->setField(
            TextField::make()
                ->sectionId('opt-text-subsection-logo')
                ->name('site_name')
                ->label(__('Site name'))
                ->helperText(__('The name displayed next to the logo.'))
        )
        ->setField(
            MediaImageField::make()
                ->sectionId('opt-text-subsection-logo')
                ->name('logo_dark')
                ->label(__('Logo dark'))
        )
        ->setSection(
            ThemeOptionSection::make('styles')
                ->title(__('Styles'))
                ->icon('ti ti-palette')
                ->priority(1)
                ->fields([
                    SelectField::make()
                        ->label(__('Default theme color mode'))
                        ->name('default_theme_color_mode')
                        ->defaultValue('dark')
                        ->options([
                            'dark' => __('Dark'),
                            'light' => __('Light'),
                        ]),
                    SelectField::make()
                        ->label(__('Hide theme mode switcher'))
                        ->name('hide_theme_mode_switcher')
                        ->defaultValue('no')
                        ->options([
                            'no' => __('No'),
                            'yes' => __('Yes'),
                        ]),
                    UiSelectorField::make()
                        ->label(__('Header'))
                        ->name('header_style')
                        ->withoutAspectRatio()
                        ->defaultValue(1)
                        ->options(
                            collect(range(1, 3))->mapWithKeys(function ($i) {
                                return [
                                    $i => [
                                        'label' => __('Style :i', ['i' => $i]),
                                        'image' => Theme::asset()->url("images/header/style-$i.png"),
                                    ],
                                ];
                            })->all()
                        ),
                    UiSelectorField::make()
                        ->label(__('Footer'))
                        ->name('footer_style')
                        ->withoutAspectRatio()
                        ->defaultValue(1)
                        ->options(
                            collect(range(1, 3))->mapWithKeys(function ($i) {
                                return [
                                    $i => [
                                        'label' => __('Style :i', ['i' => $i]),
                                        'image' => Theme::asset()->url("images/footer/style-$i.png"),
                                    ],
                                ];
                            })->all()
                        ),
                    UiSelectorField::make()
                        ->label(__('Preloader'))
                        ->name('preloader_style')
                        ->defaultValue(1)
                        ->options(
                            collect(range(1, 3))->mapWithKeys(function ($i) {
                                return [
                                    $i => [
                                        'label' => __('Style :i', ['i' => $i]),
                                        'image' => Theme::asset()->url("images/preloader/style-$i.gif"),
                                    ],
                                ];
                            })
                                ->prepend([
                                    'label' => __('Disabled Preloader'),
                                ])
                                ->all()
                        ),
                ])
        )
        ->setField(UiSelectorField::make()
            ->label(__('Post item'))
            ->sectionId('opt-text-subsection-blog')
            ->name('post_item_style')
            ->withoutAspectRatio()
            ->defaultValue(1)
            ->options(
                collect(range(1, 3))->mapWithKeys(function ($i) {
                    return [
                        $i => [
                            'label' => __('Style :i', ['i' => $i]),
                            'image' => Theme::asset()->url("images/post-item/style-$i.png"),
                        ],
                    ];
                })->all()
            ))
        ->setField(TextField::make()
            ->label(__('Post item per row'))
            ->sectionId('opt-text-subsection-blog')
            ->name('post_item_per_row')
            ->defaultValue(3)
            ->helperText(__('Number of post items per row on the blog page.')))
        ->setField(SelectField::make()
            ->label(__('Show blog post featured image in post detail page'))
            ->sectionId('opt-text-subsection-blog')
            ->name('show_blog_post_featured_image')
            ->defaultValue('yes')
            ->options([
                'yes' => __('Yes'),
                'no' => __('No'),
            ]))
        ->setField(
            MediaImageField::make()
                ->sectionId('opt-text-subsection-page')
                ->name('404_page_image')
                ->label(__('404 page image'))
        );
});
