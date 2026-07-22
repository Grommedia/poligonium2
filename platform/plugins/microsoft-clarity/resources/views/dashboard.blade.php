@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @php
        $isConnected = ! empty($projectId);
    @endphp

    <style>
        .poligonium-clarity-admin {
            --clarity-border: rgba(17, 24, 39, 0.12);
            --clarity-soft: #f7f9fc;
            --clarity-text: #1f2937;
        }

        .poligonium-clarity-admin .clarity-shell {
            border: 1px solid var(--clarity-border);
            border-radius: 10px;
            background:
                linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(255, 255, 255, 0) 34%),
                linear-gradient(315deg, rgba(245, 158, 11, 0.08), rgba(255, 255, 255, 0) 36%),
                #fff;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .poligonium-clarity-admin .clarity-toolbar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(430px, 520px);
            gap: 14px;
            align-items: stretch;
            padding: 14px;
            border-bottom: 1px solid var(--clarity-border);
        }

        .poligonium-clarity-admin .clarity-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: #2563eb;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .poligonium-clarity-admin .clarity-title {
            margin: 0;
            color: var(--clarity-text);
            font-size: 21px;
            font-weight: 700;
        }

        .poligonium-clarity-admin .clarity-description {
            max-width: 760px;
            margin: 6px 0 0;
            color: #667085;
            font-size: 13px;
            line-height: 1.55;
        }

        .poligonium-clarity-admin .clarity-status {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .poligonium-clarity-admin .clarity-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 34px;
            padding: 6px 12px;
            border: 1px solid var(--clarity-border);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.78);
            color: #344054;
            font-size: 13px;
            font-weight: 600;
        }

        .poligonium-clarity-admin .clarity-pill.is-connected {
            border-color: rgba(16, 185, 129, 0.34);
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
        }

        .poligonium-clarity-admin .clarity-pill.is-warning {
            border-color: rgba(245, 158, 11, 0.34);
            background: rgba(245, 158, 11, 0.12);
            color: #92400e;
        }

        .poligonium-clarity-admin .clarity-connect {
            border: 1px solid var(--clarity-border);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.76);
            padding: 12px;
        }

        .poligonium-clarity-admin .clarity-connect .form-control {
            background: #fff;
        }

        .poligonium-clarity-admin .clarity-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .poligonium-clarity-admin .clarity-actions .btn,
        .poligonium-clarity-admin .clarity-project-save {
            white-space: nowrap;
        }

        .poligonium-clarity-admin .clarity-workspace {
            padding: 14px;
            background:
                linear-gradient(rgba(15, 23, 42, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.025) 1px, transparent 1px),
                var(--clarity-soft);
            background-size: 24px 24px;
        }

        .poligonium-clarity-admin .clarity-frame-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
            color: #475467;
            font-size: 13px;
        }

        .poligonium-clarity-admin .clarity-frame-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: #111827;
        }

        .poligonium-clarity-admin .clarity-frame {
            height: max(650px, calc(100vh - 230px));
            min-height: 620px;
            border: 1px solid rgba(17, 24, 39, 0.16);
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.7);
        }

        .poligonium-clarity-admin .clarity-frame iframe {
            display: block;
            width: 100%;
            height: 100%;
            border: 0;
            background: #fff;
        }

        @media (max-width: 991px) {
            .poligonium-clarity-admin .clarity-toolbar {
                grid-template-columns: 1fr;
            }

            .poligonium-clarity-admin .clarity-frame {
                height: 720px;
            }
        }
    </style>

    <div class="poligonium-clarity-admin">
        <div class="clarity-shell">
            <div class="clarity-toolbar">
                <div>
                    <div class="clarity-eyebrow">
                        <x-core::icon name="ti ti-chart-dots-3" />
                        {{ trans('plugins/microsoft-clarity::microsoft-clarity.menu_name') }}
                    </div>

                    <h1 class="clarity-title">
                        {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.title') }}
                    </h1>

                    <p class="clarity-description">
                        {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.description') }}
                    </p>

                    <div class="clarity-status">
                        <span class="clarity-pill {{ $isConnected ? 'is-connected' : 'is-warning' }}">
                            <x-core::icon :name="$isConnected ? 'ti ti-circle-check' : 'ti ti-alert-circle'" />
                            {{ $isConnected ? trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.project_connected') : trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.project_missing') }}
                        </span>

                        @if ($isConnected)
                            <span class="clarity-pill">
                                Project ID:
                                <code>{{ $projectId }}</code>
                            </span>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('microsoft-clarity.project.update') }}" class="clarity-connect">
                    @csrf

                    <label class="form-label fw-semibold" for="microsoft-clarity-project-id">
                        {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.manual_project') }}
                    </label>

                    <div class="input-group">
                        <input
                            id="microsoft-clarity-project-id"
                            class="form-control"
                            name="project_id"
                            value="{{ old('project_id', $projectId) }}"
                            placeholder="abc123xyz"
                        >

                        <button class="btn btn-primary clarity-project-save" type="submit">
                            <x-core::icon name="ti ti-device-floppy" />
                            {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.save_project') }}
                        </button>
                    </div>

                    <div class="clarity-actions">
                        <x-core::button
                            tag="a"
                            :href="route('microsoft-clarity.settings')"
                            color="secondary"
                            icon="ti ti-settings"
                        >
                            {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.open_settings') }}
                        </x-core::button>

                        <x-core::button
                            tag="a"
                            href="https://clarity.microsoft.com/"
                            target="_blank"
                            color="light"
                            icon="ti ti-external-link"
                        >
                            {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.open_clarity') }}
                        </x-core::button>
                    </div>
                </form>
            </div>

            <div class="clarity-workspace">
                <div class="clarity-frame-header">
                    <span class="clarity-frame-label">
                        <x-core::icon name="ti ti-layout-dashboard" />
                        {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.embed_title') }}
                    </span>
                    <span>
                        {{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.project_hint') }}
                    </span>
                </div>

                <div class="clarity-frame">
                    <iframe
                        sandbox="allow-modals allow-forms allow-scripts allow-same-origin allow-popups allow-storage-access-by-user-activation"
                        src="{{ $iframeUrl }}"
                        title="{{ trans('plugins/microsoft-clarity::microsoft-clarity.dashboard.embed_title') }}"
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const endpoint = @json(route('microsoft-clarity.project.update'));
            const token = @json(csrf_token());
            const input = document.getElementById('microsoft-clarity-project-id');

            const isValidProjectId = (id) => typeof id === 'string' && /^[A-Za-z0-9_-]*$/.test(id);

            window.addEventListener('message', (event) => {
                if (event.origin !== 'https://clarity.microsoft.com') {
                    return;
                }

                const message = event.data || {};

                if (message.operation !== 1 || !isValidProjectId(message.id)) {
                    return;
                }

                const projectId = message.id || '';

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ project_id: projectId }),
                }).then((response) => {
                    if (response.ok && input) {
                        input.value = projectId;
                    }
                });
            }, false);
        })();
    </script>
@endsection
