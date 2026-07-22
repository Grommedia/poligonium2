@php
    $formatMoney = function ($amount, $currency = 'UAH') {
        $amount = number_format((float) $amount, 0, '.', ' ');

        return $currency === 'USD' ? '$' . $amount : $amount . ' ' . $currency;
    };

    $budgetItems = [
        ['label' => 'Сценарій, режисура, концепт-арт', 'percent' => 15],
        ['label' => '3D-персонажі, світи та реквізит', 'percent' => 25],
        ['label' => 'Ригінг, анімація, VFX', 'percent' => 30],
        ['label' => 'Рендеринг, звук, композитинг', 'percent' => 20],
        ['label' => 'Резерв виробництва', 'percent' => 10],
    ];

    $stages = \Botble\Campaigns\Support\CampaignOptions::stages();
    $currentStageIndex = max(0, array_search($campaign->production_stage, array_keys($stages), true) ?: 0);
    $publicSupporters = $campaign->confirmedContributions->where('is_public', true)->take(8);
    $firstReward = $campaign->rewards->first();
@endphp

<section class="poligonium-campaigns-page poligonium-campaign-detail">
    <div class="poligonium-campaigns-grid-bg" aria-hidden="true"></div>

    <div class="poligonium-campaigns-wrap">
        <a class="poligonium-campaign-back" href="{{ route('campaigns.public.index') }}">← Усі проєкти</a>

        <header class="poligonium-campaign-detail-hero">
            <div class="poligonium-campaign-detail-media">
                @if ($campaign->image)
                    <img src="{{ RvMedia::getImageUrl($campaign->image) }}" alt="{{ $campaign->name }}">
                @else
                    <div class="poligonium-campaign-fallback-art" aria-hidden="true">
                        <span></span><span></span><span></span>
                    </div>
                @endif
            </div>

            <div class="poligonium-campaign-detail-copy">
                <p class="poligonium-campaigns-kicker">{{ $campaign->state_label }} · {{ $campaign->stage_label }}</p>
                <h1>{{ $campaign->name }}</h1>

                @if ($campaign->subtitle)
                    <p class="poligonium-campaign-lead">{{ $campaign->subtitle }}</p>
                @endif

                <p>{{ $campaign->description }}</p>

                <div class="poligonium-campaign-progress is-large">
                    <div>
                        <strong>{{ $formatMoney($campaign->collected_amount, $campaign->currency) }}</strong>
                        <span>зібрано з {{ $formatMoney($campaign->target_amount, $campaign->currency) }}</span>
                    </div>
                    <b>{{ $campaign->progress_percent }}%</b>
                    <i style="--progress: {{ $campaign->progress_percent }}%"></i>
                </div>

                <div class="poligonium-campaign-hero-stats">
                    <span>{{ $campaign->rewards->sum('backers_count') }} бекерів</span>
                    <span>{{ $campaign->rewards->count() }} пакетів підтримки</span>
                    <span>до {{ optional($campaign->ends_at)->format('d.m.Y') ?: 'релізу' }}</span>
                </div>

                <div class="poligonium-campaign-actions">
                    <a href="#rewards">Обрати пакет</a>
                    <a href="#support-form">Підтримати без винагороди</a>
                </div>
            </div>
        </header>

        <div class="poligonium-campaign-layout">
            <main>
                @if ($campaign->content)
                    <section class="poligonium-campaign-section">
                        <h2>Про проєкт</h2>
                        <div class="poligonium-campaign-content">{!! BaseHelper::clean($campaign->content) !!}</div>
                    </section>
                @endif

                <section class="poligonium-campaign-section">
                    <h2>Куди підуть кошти</h2>
                    <div class="poligonium-campaign-budget">
                        @foreach ($budgetItems as $item)
                            <div class="poligonium-campaign-budget-row">
                                <div>
                                    <strong>{{ $item['label'] }}</strong>
                                    <span>{{ $item['percent'] }}%</span>
                                </div>
                                <i style="--budget: {{ $item['percent'] }}%"></i>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="poligonium-campaign-section">
                    <h2>Дорожня карта виробництва</h2>
                    <div class="poligonium-campaign-roadmap">
                        @foreach ($stages as $stageKey => $stageLabel)
                            @php($stageIndex = $loop->index)
                            <div @class([
                                'is-done' => $stageIndex < $currentStageIndex,
                                'is-current' => $stageKey === $campaign->production_stage,
                            ])>
                                <span>{{ $loop->iteration }}</span>
                                <strong>{{ $stageLabel }}</strong>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="poligonium-campaign-section">
                    <h2>Спонсори проєкту</h2>
                    @if ($publicSupporters->isNotEmpty())
                        <div class="poligonium-campaign-supporters">
                            @foreach ($publicSupporters as $supporter)
                                <article>
                                    <strong>{{ $supporter->donor_name ?: 'Анонімний спонсор' }}</strong>
                                    <span>{{ $formatMoney($supporter->amount, $supporter->currency) }}</span>
                                    @if ($supporter->message)
                                        <p>{{ $supporter->message }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="poligonium-campaign-soft-note">
                            Перші спонсори зʼявляться тут після підтвердження підтримки. Стартові цифри в пакетах показують попередній інтерес до нагород.
                        </div>
                    @endif
                </section>

                @if ($campaign->teamMembers->isNotEmpty())
                    <section class="poligonium-campaign-section">
                        <h2>Команда</h2>
                        <div class="poligonium-campaign-team">
                            @foreach ($campaign->teamMembers as $member)
                                <article>
                                    @if ($member->avatar)
                                        <img src="{{ RvMedia::getImageUrl($member->avatar) }}" alt="{{ $member->name }}">
                                    @endif
                                    <strong>{{ $member->name }}</strong>
                                    @if ($member->role)
                                        <span>{{ $member->role }}</span>
                                    @endif
                                    @if ($member->bio)
                                        <p>{{ $member->bio }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($campaign->updates->isNotEmpty())
                    <section class="poligonium-campaign-section">
                        <h2>Оновлення виробництва</h2>
                        <div class="poligonium-campaign-updates">
                            @foreach ($campaign->updates as $update)
                                <article>
                                    <time>{{ optional($update->published_at ?: $update->created_at)->format('d.m.Y') }}</time>
                                    <h3>{{ $update->title }}</h3>
                                    @if ($update->content)
                                        <div>{!! BaseHelper::clean($update->content) !!}</div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($campaign->faqs->isNotEmpty())
                    <section class="poligonium-campaign-section">
                        <h2>Питання</h2>
                        <div class="poligonium-campaign-faqs">
                            @foreach ($campaign->faqs as $faq)
                                <details>
                                    <summary>{{ $faq->question }}</summary>
                                    <p>{{ $faq->answer }}</p>
                                </details>
                            @endforeach
                        </div>
                    </section>
                @endif
            </main>

            <aside id="rewards" class="poligonium-campaign-rewards">
                <h2>Пакети підтримки</h2>

                @forelse ($campaign->rewards as $reward)
                    <article @class(['is-featured' => $reward->is_featured])>
                        <div class="poligonium-reward-topline">
                            <strong>{{ $formatMoney($reward->amount, $reward->currency) }}</strong>
                            <span>{{ $reward->backers_count }} бекерів</span>
                        </div>

                        <h3>{{ $reward->title }}</h3>
                        <p>{{ $reward->description }}</p>

                        @if ($reward->estimated_delivery)
                            <div class="poligonium-reward-delivery">Орієнтовно: {{ $reward->estimated_delivery }}</div>
                        @endif

                        @if ($reward->includes_list)
                            <ul>
                                @foreach ($reward->includes_list as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <button
                            type="button"
                            data-campaign-reward
                            data-reward-id="{{ $reward->getKey() }}"
                            data-reward-title="{{ $reward->title }}"
                            data-reward-amount="{{ $reward->amount }}"
                            data-reward-price="{{ $formatMoney($reward->amount, $reward->currency) }}"
                        >
                            Обрати цей пакет
                        </button>
                    </article>
                @empty
                    <article>
                        <h3>Пакети готуються</h3>
                        <p>Додайте рівні підтримки в адмінці, щоб глядачі бачили, що вони отримують за участь.</p>
                    </article>
                @endforelse

                <form id="support-form" class="poligonium-campaign-support-form" method="POST" action="{{ route('campaigns.public.support', $campaign) }}">
                    @csrf
                    <h2>Підтримати проєкт</h2>

                    @if (session('campaign_support_success'))
                        <div class="poligonium-campaign-form-success">{{ session('campaign_support_success') }}</div>
                    @endif

                    <div class="poligonium-selected-reward" data-selected-reward>
                        @if ($firstReward)
                            <strong>{{ $firstReward->title }}</strong>
                            <span>{{ $formatMoney($firstReward->amount, $firstReward->currency) }}</span>
                        @else
                            <strong>Без винагороди</strong>
                            <span>Вкажіть суму вручну</span>
                        @endif
                    </div>

                    <label>
                        <span>Пакет підтримки</span>
                        <select name="reward_id" data-reward-select>
                            <option value="" data-amount="">Без винагороди</option>
                            @foreach ($campaign->rewards as $reward)
                                <option
                                    value="{{ $reward->getKey() }}"
                                    data-amount="{{ $reward->amount }}"
                                    data-title="{{ $reward->title }}"
                                    data-price="{{ $formatMoney($reward->amount, $reward->currency) }}"
                                    @selected((string) old('reward_id', optional($firstReward)->getKey()) === (string) $reward->getKey())
                                >
                                    {{ $reward->title }} - {{ $formatMoney($reward->amount, $reward->currency) }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        <span>Сума підтримки</span>
                        <input name="amount" data-reward-amount type="number" min="1" value="{{ old('amount', $firstReward->amount ?? 1000) }}">
                    </label>

                    <label>
                        <span>Імʼя</span>
                        <input name="name" type="text" value="{{ old('name') }}" required>
                    </label>

                    <label>
                        <span>Email</span>
                        <input name="email" type="email" value="{{ old('email') }}">
                    </label>

                    <label>
                        <span>Телефон або Telegram</span>
                        <input name="phone" type="text" value="{{ old('phone') }}" placeholder="+380 або @username">
                    </label>

                    <label>
                        <span>Коментар</span>
                        <textarea name="message" rows="4">{{ old('message') }}</textarea>
                    </label>

                    @if ($errors->any())
                        <div class="poligonium-campaign-form-error">{{ $errors->first() }}</div>
                    @endif

                    <button type="submit">Надіслати заявку</button>
                </form>
            </aside>
        </div>
    </div>
</section>

<script>
    (() => {
        const root = document.querySelector('.poligonium-campaign-detail');
        if (!root) return;

        const select = root.querySelector('[data-reward-select]');
        const amount = root.querySelector('[data-reward-amount]');
        const selected = root.querySelector('[data-selected-reward]');
        const form = root.querySelector('#support-form');

        const applyReward = (option) => {
            if (!option || !amount || !selected) return;

            const rewardAmount = option.dataset.amount || '';
            const title = option.dataset.title || 'Без винагороди';
            const price = option.dataset.price || 'Вкажіть суму вручну';

            if (rewardAmount) {
                amount.value = rewardAmount;
            }

            selected.innerHTML = `<strong>${title}</strong><span>${price}</span>`;
        };

        root.querySelectorAll('[data-campaign-reward]').forEach((button) => {
            button.addEventListener('click', () => {
                if (!select) return;

                select.value = button.dataset.rewardId;
                applyReward(select.selectedOptions[0]);
                form?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        select?.addEventListener('change', () => applyReward(select.selectedOptions[0]));
    })();
</script>

@include('theme.zelio::views.campaigns.partials.styles')
