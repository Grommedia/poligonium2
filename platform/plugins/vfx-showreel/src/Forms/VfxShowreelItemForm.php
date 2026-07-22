<?php

namespace Botble\VfxShowreel\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Botble\Base\Forms\FieldOptions\MediaFileFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\VfxShowreel\Http\Requests\VfxShowreelItemRequest;
use Botble\VfxShowreel\Models\VfxShowreelItem;

class VfxShowreelItemForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(VfxShowreelItem::class)
            ->setValidatorClass(VfxShowreelItemRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('type', TextField::class, [
                'label' => trans('plugins/vfx-showreel::vfx-showreel.type'),
                'attr' => ['placeholder' => 'Product animation / VFX / Motion'],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->rows(4))
            ->add('poster', 'mediaImage', MediaImageFieldOption::make()
                ->label(trans('plugins/vfx-showreel::vfx-showreel.poster'))
                ->value($this->getModel()->poster)
                ->helperText('Рекомендовано: 1200x1200 або широкий кадр з ролика.')
                ->toArray()
            )
            ->add('preview_video', 'mediaFile', MediaFileFieldOption::make()
                ->label(trans('plugins/vfx-showreel::vfx-showreel.preview_video'))
                ->value($this->getModel()->preview_video)
                ->helperText(trans('plugins/vfx-showreel::vfx-showreel.preview_video_helper'))
                ->toArray()
            )
            ->add('tools', TextField::class, [
                'label' => trans('plugins/vfx-showreel::vfx-showreel.tools'),
                'helperText' => trans('plugins/vfx-showreel::vfx-showreel.tools_helper'),
                'attr' => ['placeholder' => 'Blender, Houdini, After Effects'],
            ])
            ->add('year', TextField::class, [
                'label' => trans('plugins/vfx-showreel::vfx-showreel.year'),
                'attr' => ['placeholder' => '2025'],
            ])
            ->add('url', TextField::class, [
                'label' => trans('plugins/vfx-showreel::vfx-showreel.url'),
                'attr' => ['placeholder' => '/contact'],
            ])
            ->add('is_featured', OnOffField::class, IsFeaturedFieldOption::make())
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
