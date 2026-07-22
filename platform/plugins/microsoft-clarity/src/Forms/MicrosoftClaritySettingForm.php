<?php

namespace Botble\MicrosoftClarity\Forms;

use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\MicrosoftClarity\Http\Requests\MicrosoftClaritySettingRequest;
use Botble\Setting\Forms\SettingForm;

class MicrosoftClaritySettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/microsoft-clarity::microsoft-clarity.settings.title'))
            ->setSectionDescription(trans('plugins/microsoft-clarity::microsoft-clarity.settings.description'))
            ->setValidatorClass(MicrosoftClaritySettingRequest::class)
            ->add(
                'microsoft_clarity_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/microsoft-clarity::microsoft-clarity.settings.enabled'))
                    ->value(setting('microsoft_clarity_enabled', true))
                    ->helperText(trans('plugins/microsoft-clarity::microsoft-clarity.settings.enabled_help'))
                    ->toArray()
            )
            ->add(
                'microsoft_clarity_project_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/microsoft-clarity::microsoft-clarity.settings.project_id'))
                    ->value(setting('microsoft_clarity_project_id'))
                    ->placeholder('abc123xyz')
                    ->helperText(trans('plugins/microsoft-clarity::microsoft-clarity.settings.project_id_help'))
                    ->toArray()
            )
            ->add(
                'microsoft_clarity_tracking_mode',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/microsoft-clarity::microsoft-clarity.settings.tracking_mode'))
                    ->selected(setting('microsoft_clarity_tracking_mode', 'project_id'))
                    ->choices([
                        'project_id' => trans('plugins/microsoft-clarity::microsoft-clarity.settings.manual_mode'),
                        'custom_code' => trans('plugins/microsoft-clarity::microsoft-clarity.settings.script_mode'),
                    ])
                    ->toArray()
            )
            ->add(
                'microsoft_clarity_tracking_code',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('plugins/microsoft-clarity::microsoft-clarity.settings.tracking_code'))
                    ->value(setting('microsoft_clarity_tracking_code'))
                    ->mode('html')
                    ->helperText(trans('plugins/microsoft-clarity::microsoft-clarity.settings.tracking_code_help'))
                    ->toArray()
            )
            ->add(
                'microsoft_clarity_exclude_admin',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/microsoft-clarity::microsoft-clarity.settings.exclude_admin'))
                    ->value(setting('microsoft_clarity_exclude_admin', true))
                    ->helperText(trans('plugins/microsoft-clarity::microsoft-clarity.settings.exclude_admin_help'))
                    ->toArray()
            );
    }
}
