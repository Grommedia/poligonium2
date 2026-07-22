<?php

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Forms\FieldOptions\CoreIconFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImagesFieldOption;
use Botble\Base\Forms\FieldOptions\MediaFileFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CoreIconField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Media\Facades\RvMedia;
use Botble\Portfolio\Forms\Fronts\QuotationForm;
use Botble\Portfolio\Forms\PackageForm;
use Botble\Portfolio\Forms\ProjectForm;
use Botble\Portfolio\Forms\ServiceForm;
use Botble\Portfolio\Models\CustomField;
use Botble\Portfolio\Models\CustomFieldOption;
use Botble\Portfolio\Models\Package;
use Botble\Portfolio\Models\ServiceCategory;
use Botble\Portfolio\Tables\PackageTable;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\SiteMapManager;
use Botble\Theme\Facades\Theme;
use Botble\Theme\FormFrontManager;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Theme\Typography\TypographyItem;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;

if (! function_exists('poligonium_portfolio_tool_registry')) {
    function poligonium_portfolio_tool_registry(): array
    {
        return [
            'blender' => ['label' => 'Blender', 'short' => 'Bl', 'icon' => 'images/pipeline-icons/blender.png'],
            'houdini' => ['label' => 'Houdini', 'short' => 'Ho', 'icon' => 'images/pipeline-icons/houdini.png'],
            'zbrush' => ['label' => 'ZBrush', 'short' => 'Zb', 'icon' => 'images/pipeline-icons/zbrush.png'],
            'substance-3d-painter' => ['label' => 'Substance 3D Painter', 'short' => 'Pt', 'icon' => 'images/pipeline-icons/substance-painter.png'],
            'substance-3d-designer' => ['label' => 'Substance 3D Designer', 'short' => 'Sd', 'icon' => 'images/pipeline-icons/substance-designer.png'],
            'photoshop' => ['label' => 'Photoshop', 'short' => 'Ps', 'icon' => 'images/pipeline-icons/photoshop.png'],
            'illustrator' => ['label' => 'Illustrator', 'short' => 'Ai', 'icon' => 'images/pipeline-icons/illustrator.png'],
            'after-effects' => ['label' => 'After Effects', 'short' => 'Ae', 'icon' => 'images/pipeline-icons/after-effects.png'],
            'nuke' => ['label' => 'Nuke', 'short' => 'Nk', 'icon' => 'images/pipeline-icons/nuke.png'],
            'copernicus' => ['label' => 'Houdini Copernicus', 'short' => 'Cp', 'icon' => 'images/pipeline-icons/copernicus.svg'],
            'ai-concept' => ['label' => 'AI Concept', 'short' => 'AI', 'icon' => 'images/pipeline-icons/ai.svg'],
            'unreal-engine-5' => ['label' => 'Unreal Engine 5', 'short' => 'UE', 'icon' => null],
            'marvelous-designer' => ['label' => 'Marvelous Designer', 'short' => 'Md', 'icon' => null],
            'fusion-360' => ['label' => 'Fusion 360', 'short' => 'F3', 'icon' => null],
            'inventor' => ['label' => 'Inventor', 'short' => 'Iv', 'icon' => null],
            'rigify' => ['label' => 'Rigify', 'short' => 'Rg', 'icon' => null],
            'auto-rig-pro' => ['label' => 'Auto-Rig Pro', 'short' => 'AR', 'icon' => null],
            'houdini-apex' => ['label' => 'Houdini APEX', 'short' => 'Ax', 'icon' => 'images/pipeline-icons/houdini.png'],
        ];
    }
}

if (! function_exists('poligonium_portfolio_tool_options')) {
    function poligonium_portfolio_tool_options(): array
    {
        return collect(poligonium_portfolio_tool_registry())
            ->mapWithKeys(fn (array $tool) => [$tool['label'] => $tool['label']])
            ->all();
    }
}

if (! function_exists('poligonium_portfolio_tool_key')) {
    function poligonium_portfolio_tool_key(string $tool): string
    {
        $normalized = str($tool)
            ->lower()
            ->replace(['3d painter', '3d-painter'], '3d painter')
            ->replaceMatches('/[^a-z0-9]+/', '-')
            ->trim('-')
            ->toString();

        return match ($normalized) {
            'substance-painter', 'substance-3d-painter', 'substance-pt' => 'substance-3d-painter',
            'substance-designer', 'substance-3d-designer' => 'substance-3d-designer',
            'adobe-photoshop' => 'photoshop',
            'adobe-illustrator' => 'illustrator',
            'adobe-after-effects' => 'after-effects',
            'ue5', 'unreal', 'unreal-engine' => 'unreal-engine-5',
            'houdini-copernicus' => 'copernicus',
            'apex', 'houdini-apex-rig' => 'houdini-apex',
            'ai', 'ai-tools', 'generative-ai' => 'ai-concept',
            default => $normalized,
        };
    }
}

if (! function_exists('poligonium_portfolio_normalize_tools')) {
    function poligonium_portfolio_normalize_tools(array|string|null $tools): array
    {
        $items = is_array($tools)
            ? $tools
            : preg_split('/\r\n|\r|\n|,|\/|;/', (string) $tools);

        return collect($items)
            ->flatten()
            ->map(fn ($tool) => trim((string) $tool))
            ->filter()
            ->map(function (string $tool): string {
                $registry = poligonium_portfolio_tool_registry();
                $key = poligonium_portfolio_tool_key($tool);

                return $registry[$key]['label'] ?? $tool;
            })
            ->unique()
            ->values()
            ->all();
    }
}

if (! function_exists('poligonium_portfolio_tool_data')) {
    function poligonium_portfolio_tool_data(string $tool): array
    {
        $registry = poligonium_portfolio_tool_registry();
        $key = poligonium_portfolio_tool_key($tool);
        $data = $registry[$key] ?? [
            'label' => $tool,
            'short' => str($tool)->substr(0, 2)->upper()->toString(),
            'icon' => null,
        ];

        $data['icon_url'] = $data['icon'] ? Theme::asset()->url($data['icon']) : null;

        return $data;
    }
}

if (! function_exists('poligonium_portfolio_default_tools')) {
    function poligonium_portfolio_default_tools(): array
    {
        return ['Blender', 'Houdini', 'ZBrush'];
    }
}

if (! function_exists('poligonium_portfolio_effective_tools')) {
    function poligonium_portfolio_effective_tools($project): array
    {
        $tools = poligonium_portfolio_normalize_tools($project->getMetaData('tools', true));

        return $tools ?: poligonium_portfolio_default_tools();
    }
}

if (! function_exists('poligonium_portfolio_effective_card_label')) {
    function poligonium_portfolio_effective_card_label($project): string
    {
        $label = $project->getMetaData('card_label', true);

        if ($label) {
            return $label;
        }

        try {
            $categoryName = $project->categories?->first()?->name;

            if ($categoryName) {
                return $categoryName;
            }
        } catch (Throwable) {
            //
        }

        $categoryIds = collect((array) $project->getMetaData('category_ids', true))
            ->flatten()
            ->filter()
            ->values();

        if ($categoryIds->isNotEmpty()) {
            $categoryName = ServiceCategory::query()
                ->whereKey($categoryIds->first())
                ->value('name');

            if ($categoryName) {
                return $categoryName;
            }
        }

        return __('Project');
    }
}

if (! function_exists('poligonium_portfolio_effective_card_subtitle')) {
    function poligonium_portfolio_effective_card_subtitle($project): string
    {
        return $project->getMetaData('card_subtitle', true) ?: (string) $project->client;
    }
}

if (! function_exists('poligonium_portfolio_tools_admin_preview')) {
    function poligonium_portfolio_tools_admin_preview(): string
    {
        $tools = collect(poligonium_portfolio_tool_registry())
            ->map(function (array $tool) {
                $icon = $tool['icon'] ? Theme::asset()->url($tool['icon']) : null;
                $image = $icon
                    ? sprintf('<img src="%s" alt="%s">', e($icon), e($tool['label']))
                    : sprintf('<b>%s</b>', e($tool['short']));

                return sprintf(
                    '<span class="poligonium-admin-tool-chip" data-tool="%s">%s<em>%s</em></span>',
                    e($tool['label']),
                    $image,
                    e($tool['label'])
                );
            })
            ->implode('');

        return <<<HTML
            <div class="poligonium-admin-tools-preview">
                <div class="poligonium-admin-tools-preview__head">
                    <strong>Логотипы и технологии на карточке</strong>
                    <span>Выбранные пункты будут показаны на сайте с логотипами.</span>
                </div>
                <div class="poligonium-admin-tools-preview__selected" data-poligonium-tools-selected>
                    <small>Выберите технологии выше.</small>
                </div>
                <details>
                    <summary>Показать весь каталог логотипов</summary>
                    <div class="poligonium-admin-tools-preview__catalog">{$tools}</div>
                </details>
            </div>
            <style>
                .poligonium-admin-tools-preview {
                    margin-top: -10px;
                    padding: 14px;
                    border: 1px solid rgba(32, 36, 42, .14);
                    border-radius: 8px;
                    background: #fbfbfa;
                }
                .poligonium-admin-tools-preview__head {
                    display: grid;
                    gap: 2px;
                    margin-bottom: 12px;
                }
                .poligonium-admin-tools-preview__head strong {
                    color: #20242a;
                }
                .poligonium-admin-tools-preview__head span,
                .poligonium-admin-tools-preview small {
                    color: #69707a;
                    font-size: 12px;
                }
                .poligonium-admin-tools-preview__selected,
                .poligonium-admin-tools-preview__catalog {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                }
                .poligonium-admin-tools-preview details {
                    margin-top: 12px;
                }
                .poligonium-admin-tools-preview summary {
                    cursor: pointer;
                    color: #206bc4;
                    font-weight: 600;
                }
                .poligonium-admin-tool-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    min-height: 34px;
                    padding: 5px 10px;
                    border: 1px solid rgba(32, 36, 42, .14);
                    border-radius: 6px;
                    background: #fff;
                    box-shadow: 0 4px 10px rgba(32, 36, 42, .06);
                    color: #20242a;
                    font-size: 12px;
                    font-weight: 700;
                }
                .poligonium-admin-tool-chip img,
                .poligonium-admin-tool-chip b {
                    width: 24px;
                    height: 24px;
                    flex: 0 0 24px;
                }
                .poligonium-admin-tool-chip img {
                    object-fit: contain;
                }
                .poligonium-admin-tool-chip b {
                    display: grid;
                    place-items: center;
                    border-radius: 5px;
                    background: #20242a;
                    color: #f49a21;
                    font-size: 10px;
                    font-style: normal;
                }
                .poligonium-admin-tool-chip em {
                    font-style: normal;
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var select = document.querySelector('select[name="tools[]"], select[name="tools"]');
                    var selected = document.querySelector('[data-poligonium-tools-selected]');

                    if (! select || ! selected) {
                        return;
                    }

                    var chips = Array.prototype.slice.call(document.querySelectorAll('.poligonium-admin-tool-chip[data-tool]'));
                    var render = function () {
                        var values = Array.prototype.slice.call(select.selectedOptions || [])
                            .map(function (option) { return option.value; })
                            .filter(Boolean);

                        selected.innerHTML = '';

                        if (! values.length) {
                            selected.innerHTML = '<small>Выберите технологии выше.</small>';
                            return;
                        }

                        values.forEach(function (value) {
                            var source = chips.find(function (chip) { return chip.dataset.tool === value; });
                            selected.appendChild(source ? source.cloneNode(true) : document.createTextNode(value));
                        });
                    };

                    select.addEventListener('change', render);
                    render();
                });
            </script>
        HTML;
    }
}

if (! function_exists('poligonium_portfolio_tools_dragdrop_admin')) {
    function poligonium_portfolio_tools_dragdrop_admin(): string
    {
        $tools = collect(poligonium_portfolio_tool_registry())
            ->map(function (array $tool) {
                $icon = $tool['icon'] ? Theme::asset()->url($tool['icon']) : null;
                $visual = $icon
                    ? sprintf('<img src="%s" alt="%s" loading="lazy">', e($icon), e($tool['label']))
                    : sprintf('<b>%s</b>', e($tool['short']));

                return sprintf(
                    '<button class="poligonium-tool-card" type="button" draggable="true" data-tool="%s" data-short="%s" data-search="%s">%s<span>%s</span></button>',
                    e($tool['label']),
                    e($tool['short']),
                    e(str($tool['label'])->lower()->toString()),
                    $visual,
                    e($tool['label'])
                );
            })
            ->implode('');

        return <<<HTML
            <div class="poligonium-tools-builder" data-poligonium-tools-builder>
                <input type="hidden" name="_poligonium_tools_builder" value="1">
                <div data-poligonium-tools-hidden></div>

                <div class="poligonium-tools-builder__head">
                    <div>
                        <strong>Логотипы и технологии на карточке</strong>
                        <span>Перетащи программы из каталога в выбранные. На карточке лучше держать до 5-6 технологий, чтобы она не превращалась в склад.</span>
                    </div>
                    <em data-poligonium-tools-count>0 / 6</em>
                </div>

                <div class="poligonium-tools-builder__grid">
                    <section class="poligonium-tools-builder__panel">
                        <div class="poligonium-tools-builder__panel-head">
                            <strong>Каталог технологий</strong>
                            <input type="search" placeholder="Найти: Blender, Houdini, ZBrush..." data-poligonium-tools-search>
                        </div>
                        <div class="poligonium-tools-builder__catalog" data-poligonium-tools-catalog>
                            {$tools}
                        </div>
                    </section>

                    <section class="poligonium-tools-builder__panel poligonium-tools-builder__panel--selected" data-poligonium-tools-dropzone>
                        <div class="poligonium-tools-builder__panel-head">
                            <strong>Используется в проекте</strong>
                            <span>Перетащи сюда нужные логотипы или нажми на карточку в каталоге.</span>
                        </div>
                        <div class="poligonium-tools-builder__selected" data-poligonium-tools-selected>
                            <small>Пока ничего не выбрано.</small>
                        </div>
                    </section>
                </div>
            </div>

            <style>
                .poligonium-tools-native-select-wrap {
                    display: none !important;
                }
                .poligonium-tools-builder {
                    grid-column: 1 / -1;
                    padding: 18px;
                    border: 1px solid rgba(32, 36, 42, .14);
                    border-radius: 8px;
                    background:
                        linear-gradient(rgba(255, 255, 255, .88), rgba(255, 255, 255, .88)),
                        linear-gradient(90deg, rgba(32, 36, 42, .06) 1px, transparent 1px),
                        linear-gradient(rgba(32, 36, 42, .06) 1px, transparent 1px);
                    background-size: auto, 24px 24px, 24px 24px;
                    box-shadow: 0 16px 38px rgba(32, 36, 42, .06);
                }
                .poligonium-tools-builder__head {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 16px;
                    margin-bottom: 16px;
                }
                .poligonium-tools-builder__head strong,
                .poligonium-tools-builder__panel-head strong {
                    display: block;
                    color: #20242a;
                    font-size: 15px;
                    font-weight: 800;
                }
                .poligonium-tools-builder__head span,
                .poligonium-tools-builder__panel-head span,
                .poligonium-tools-builder small {
                    display: block;
                    margin-top: 3px;
                    color: #69707a;
                    font-size: 12px;
                    line-height: 1.45;
                }
                .poligonium-tools-builder__head em {
                    min-width: 58px;
                    padding: 5px 9px;
                    border: 1px solid rgba(32, 36, 42, .16);
                    border-radius: 999px;
                    background: #fff;
                    color: #20242a;
                    font-size: 12px;
                    font-style: normal;
                    font-weight: 800;
                    text-align: center;
                }
                .poligonium-tools-builder__grid {
                    display: grid;
                    grid-template-columns: minmax(0, 1.1fr) minmax(260px, .9fr);
                    gap: 16px;
                }
                .poligonium-tools-builder__panel {
                    min-height: 260px;
                    padding: 14px;
                    border: 1px solid rgba(32, 36, 42, .12);
                    border-radius: 8px;
                    background: rgba(255, 255, 255, .78);
                    backdrop-filter: blur(8px);
                }
                .poligonium-tools-builder__panel--selected {
                    border-style: dashed;
                }
                .poligonium-tools-builder__panel--dragover {
                    border-color: #f49a21;
                    box-shadow: inset 0 0 0 2px rgba(244, 154, 33, .16);
                }
                .poligonium-tools-builder__panel-head {
                    display: grid;
                    gap: 8px;
                    margin-bottom: 12px;
                }
                .poligonium-tools-builder__panel-head input {
                    width: 100%;
                    height: 36px;
                    padding: 0 11px;
                    border: 1px solid rgba(32, 36, 42, .18);
                    border-radius: 6px;
                    background: #fff;
                    color: #20242a;
                    font-size: 13px;
                }
                .poligonium-tools-builder__catalog,
                .poligonium-tools-builder__selected {
                    display: flex;
                    flex-wrap: wrap;
                    align-content: flex-start;
                    gap: 9px;
                    min-height: 150px;
                }
                .poligonium-tools-builder__selected {
                    padding: 12px;
                    border-radius: 8px;
                    background: rgba(32, 36, 42, .035);
                }
                .poligonium-tool-card,
                .poligonium-tool-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 9px;
                    min-height: 38px;
                    padding: 7px 10px;
                    border: 1px solid rgba(32, 36, 42, .14);
                    border-radius: 7px;
                    background: #fff;
                    box-shadow: 0 5px 14px rgba(32, 36, 42, .06);
                    color: #20242a;
                    font-size: 12px;
                    font-weight: 800;
                    line-height: 1.2;
                    cursor: grab;
                    transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease, opacity .18s ease;
                }
                .poligonium-tool-card:hover,
                .poligonium-tool-pill:hover {
                    border-color: rgba(244, 154, 33, .75);
                    box-shadow: 0 8px 20px rgba(32, 36, 42, .1);
                    transform: translateY(-1px);
                }
                .poligonium-tool-card.is-selected {
                    opacity: .42;
                }
                .poligonium-tool-card img,
                .poligonium-tool-card b,
                .poligonium-tool-pill img,
                .poligonium-tool-pill b {
                    width: 24px;
                    height: 24px;
                    flex: 0 0 24px;
                }
                .poligonium-tool-card img,
                .poligonium-tool-pill img {
                    object-fit: contain;
                }
                .poligonium-tool-card b,
                .poligonium-tool-pill b {
                    display: grid;
                    place-items: center;
                    border-radius: 5px;
                    background: #20242a;
                    color: #f49a21;
                    font-size: 10px;
                    font-style: normal;
                }
                .poligonium-tool-pill {
                    cursor: pointer;
                    background: #fffaf2;
                }
                .poligonium-tool-pill::after {
                    content: "×";
                    display: grid;
                    place-items: center;
                    width: 18px;
                    height: 18px;
                    border-radius: 50%;
                    background: rgba(32, 36, 42, .1);
                    color: #20242a;
                    font-size: 14px;
                    line-height: 1;
                }
                @media (max-width: 991px) {
                    .poligonium-tools-builder__grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>

            <script>
                (function () {
                    var boot = function () {
                        document.querySelectorAll('[data-poligonium-tools-builder]').forEach(function (builder) {
                            if (builder.dataset.ready === '1') {
                                return;
                            }

                            builder.dataset.ready = '1';

                            var form = builder.closest('form') || document;
                            var select = form.querySelector('select[name="tools[]"], select[name="tools"]');
                            var selectedBox = builder.querySelector('[data-poligonium-tools-selected]');
                            var hiddenBox = builder.querySelector('[data-poligonium-tools-hidden]');
                            var dropzone = builder.querySelector('[data-poligonium-tools-dropzone]');
                            var count = builder.querySelector('[data-poligonium-tools-count]');
                            var search = builder.querySelector('[data-poligonium-tools-search]');
                            var cards = Array.prototype.slice.call(builder.querySelectorAll('.poligonium-tool-card[data-tool]'));
                            var maxTools = 6;
                            var draggedTool = null;

                            if (! select || ! selectedBox) {
                                return;
                            }

                            var selectedValues = function () {
                                return Array.prototype.slice.call(select.options)
                                    .filter(function (option) { return option.selected && option.value; })
                                    .map(function (option) { return option.value; });
                            };

                            var triggerChange = function () {
                                select.dispatchEvent(new Event('change', { bubbles: true }));

                                if (window.jQuery) {
                                    window.jQuery(select).trigger('change');
                                }
                            };

                            var setSelected = function (values) {
                                var unique = [];

                                values.forEach(function (value) {
                                    if (value && unique.indexOf(value) === -1 && unique.length < maxTools) {
                                        unique.push(value);
                                    }
                                });

                                Array.prototype.slice.call(select.options).forEach(function (option) {
                                    option.selected = unique.indexOf(option.value) !== -1;
                                });

                                triggerChange();
                                render();
                            };

                            var addTool = function (tool) {
                                var values = selectedValues();

                                if (values.indexOf(tool) !== -1 || values.length >= maxTools) {
                                    render();
                                    return;
                                }

                                values.push(tool);
                                setSelected(values);
                            };

                            var removeTool = function (tool) {
                                setSelected(selectedValues().filter(function (value) {
                                    return value !== tool;
                                }));
                            };

                            var chipMarkup = function (source) {
                                var chip = document.createElement('button');

                                chip.type = 'button';
                                chip.className = 'poligonium-tool-pill';
                                chip.dataset.tool = source.dataset.tool;
                                chip.title = 'Убрать ' + source.dataset.tool;
                                chip.innerHTML = source.innerHTML;
                                chip.addEventListener('click', function () {
                                    removeTool(source.dataset.tool);
                                });

                                return chip;
                            };

                            var render = function () {
                                var values = selectedValues();

                                selectedBox.innerHTML = '';

                                if (hiddenBox) {
                                    hiddenBox.innerHTML = '';

                                    values.forEach(function (value) {
                                        var input = document.createElement('input');

                                        input.type = 'hidden';
                                        input.name = 'tools[]';
                                        input.value = value;
                                        hiddenBox.appendChild(input);
                                    });
                                }

                                if (! values.length) {
                                    selectedBox.innerHTML = '<small>Пока ничего не выбрано.</small>';
                                } else {
                                    values.forEach(function (value) {
                                        var source = cards.find(function (card) { return card.dataset.tool === value; });

                                        if (source) {
                                            selectedBox.appendChild(chipMarkup(source));
                                        }
                                    });
                                }

                                cards.forEach(function (card) {
                                    card.classList.toggle('is-selected', values.indexOf(card.dataset.tool) !== -1);
                                });

                                if (count) {
                                    count.textContent = values.length + ' / ' + maxTools;
                                }
                            };

                            cards.forEach(function (card) {
                                card.addEventListener('click', function () {
                                    addTool(card.dataset.tool);
                                });
                                card.addEventListener('dragstart', function (event) {
                                    draggedTool = card.dataset.tool;
                                    event.dataTransfer.setData('text/plain', draggedTool);
                                    event.dataTransfer.effectAllowed = 'copy';
                                });
                            });

                            if (dropzone) {
                                ['dragenter', 'dragover'].forEach(function (eventName) {
                                    dropzone.addEventListener(eventName, function (event) {
                                        event.preventDefault();
                                        dropzone.classList.add('poligonium-tools-builder__panel--dragover');
                                    });
                                });

                                ['dragleave', 'drop'].forEach(function (eventName) {
                                    dropzone.addEventListener(eventName, function () {
                                        dropzone.classList.remove('poligonium-tools-builder__panel--dragover');
                                    });
                                });

                                dropzone.addEventListener('drop', function (event) {
                                    event.preventDefault();
                                    addTool(event.dataTransfer.getData('text/plain') || draggedTool);
                                });
                            }

                            if (search) {
                                search.addEventListener('input', function () {
                                    var query = search.value.trim().toLowerCase();

                                    cards.forEach(function (card) {
                                        card.style.display = ! query || card.dataset.search.indexOf(query) !== -1 ? '' : 'none';
                                    });
                                });
                            }

                            select.addEventListener('change', render);
                            render();
                        });
                    };

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', boot);
                    } else {
                        boot();
                    }
                })();
            </script>
        HTML;
    }
}

if (is_plugin_active('portfolio')) {
    SlugHelper::registering(function (): void {
        SlugHelper::removeModule([
            Package::class,
            ServiceCategory::class,
        ]);
    });

    SeoHelper::removeModule([
        Package::class,
        ServiceCategory::class,
    ]);

    if (is_plugin_active('language-advanced')) {
        LanguageAdvancedManager::removeModule([
            CustomField::class,
            CustomFieldOption::class,
        ]);
    }

    FormFrontManager::remove(QuotationForm::class);
}

app()->booted(function (): void {
    register_sidebar([
        'id' => 'footer_sidebar',
        'name' => __('Footer Sidebar'),
        'description' => __('Displays the site logo, quick links, and copyright info.'),
    ]);

    register_page_template([
        'sidebar' => __('Sidebar'),
        'has-heading' => __('Has Heading'),
    ]);

    register_sidebar([
        'id' => 'sidebar_panel_sidebar',
        'name' => __('Panel Sidebar'),
        'description' => __('Displays widgets inside a slide-out panel, shown on desktop.'),
    ]);

    RvMedia::addSize('post-thumbnail', 1200, 800);

    Theme::typography()
        ->registerFontFamilies([
            new TypographyItem('primary', __('Primary'), 'Playfair Display'),
            new TypographyItem('secondary', __('Secondary'), 'Urbanist'),
        ]);

    ThemeSupport::registerSocialLinks();
    ThemeSupport::registerSiteCopyright();
    ThemeSupport::registerSocialSharing();
    ThemeSupport::registerLazyLoadImages();

    function get_header_style(): int
    {
        $headerStyle = theme_option('header_style', 1);

        return in_array($headerStyle, range(1, 3)) ? $headerStyle : 1;
    }

    function get_footer_style(): int
    {
        $footerStyle = theme_option('footer_style', 1);

        return in_array($footerStyle, range(1, 3)) ? $footerStyle : 1;
    }

    if (is_plugin_active('portfolio')) {
        SiteMapManager::removeKey('packages');
        SiteMapManager::removeKey('service-categories');

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->removeItem([
                    'cms-core-portfolio-quotation-requests',
                    'cms-core-portfolio-custom-fields',
                ]);
        });

        Event::listen(RouteMatched::class, function (): void {
            EmailHandler::removeTemplateSettings('portfolio');
        });

        ProjectForm::extend(function (ProjectForm $form): void {
            $form
                ->remove(['author', 'place'])
                ->addBefore(
                    'name',
                    'category_ids',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Category'))
                        ->multiple()
                        ->searchable()
                        ->choices(
                            ServiceCategory::query()
                                ->wherePublished()
                                ->pluck('name', 'id')
                                ->all()
                        )
                        ->required()
                        ->metadata()
                )
                ->addAfter(
                    'client',
                    'link',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Link'))
                        ->helperText(__('Link to the project'))
                        ->metadata()
                )
                ->addAfter(
                    'image',
                    'portfolio_card_specs',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content('
                            <div class="alert alert-info">
                                <strong>Памятка для карточки портфолио</strong>
                                <div class="mt-2">
                                    <div><b>Портрет-стикер:</b> PNG или WebP с прозрачным фоном. Рекомендуемый размер 1200 x 1200 px, минимум 900 x 900 px.</div>
                                    <div><b>Кадрирование:</b> голова или бюст по центру, без встроенной тени и без белого канта. Оставьте 8-12% прозрачного поля вокруг силуэта.</div>
                                    <div><b>Основное изображение проекта:</b> JPG/PNG/WebP 1920 x 1080 px или 1600 x 1000 px. Это полноценный render/preview для внутренней страницы.</div>
                                    <div><b>Галерея проекта:</b> ширина от 1600 px, желательно единый стиль и близкие пропорции, чтобы сетка внутри проекта выглядела ровно.</div>
                                    <div><b>Что делает сайт:</b> автоматически добавляет белый кант, тень, бумажную карточку, клетку и техническую рамку.</div>
                                    <div><b>Обычная картинка проекта:</b> используйте для полного render/preview и галереи внутри страницы.</div>
                                </div>
                            </div>
                        ')
                )
                ->addAfter(
                    'portfolio_card_specs',
                    'portrait_image',
                    'mediaImage',
                    MediaImageFieldOption::make()
                        ->label('Портрет-стикер для карточки')
                        ->helperText('PNG/WebP с прозрачным фоном, 1200 x 1200 px. Голова или бюст по центру, без тени и без канта.')
                        ->metadata()
                )
                ->addAfter(
                    'portrait_image',
                    'card_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Тип работы на карточке')
                        ->placeholder('CHARACTER / PROP / WORLD / PRODUCT')
                        ->metadata()
                )
                ->addAfter(
                    'card_label',
                    'card_subtitle',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Подзаголовок карточки')
                        ->placeholder('Orc warlord / Creature rig / Product animation')
                        ->metadata()
                )
                ->addAfter(
                    'card_subtitle',
                    'tools',
                    TextareaField::class,
                    TextareaFieldOption::make()
                        ->label('Инструменты и технологии')
                        ->helperText('Один инструмент в строку. Например: Blender, ZBrush, Substance 3D Painter, Houdini.')
                        ->rows(5)
                        ->metadata()
                )
                ->addAfter(
                    'tools',
                    'images[]',
                    'mediaImages',
                    MediaImagesFieldOption::make()
                        ->label('Галерея проекта')
                )
                ->addAfter(
                    'link',
                    'github_url',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('GitHub repository URL'))
                        ->helperText(__('Leave empty if not available. e.g: https://github.com/...'))
                        ->metadata()
                );
        }, 1000);

        ProjectForm::extend(function (ProjectForm $form): void {
            $form
                ->columns()
                ->remove(['portfolio_card_specs', 'portrait_image', 'card_label', 'card_subtitle', 'tools', 'tools_preview', 'images[]'])
                ->add('name', TextField::class, NameFieldOption::make()->label('Название проекта')->required()->colspan(1), true)
                ->add(
                    'description',
                    TextareaField::class,
                    DescriptionFieldOption::make()
                        ->label('Краткое описание')
                        ->helperText('Короткий текст для карточки. Лучше 1-2 предложения.')
                        ->colspan(1),
                    true
                )
                ->add(
                    'image',
                    'mediaImage',
                    [
                        'label' => 'Основное изображение проекта',
                        'help_block' => [
                            'text' => 'Render/preview для внутренней страницы проекта: 1920 x 1080 px или 1600 x 1000 px.',
                            'tag' => 'small',
                            'attr' => ['class' => 'form-hint'],
                        ],
                        'colspan' => 1,
                    ],
                    true
                )
                ->add('client', TextField::class, TextFieldOption::make()->label('Клиент / проект')->placeholder('Poligonium / корпоративный клиент / личный проект')->colspan(1), true)
                ->add('start_date', 'datePicker', ['label' => 'Дата создания', 'colspan' => 1], true)
                ->add(
                    'category_ids',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label('Категории портфолио')
                        ->multiple()
                        ->searchable()
                        ->choices(ServiceCategory::query()->wherePublished()->pluck('name', 'id')->all())
                        ->required()
                        ->helperText('Можно выбрать несколько направлений, например 3D-моделирование и 3D-анимация.')
                        ->colspan(1)
                        ->metadata(),
                    true
                )
                ->add(
                    'link',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Ссылка на проект')
                        ->helperText('Можно оставить пустым.')
                        ->colspan(1)
                        ->metadata(),
                    true
                )
                ->addAfter(
                    'image',
                    'portfolio_card_specs',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content('
                            <div class="alert alert-info">
                                <strong>Памятка для карточки портфолио</strong>
                                <div class="mt-2">
                                    <div><b>Портрет-стикер:</b> PNG или WebP с прозрачным фоном. Рекомендуемый размер 1200 x 1200 px, минимум 900 x 900 px.</div>
                                    <div><b>Кадрирование:</b> голова или бюст без встроенной тени и без белого канта. Белый кант и тень сайт добавляет сам.</div>
                                    <div><b>Основное изображение:</b> JPG/PNG/WebP 1920 x 1080 px или 1600 x 1000 px для внутренней страницы.</div>
                                    <div><b>Галерея:</b> ширина от 1600 px, желательно единый стиль и близкие пропорции.</div>
                                </div>
                            </div>
                        ')
                        ->colspan(2)
                )
                ->addAfter(
                    'portfolio_card_specs',
                    'portrait_image',
                    'mediaImage',
                    MediaImageFieldOption::make()
                        ->label('Портрет-стикер для карточки')
                        ->helperText('PNG/WebP с прозрачным фоном, 1200 x 1200 px. Голова или бюст без тени и без канта.')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'portrait_image',
                    'card_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Тип работы на карточке')
                        ->placeholder('Персонаж / проп / мир / продукт')
                        ->helperText('Это маленькая плашка сверху слева на карточке.')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'card_label',
                    'card_subtitle',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Подзаголовок карточки')
                        ->placeholder('Корпоративный клиент / личный проект / реклама продукта')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'card_subtitle',
                    'tools',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label('Tools & Technologies')
                        ->choices(poligonium_portfolio_tool_options())
                        ->selected(fn () => poligonium_portfolio_normalize_tools($form->getModel()->getMetaData('tools', true)))
                        ->multiple()
                        ->searchable()
                        ->allowClear()
                        ->helperText('Выбирай программы из списка. На сайте они будут показаны с логотипами.')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'tools',
                    'tools_preview',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content(poligonium_portfolio_tools_admin_preview())
                        ->colspan(1)
                )
                ->addAfter(
                    'tools_preview',
                    'images[]',
                    'mediaImages',
                    MediaImagesFieldOption::make()
                        ->label('Галерея проекта')
                        ->helperText('Дополнительные изображения для внутренней страницы.')
                        ->colspan(1)
                )
                ->add(
                    'github_url',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('GitHub / дополнительная ссылка')
                        ->helperText('Можно оставить пустым.')
                        ->colspan(1)
                        ->metadata(),
                    true
                );
        }, 500);

        ProjectForm::extend(function (ProjectForm $form): void {
            $form
                ->columns()
                ->remove(['portfolio_card_specs', 'portrait_image', 'card_label', 'card_subtitle', 'tools', 'tools_preview', 'images[]', 'project_video', 'github_url'])
                ->addAfter(
                    'content',
                    'portfolio_card_specs',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content('
                            <div class="alert alert-info">
                                <strong>Памятка для карточки портфолио</strong>
                                <div class="mt-2">
                                    <div><b>Портрет-стикер:</b> PNG или WebP с прозрачным фоном. Рекомендуемый размер 1200 x 1200 px, минимум 900 x 900 px.</div>
                                    <div><b>Кадрирование:</b> голова или бюст без встроенной тени и без белого канта. Белый кант, тень, бумажную карточку, клетку и техническую рамку сайт добавляет сам.</div>
                                    <div><b>Плашка категории:</b> короткий текст сверху слева на карточке, например: Персонаж, Продукт, 3D-анимация, VR showroom.</div>
                                    <div><b>Основное изображение:</b> JPG/PNG/WebP 1920 x 1080 px или 1600 x 1000 px для внутренней страницы проекта.</div>
                                    <div><b>Галерея-презентация:</b> 3 вертикальных изображения 1080 x 1920 px. Они будут первыми тремя элементами в Presentation Frame.</div>
                                    <div><b>Видео презентации:</b> MP4/WebM 1080 x 1920 px. Видео добавляется отдельным полем ниже галереи и будет четвертым элементом в Presentation Frame.</div>
                                </div>
                            </div>
                        ')
                        ->colspan(2)
                )
                ->addAfter(
                    'portfolio_card_specs',
                    'portrait_image',
                    'mediaImage',
                    MediaImageFieldOption::make()
                        ->label('Портрет-стикер для карточки')
                        ->helperText('PNG/WebP с прозрачным фоном, 1200 x 1200 px. Голова или бюст без тени и без канта.')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'portrait_image',
                    'card_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Плашка категории на карточке')
                        ->placeholder('Персонаж / Продукт / 3D-анимация / VR showroom')
                        ->helperText('Этот текст будет в верхнем левом углу карточки портфолио.')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'card_label',
                    'card_subtitle',
                    TextField::class,
                    TextFieldOption::make()
                        ->label('Подзаголовок карточки')
                        ->placeholder('Корпоративный клиент / личный проект / реклама продукта')
                        ->helperText('Маленькая строка под названием проекта. Если не нужна, можно оставить пустой.')
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'card_subtitle',
                    'tools',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label('Tools & Technologies')
                        ->choices(poligonium_portfolio_tool_options())
                        ->multiple()
                        ->searchable()
                        ->allowClear()
                        ->wrapperAttributes(['class' => 'poligonium-tools-native-select-wrap', 'style' => 'display: none !important;'])
                        ->colspan(1)
                        ->metadata()
                )
                ->addAfter(
                    'tools',
                    'tools_preview',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content(poligonium_portfolio_tools_dragdrop_admin())
                        ->colspan(2)
                )
                ->addAfter(
                    'tools_preview',
                    'images[]',
                    'mediaImages',
                    MediaImagesFieldOption::make()
                        ->label('Галерея-презентация проекта')
                        ->helperText('Добавь 3 вертикальных изображения 1080 x 1920 px. Если загрузишь больше, на странице проекта будут показаны первые три.')
                        ->colspan(2)
                )
                ->addAfter(
                    'images[]',
                    'project_video',
                    'mediaFile',
                    MediaFileFieldOption::make()
                        ->label('Видео проекта для Presentation Frame')
                        ->helperText('MP4/WebM 1080 x 1920 px. Видео будет четвертым элементом после трех вертикальных изображений.')
                        ->colspan(2)
                        ->metadata()
                );
        }, 100);

        add_filter(BASE_FILTER_AFTER_RENDER_FORM, function ($form, $model) {
            if (! $form instanceof ProjectForm || ! $model instanceof \Botble\Portfolio\Models\Project || ! $model->exists) {
                return $form;
            }

            $model->loadMissing('metadata');

            $setValueIfEmpty = function (string $fieldName, mixed $value) use ($form): void {
                if (! $form->has($fieldName)) {
                    return;
                }

                $field = $form->getField($fieldName);
                $currentValue = $field->getValue();

                if ($currentValue === null || $currentValue === '' || $currentValue === []) {
                    $field->setValue($value);
                }
            };

            $setValueIfEmpty('card_label', poligonium_portfolio_effective_card_label($model));
            $setValueIfEmpty('card_subtitle', poligonium_portfolio_effective_card_subtitle($model));
            $setValueIfEmpty('portrait_image', $model->getMetaData('portrait_image', true));

            if ($form->has('tools')) {
                $toolsField = $form->getField('tools');
                $tools = poligonium_portfolio_normalize_tools($toolsField->getValue());

                $toolsField->setValue($tools ?: poligonium_portfolio_effective_tools($model));
            }

            return $form;
        }, 100, 2);

        ProjectForm::afterSaving(function (ProjectForm $form): void {
            $model = $form->getModel();
            $request = $form->getRequest();

            if (! $model instanceof \Botble\Portfolio\Models\Project || ! $request->has('_poligonium_tools_builder')) {
                return;
            }

            $tools = poligonium_portfolio_normalize_tools($request->input('tools', []));

            if ($tools) {
                \Botble\Base\Facades\MetaBox::saveMetaBoxData($model, 'tools', $tools);
            } else {
                \Botble\Base\Facades\MetaBox::deleteMetaData($model, 'tools');
            }

            foreach (['card_label', 'card_subtitle'] as $field) {
                if (! $request->has($field)) {
                    continue;
                }

                $value = trim((string) $request->input($field));

                if ($value !== '') {
                    \Botble\Base\Facades\MetaBox::saveMetaBoxData($model, $field, $value);
                } else {
                    \Botble\Base\Facades\MetaBox::deleteMetaData($model, $field);
                }
            }
        }, 100);

        ProjectForm::afterSaving(function (ProjectForm $form): void {
            $model = $form->getModel();
            $request = $form->getRequest();

            if (! $model instanceof \Botble\Portfolio\Models\Project || ! $request->has('project_video')) {
                return;
            }

            $video = trim((string) $request->input('project_video'));

            if ($video !== '') {
                \Botble\Base\Facades\MetaBox::saveMetaBoxData($model, 'project_video', $video);
            } else {
                \Botble\Base\Facades\MetaBox::deleteMetaData($model, 'project_video');
            }
        }, 110);

        ProjectForm::afterSaving(function (ProjectForm $form): void {
            $model = $form->getModel();
            $request = $form->getRequest();

            if (! $model instanceof \Botble\Portfolio\Models\Project) {
                return;
            }

            if ($request->has('portrait_image')) {
                $portrait = trim((string) $request->input('portrait_image'));

                if ($portrait !== '') {
                    \Botble\Base\Facades\MetaBox::saveMetaBoxData($model, 'portrait_image', $portrait);
                } else {
                    \Botble\Base\Facades\MetaBox::deleteMetaData($model, 'portrait_image');
                }
            }

            if ($request->has('images')) {
                $images = array_values(array_filter((array) $request->input('images')));

                if ($images !== array_values(array_filter((array) $model->images))) {
                    $model->forceFill(['images' => $images])->saveQuietly();
                }
            }

            foreach (['card_label', 'card_subtitle'] as $field) {
                if (! $request->has($field)) {
                    continue;
                }

                $value = trim((string) $request->input($field));

                if ($value !== '') {
                    \Botble\Base\Facades\MetaBox::saveMetaBoxData($model, $field, $value);
                } else {
                    \Botble\Base\Facades\MetaBox::deleteMetaData($model, $field);
                }
            }
        }, 1000);

        ProjectForm::extend(function (ProjectForm $form): void {
            $form
                ->remove(['images[]', 'project_video_specs', 'project_video'])
                ->addAfter(
                    'tools_preview',
                    'images[]',
                    'mediaImages',
                    MediaImagesFieldOption::make()
                        ->label('Галерея-презентация проекта')
                        ->helperText('Добавь 3 вертикальных изображения 1080 x 1920 px. Если загрузишь больше, на странице проекта будут показаны первые три.')
                        ->values(array_values(array_filter((array) $form->getModel()->images)))
                        ->colspan(2)
                )
                ->addAfter(
                    'images[]',
                    'project_video_specs',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content('
                            <div class="alert alert-warning">
                                <strong>Видео для Presentation Frame</strong>
                                <div class="mt-2">Ниже выбери MP4/WebM 1080 x 1920 px. На странице проекта это будет четвертый элемент после трех вертикальных изображений.</div>
                            </div>
                        ')
                        ->colspan(2)
                )
                ->addAfter(
                    'project_video_specs',
                    'project_video',
                    'mediaFile',
                    MediaFileFieldOption::make()
                        ->label('Видео проекта для Presentation Frame')
                        ->helperText('MP4/WebM 1080 x 1920 px. Видео будет четвертым элементом после трех вертикальных изображений.')
                        ->value($form->getModel()->getMetaData('project_video', true))
                        ->colspan(2)
                        ->metadata()
                );
        }, 10);

        ProjectForm::extend(function (ProjectForm $form): void {
            $model = $form->getModel();

            if (! $model instanceof \Botble\Portfolio\Models\Project) {
                return;
            }

            $model->loadMissing('metadata');

            if ($form->has('portrait_image')) {
                $form->getField('portrait_image')->setValue($model->getMetaData('portrait_image', true));
            }

            if ($form->has('card_label')) {
                $form->getField('card_label')->setValue($model->getMetaData('card_label', true));
            }

            if ($form->has('card_subtitle')) {
                $form->getField('card_subtitle')->setValue($model->getMetaData('card_subtitle', true));
            }

            if ($form->has('tools')) {
                $form->getField('tools')->setValue(poligonium_portfolio_effective_tools($model));
            }
        }, 1);

        ServiceForm::extend(function (ServiceForm $form): void {
            $form->add(
                'icon',
                CoreIconField::class,
                CoreIconFieldOption::make()
                    ->label(__('Icon'))
                    ->metadata()
            );
        });

        PackageForm::extend(function (PackageForm $form): void {
            $form
                ->remove(['content', 'annual_price', 'is_popular'])
                ->remove('price')
                ->addBefore(
                    'duration',
                    'price',
                    TextField::class,
                    TextFieldOption::make()
                        ->required()
                        ->colspan(2)
                        ->label(trans('plugins/portfolio::portfolio.price'))
                        ->placeholder(trans('plugins/portfolio::portfolio.form.price_placeholder'))
                );
        });

        PackageTable::extend(function (PackageTable $table): void {
            $table->removeColumn('is_popular');
        });
    }

    add_filter('cms_installer_themes', function () {
        return [
            'design' => [
                'label' => 'Zelio - Designer',
                'image' => Theme::asset()->url('images/demos/home-design.png'),
            ],
            'code' => [
                'label' => 'Zelio - Developer',
                'image' => Theme::asset()->url('images/demos/home-code.png'),
            ],
            'write' => [
                'label' => 'Zelio - Writer',
                'image' => Theme::asset()->url('images/demos/home-write.png'),
            ],
        ];
    }, 10);
});
