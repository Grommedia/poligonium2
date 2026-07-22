@php
    /** @var \Botble\Campaigns\Models\Campaign $campaign */
    $rewards = $campaign->rewards;
    $updates = $campaign->updates;
    $teamMembers = $campaign->teamMembers;
    $faqs = $campaign->faqs;
    $formatMoney = fn ($amount, $currency = 'UAH') => number_format((float) $amount, 0, '.', ' ') . ' ' . $currency;
@endphp

<div
    id="campaign-project-builder"
    data-store-reward-url="{{ route('campaigns.campaigns.builder.rewards.store', $campaign) }}"
    data-store-update-url="{{ route('campaigns.campaigns.builder.updates.store', $campaign) }}"
    data-store-team-url="{{ route('campaigns.campaigns.builder.team.store', $campaign) }}"
    data-store-faq-url="{{ route('campaigns.campaigns.builder.faqs.store', $campaign) }}"
    data-csrf-token="{{ csrf_token() }}"
>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="border rounded p-3 h-100">
                <div class="text-muted small">{{ trans('plugins/campaigns::campaigns.rewards') }}</div>
                <div class="fs-2 fw-bold">{{ $rewards->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded p-3 h-100">
                <div class="text-muted small">{{ trans('plugins/campaigns::campaigns.updates') }}</div>
                <div class="fs-2 fw-bold">{{ $updates->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded p-3 h-100">
                <div class="text-muted small">{{ trans('plugins/campaigns::campaigns.team') }}</div>
                <div class="fs-2 fw-bold">{{ $teamMembers->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded p-3 h-100">
                <div class="text-muted small">{{ trans('plugins/campaigns::campaigns.faqs') }}</div>
                <div class="fs-2 fw-bold">{{ $faqs->count() }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="border rounded p-3 h-100 bg-light">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.add_reward') }}</h4>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="campaign-reward-title">{{ trans('plugins/campaigns::campaigns.reward_title') }}</label>
                        <input class="form-control" id="campaign-reward-title" type="text" data-campaign-builder-field="reward-title">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="campaign-reward-amount">{{ trans('plugins/campaigns::campaigns.amount') }}</label>
                        <input class="form-control" id="campaign-reward-amount" type="number" min="0" data-campaign-builder-field="reward-amount">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="campaign-reward-description">{{ trans('core/base::forms.description') }}</label>
                        <textarea class="form-control" id="campaign-reward-description" rows="2" data-campaign-builder-field="reward-description"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="campaign-reward-includes">{{ trans('plugins/campaigns::campaigns.includes') }}</label>
                        <textarea class="form-control" id="campaign-reward-includes" rows="3" data-campaign-builder-field="reward-includes" placeholder="{{ trans('plugins/campaigns::campaigns.includes_placeholder') }}"></textarea>
                    </div>
                    <div class="col-12 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <label class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" data-campaign-builder-field="reward-featured">
                            <span class="form-check-label">{{ trans('plugins/campaigns::campaigns.featured') }}</span>
                        </label>
                        <button class="btn btn-primary" type="button" data-campaign-builder-action="add-reward">
                            {{ trans('plugins/campaigns::campaigns.add_reward') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="border rounded p-3 h-100 bg-light">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.add_update') }}</h4>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="campaign-update-title">{{ trans('plugins/campaigns::campaigns.title') }}</label>
                        <input class="form-control" id="campaign-update-title" type="text" data-campaign-builder-field="update-title">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="campaign-update-published-at">{{ trans('plugins/campaigns::campaigns.published_at') }}</label>
                        <input class="form-control" id="campaign-update-published-at" type="date" data-campaign-builder-field="update-published-at">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="campaign-update-content">{{ trans('plugins/campaigns::campaigns.content') }}</label>
                        <textarea class="form-control" id="campaign-update-content" rows="5" data-campaign-builder-field="update-content"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="button" data-campaign-builder-action="add-update">
                            {{ trans('plugins/campaigns::campaigns.add_update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="border rounded p-3 h-100 bg-light">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.add_team_member') }}</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="campaign-team-name">{{ trans('plugins/campaigns::campaigns.member_name') }}</label>
                        <input class="form-control" id="campaign-team-name" type="text" data-campaign-builder-field="team-name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="campaign-team-role">{{ trans('plugins/campaigns::campaigns.role') }}</label>
                        <input class="form-control" id="campaign-team-role" type="text" data-campaign-builder-field="team-role">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="campaign-team-bio">{{ trans('plugins/campaigns::campaigns.bio') }}</label>
                        <textarea class="form-control" id="campaign-team-bio" rows="3" data-campaign-builder-field="team-bio"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="button" data-campaign-builder-action="add-team">
                            {{ trans('plugins/campaigns::campaigns.add_team_member') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="border rounded p-3 h-100 bg-light">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.add_faq') }}</h4>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="campaign-faq-question">{{ trans('plugins/campaigns::campaigns.question') }}</label>
                        <input class="form-control" id="campaign-faq-question" type="text" data-campaign-builder-field="faq-question">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="campaign-faq-answer">{{ trans('plugins/campaigns::campaigns.answer') }}</label>
                        <textarea class="form-control" id="campaign-faq-answer" rows="3" data-campaign-builder-field="faq-answer"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="button" data-campaign-builder-action="add-faq">
                            {{ trans('plugins/campaigns::campaigns.add_faq') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="border rounded p-3 h-100">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.rewards') }}</h4>
                @forelse ($rewards as $reward)
                    <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                        <div>
                            <strong>{{ $reward->title }}</strong>
                            <div class="text-muted small">{{ $formatMoney($reward->amount, $reward->currency) }}</div>
                        </div>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('campaigns.rewards.edit', $reward->getKey()) }}">
                            {{ trans('plugins/campaigns::campaigns.open_full_editor') }}
                        </a>
                    </div>
                @empty
                    <div class="text-muted">{{ trans('plugins/campaigns::campaigns.no_rewards') }}</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-6">
            <div class="border rounded p-3 h-100">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.updates') }}</h4>
                @forelse ($updates as $update)
                    <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                        <div>
                            <strong>{{ $update->title }}</strong>
                            <div class="text-muted small">{{ optional($update->published_at ?: $update->created_at)->format('d.m.Y') }}</div>
                        </div>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('campaigns.updates.edit', $update->getKey()) }}">
                            {{ trans('plugins/campaigns::campaigns.open_full_editor') }}
                        </a>
                    </div>
                @empty
                    <div class="text-muted">{{ trans('plugins/campaigns::campaigns.no_updates') }}</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-6">
            <div class="border rounded p-3 h-100">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.team') }}</h4>
                @forelse ($teamMembers as $member)
                    <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                        <div>
                            <strong>{{ $member->name }}</strong>
                            @if ($member->role)
                                <div class="text-muted small">{{ $member->role }}</div>
                            @endif
                        </div>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('campaigns.team.edit', $member->getKey()) }}">
                            {{ trans('plugins/campaigns::campaigns.open_full_editor') }}
                        </a>
                    </div>
                @empty
                    <div class="text-muted">{{ trans('plugins/campaigns::campaigns.no_team') }}</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-6">
            <div class="border rounded p-3 h-100">
                <h4 class="mb-3">{{ trans('plugins/campaigns::campaigns.faqs') }}</h4>
                @forelse ($faqs as $faq)
                    <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                        <div>
                            <strong>{{ $faq->question }}</strong>
                        </div>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('campaigns.faqs.edit', $faq->getKey()) }}">
                            {{ trans('plugins/campaigns::campaigns.open_full_editor') }}
                        </a>
                    </div>
                @empty
                    <div class="text-muted">{{ trans('plugins/campaigns::campaigns.no_faqs') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('footer')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const builder = document.getElementById('campaign-project-builder')

            if (!builder) {
                return
            }

            const field = (name) => builder.querySelector(`[data-campaign-builder-field="${name}"]`)
            const token = builder.dataset.csrfToken

            const post = (url, payload, button) => {
                const formData = new FormData()

                Object.entries(payload).forEach(([key, value]) => {
                    formData.append(key, value ?? '')
                })

                button.disabled = true

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}))

                        if (!response.ok || data.error) {
                            throw data
                        }

                        if (window.Botble) {
                            Botble.showSuccess(data.message || '{{ trans('core/base::notices.create_success_message') }}')
                        }

                        window.setTimeout(() => window.location.reload(), 500)
                    })
                    .catch((error) => {
                        const firstError = error?.errors ? Object.values(error.errors).flat()[0] : null
                        const message = firstError || error?.message || '{{ trans('core/base::notices.error') }}'

                        if (window.Botble) {
                            Botble.showError(message)
                        } else {
                            alert(message)
                        }
                    })
                    .finally(() => {
                        button.disabled = false
                    })
            }

            builder.querySelector('[data-campaign-builder-action="add-reward"]')?.addEventListener('click', (event) => {
                post(builder.dataset.storeRewardUrl, {
                    title: field('reward-title')?.value,
                    amount: field('reward-amount')?.value,
                    description: field('reward-description')?.value,
                    includes: field('reward-includes')?.value,
                    is_featured: field('reward-featured')?.checked ? 1 : 0,
                }, event.currentTarget)
            })

            builder.querySelector('[data-campaign-builder-action="add-update"]')?.addEventListener('click', (event) => {
                post(builder.dataset.storeUpdateUrl, {
                    title: field('update-title')?.value,
                    content: field('update-content')?.value,
                    published_at: field('update-published-at')?.value,
                }, event.currentTarget)
            })

            builder.querySelector('[data-campaign-builder-action="add-team"]')?.addEventListener('click', (event) => {
                post(builder.dataset.storeTeamUrl, {
                    name: field('team-name')?.value,
                    role: field('team-role')?.value,
                    bio: field('team-bio')?.value,
                }, event.currentTarget)
            })

            builder.querySelector('[data-campaign-builder-action="add-faq"]')?.addEventListener('click', (event) => {
                post(builder.dataset.storeFaqUrl, {
                    question: field('faq-question')?.value,
                    answer: field('faq-answer')?.value,
                }, event.currentTarget)
            })
        })
    </script>
@endpush
