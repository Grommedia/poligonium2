<?php

namespace Botble\SeoOptimizer\Http\Controllers;

use Botble\SeoOptimizer\Forms\SeoOptimizationSettingForm;
use Botble\SeoOptimizer\Http\Requests\SeoOptimizationSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class SeoOptimizationSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/seo-optimizer::seo-optimizer.settings.title'));

        return SeoOptimizationSettingForm::create()->renderForm();
    }

    public function update(SeoOptimizationSettingRequest $request)
    {
        return $this
            ->performUpdate($request->validated())
            ->withUpdatedSuccessMessage();
    }
}
