<?php

namespace Botble\VfxShowreel\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\VfxShowreel\Forms\VfxShowreelItemForm;
use Botble\VfxShowreel\Http\Requests\VfxShowreelItemRequest;
use Botble\VfxShowreel\Models\VfxShowreelItem;
use Botble\VfxShowreel\Tables\VfxShowreelItemTable;

class VfxShowreelItemController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/vfx-showreel::vfx-showreel.name'))
            ->add(trans('plugins/vfx-showreel::vfx-showreel.items'), route('vfx-showreel.items.index'));
    }

    public function index(VfxShowreelItemTable $table)
    {
        $this->pageTitle(trans('plugins/vfx-showreel::vfx-showreel.items'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return VfxShowreelItemForm::create()->renderForm();
    }

    public function store(VfxShowreelItemRequest $request)
    {
        $form = VfxShowreelItemForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('vfx-showreel.items.index')
            ->setNextRoute('vfx-showreel.items.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(VfxShowreelItem $item)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $item->name]));

        return VfxShowreelItemForm::createFromModel($item)->renderForm();
    }

    public function update(VfxShowreelItem $item, VfxShowreelItemRequest $request)
    {
        VfxShowreelItemForm::createFromModel($item)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('vfx-showreel.items.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(VfxShowreelItem $item)
    {
        return DeleteResourceAction::make($item);
    }
}
