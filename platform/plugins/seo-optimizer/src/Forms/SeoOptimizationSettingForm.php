<?php

namespace Botble\SeoOptimizer\Forms;

use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\SeoOptimizer\Http\Requests\SeoOptimizationSettingRequest;
use Botble\Setting\Forms\SettingForm;

class SeoOptimizationSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/seo-optimizer::seo-optimizer.settings.title'))
            ->setSectionDescription(trans('plugins/seo-optimizer::seo-optimizer.settings.description'))
            ->setValidatorClass(SeoOptimizationSettingRequest::class)
            ->add(
                'seo_enable_structured_data',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.enable_structured_data'))
                    ->value(setting('seo_enable_structured_data', true))
                    ->helperText(trans('plugins/seo-optimizer::seo-optimizer.settings.enable_structured_data_help'))
                    ->toArray()
            )
            ->add(
                'seo_enable_canonical_hreflang',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.enable_canonical_hreflang'))
                    ->value(setting('seo_enable_canonical_hreflang', true))
                    ->helperText(trans('plugins/seo-optimizer::seo-optimizer.settings.enable_canonical_hreflang_help'))
                    ->toArray()
            )
            ->add(
                'seo_google_site_verification',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.google_site_verification'))
                    ->value(setting('seo_google_site_verification'))
                    ->placeholder('google-site-verification token')
                    ->helperText(Html::link('https://search.google.com/search-console', attributes: ['target' => '_blank']))
                    ->toArray()
            )
            ->add(
                'seo_bing_site_verification',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.bing_site_verification'))
                    ->value(setting('seo_bing_site_verification'))
                    ->placeholder('msvalidate.01 token')
                    ->toArray()
            )
            ->add(
                'seo_organization_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_name'))
                    ->value(setting('seo_organization_name', 'Poligonium'))
                    ->toArray()
            )
            ->add(
                'seo_organization_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_url'))
                    ->value(setting('seo_organization_url', 'https://poligonium.com'))
                    ->toArray()
            )
            ->add(
                'seo_organization_logo',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_logo'))
                    ->value(setting('seo_organization_logo'))
                    ->toArray()
            )
            ->add(
                'seo_organization_phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_phone'))
                    ->value(setting('seo_organization_phone', '+380-98-223-2974'))
                    ->toArray()
            )
            ->add(
                'seo_organization_email',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_email'))
                    ->value(setting('seo_organization_email'))
                    ->toArray()
            )
            ->add(
                'seo_organization_same_as',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_same_as'))
                    ->value(setting('seo_organization_same_as'))
                    ->rows(4)
                    ->helperText(trans('plugins/seo-optimizer::seo-optimizer.settings.organization_same_as_help'))
                    ->toArray()
            )
            ->add(
                'seo_extra_head_html',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.extra_head_html'))
                    ->value(setting('seo_extra_head_html'))
                    ->mode('html')
                    ->helperText(trans('plugins/seo-optimizer::seo-optimizer.settings.extra_head_html_help'))
                    ->toArray()
            )
            ->add(
                'seo_extra_body_html',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('plugins/seo-optimizer::seo-optimizer.settings.extra_body_html'))
                    ->value(setting('seo_extra_body_html'))
                    ->mode('html')
                    ->helperText(trans('plugins/seo-optimizer::seo-optimizer.settings.extra_body_html_help'))
                    ->toArray()
            );
    }
}
