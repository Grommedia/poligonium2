<?php

namespace Botble\MicrosoftClarity\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\MicrosoftClarity\Forms\MicrosoftClaritySettingForm;
use Botble\MicrosoftClarity\Http\Requests\MicrosoftClaritySettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class MicrosoftClaritySettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/microsoft-clarity::microsoft-clarity.settings.title'));

        return MicrosoftClaritySettingForm::create()->renderForm();
    }

    public function update(MicrosoftClaritySettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated())->withUpdatedSuccessMessage();
    }
}
