<?php

namespace Botble\MicrosoftClarity\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MicrosoftClarityController extends BaseController
{
    public function index()
    {
        PageTitle::setTitle(trans('plugins/microsoft-clarity::microsoft-clarity.name'));

        $projectId = (string) setting('microsoft_clarity_project_id');
        $siteId = (string) setting('microsoft_clarity_site_id');

        if (! $siteId) {
            $siteId = (string) Str::uuid();
            setting()->set(['microsoft_clarity_site_id' => $siteId])->save();
        }

        $iframeUrl = $this->buildIframeUrl($projectId, $siteId);

        return view('plugins/microsoft-clarity::dashboard', compact('projectId', 'siteId', 'iframeUrl'));
    }

    public function updateProject(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]*$/'],
        ]);

        setting()
            ->set([
                'microsoft_clarity_project_id' => $validated['project_id'] ?? '',
                'microsoft_clarity_enabled' => true,
                'microsoft_clarity_tracking_mode' => 'project_id',
            ])
            ->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    protected function buildIframeUrl(string $projectId, string $siteId): string
    {
        $query = [
            'integration' => 'Botble',
            'wpsite' => $siteId,
            'siteurl' => url('/'),
            'hostingtype' => 'selfhosted',
            'WPAdmin' => 1,
        ];

        if ($projectId) {
            $query['project'] = $projectId;
        }

        return 'https://clarity.microsoft.com/embed?' . http_build_query($query);
    }
}
