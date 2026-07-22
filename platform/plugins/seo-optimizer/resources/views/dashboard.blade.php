@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <style>
        .poligonium-seo-dashboard {
            --seo-border: rgba(15, 23, 42, 0.12);
            --seo-text: #172033;
            --seo-muted: #667085;
            --seo-soft: #f7f9fc;
            --seo-blue: #2563eb;
            --seo-green: #16a34a;
            --seo-amber: #d97706;
        }

        .poligonium-seo-dashboard .seo-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
            padding: 22px;
            border: 1px solid var(--seo-border);
            border-radius: 10px;
            background:
                linear-gradient(135deg, rgba(37, 99, 235, 0.09), transparent 32%),
                linear-gradient(315deg, rgba(22, 163, 74, 0.08), transparent 34%),
                #fff;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.07);
        }

        .poligonium-seo-dashboard .seo-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: var(--seo-blue);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .poligonium-seo-dashboard h2 {
            margin: 0;
            color: var(--seo-text);
            font-size: 25px;
            font-weight: 750;
        }

        .poligonium-seo-dashboard .seo-hero p {
            max-width: 820px;
            margin: 8px 0 0;
            color: var(--seo-muted);
            line-height: 1.55;
        }

        .poligonium-seo-dashboard .seo-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        .poligonium-seo-dashboard .seo-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 16px;
            margin-top: 16px;
        }

        .poligonium-seo-dashboard .seo-panel {
            border: 1px solid var(--seo-border);
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
        }

        .poligonium-seo-dashboard .seo-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--seo-border);
            background: var(--seo-soft);
        }

        .poligonium-seo-dashboard .seo-panel-head strong {
            color: var(--seo-text);
            font-size: 15px;
        }

        .poligonium-seo-dashboard .seo-check-list,
        .poligonium-seo-dashboard .seo-stat-list,
        .poligonium-seo-dashboard .seo-link-list {
            display: grid;
            gap: 0;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .poligonium-seo-dashboard .seo-check-list li,
        .poligonium-seo-dashboard .seo-stat-list li,
        .poligonium-seo-dashboard .seo-link-list li {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 13px 16px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .poligonium-seo-dashboard .seo-check-list li:last-child,
        .poligonium-seo-dashboard .seo-stat-list li:last-child,
        .poligonium-seo-dashboard .seo-link-list li:last-child {
            border-bottom: 0;
        }

        .poligonium-seo-dashboard .seo-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: rgba(22, 163, 74, 0.12);
            color: var(--seo-green);
        }

        .poligonium-seo-dashboard .seo-status.is-warning {
            background: rgba(217, 119, 6, 0.14);
            color: var(--seo-amber);
        }

        .poligonium-seo-dashboard .seo-item-title {
            display: block;
            color: var(--seo-text);
            font-weight: 650;
        }

        .poligonium-seo-dashboard .seo-item-hint {
            display: block;
            margin-top: 2px;
            color: var(--seo-muted);
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .poligonium-seo-dashboard .seo-stat-value {
            color: var(--seo-text);
            font-size: 24px;
            font-weight: 760;
        }

        .poligonium-seo-dashboard .seo-robots {
            margin: 0;
            padding: 14px 16px;
            max-height: 220px;
            overflow: auto;
            background: #0f172a;
            color: #dbeafe;
            font-size: 12px;
            white-space: pre-wrap;
        }

        .poligonium-seo-dashboard .seo-link-list a {
            color: var(--seo-blue);
            font-weight: 650;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .poligonium-seo-dashboard .seo-hero,
            .poligonium-seo-dashboard .seo-grid {
                grid-template-columns: 1fr;
            }

            .poligonium-seo-dashboard .seo-actions {
                justify-content: flex-start;
            }
        }
    </style>

    <div class="poligonium-seo-dashboard">
        <section class="seo-hero">
            <div>
                <span class="seo-eyebrow">
                    <x-core::icon name="ti ti-seo" />
                    {{ trans('plugins/seo-optimizer::seo-optimizer.menu_name') }}
                </span>

                <h2>{{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.title') }}</h2>

                <p>{{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.description') }}</p>
            </div>

            <div class="seo-actions">
                <x-core::button tag="a" :href="route('seo-optimizer.settings')" color="primary" icon="ti ti-settings">
                    {{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.actions.settings') }}
                </x-core::button>

                <x-core::button tag="a" :href="url('/sitemap.xml')" target="_blank" color="secondary" icon="ti ti-sitemap">
                    {{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.actions.sitemap') }}
                </x-core::button>

                <x-core::button tag="a" :href="url('/robots.txt')" target="_blank" color="secondary" icon="ti ti-file-text">
                    robots.txt
                </x-core::button>
            </div>
        </section>

        <div class="seo-grid">
            <section class="seo-panel">
                <div class="seo-panel-head">
                    <strong>{{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.checklist') }}</strong>
                </div>

                <ul class="seo-check-list">
                    @foreach ($checks as $check)
                        <li>
                            <span @class(['seo-status', 'is-warning' => ! $check['status']])>
                                <x-core::icon :name="$check['status'] ? 'ti ti-check' : 'ti ti-alert-triangle'" />
                            </span>
                            <span>
                                <span class="seo-item-title">{{ $check['label'] }}</span>
                                <span class="seo-item-hint">{{ $check['hint'] }}</span>
                            </span>
                            <span class="badge {{ $check['status'] ? 'bg-success' : 'bg-warning' }}">
                                {{ $check['status'] ? trans('plugins/seo-optimizer::seo-optimizer.dashboard.status.ready') : trans('plugins/seo-optimizer::seo-optimizer.dashboard.status.attention') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="seo-panel">
                <div class="seo-panel-head">
                    <strong>{{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.stats.title') }}</strong>
                </div>

                <ul class="seo-stat-list">
                    @foreach ($contentStats as $stat)
                        <li>
                            <span class="seo-status">
                                <x-core::icon name="ti ti-file-search" />
                            </span>
                            <span class="seo-item-title">{{ $stat['label'] }}</span>
                            <span class="seo-stat-value">{{ $stat['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <div class="seo-grid">
            <section class="seo-panel">
                <div class="seo-panel-head">
                    <strong>robots.txt</strong>
                </div>

                <pre class="seo-robots">{{ $robotsContent ?: trans('plugins/seo-optimizer::seo-optimizer.dashboard.robots_missing') }}</pre>
            </section>

            <section class="seo-panel">
                <div class="seo-panel-head">
                    <strong>{{ trans('plugins/seo-optimizer::seo-optimizer.dashboard.quick_links') }}</strong>
                </div>

                <ul class="seo-link-list">
                    <li>
                        <span class="seo-status"><x-core::icon name="ti ti-brand-google" /></span>
                        <span>
                            <span class="seo-item-title">Google Search Console</span>
                            <span class="seo-item-hint">URL Inspection, Sitemaps, Page Indexing</span>
                        </span>
                        <a href="https://search.google.com/search-console" target="_blank">Open</a>
                    </li>
                    <li>
                        <span class="seo-status"><x-core::icon name="ti ti-code" /></span>
                        <span>
                            <span class="seo-item-title">Rich Results Test</span>
                            <span class="seo-item-hint">JSON-LD structured data</span>
                        </span>
                        <a href="https://search.google.com/test/rich-results" target="_blank">Open</a>
                    </li>
                    <li>
                        <span class="seo-status"><x-core::icon name="ti ti-world-search" /></span>
                        <span>
                            <span class="seo-item-title">PageSpeed Insights</span>
                            <span class="seo-item-hint">Core Web Vitals</span>
                        </span>
                        <a href="https://pagespeed.web.dev/" target="_blank">Open</a>
                    </li>
                </ul>
            </section>
        </div>
    </div>
@endsection
