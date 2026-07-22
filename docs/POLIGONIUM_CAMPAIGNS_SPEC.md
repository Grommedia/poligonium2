# Poligonium Campaigns / Support Projects

## Goal

The site must support Kickstarter-style creative campaigns: a project page, a funding goal, editable reward tiers, supporter counters, and a public form where a visitor selects a reward and leaves contact details.

## Current Public Flow

- Public list: `/support`
- Public campaign page: `/support/{campaign}`
- Example campaign: `/support/karolina-i-charivna-doroha`
- A visitor can pick a reward tier.
- When a reward is selected, the form amount is forced to the reward price on the backend.
- The submitted record is stored as a campaign support request.

## Admin Flow

- Campaigns are edited in the Campaigns plugin.
- Rewards are editable separately and attached to campaigns.
- Each reward has:
  - title
  - amount
  - currency
  - short description
  - includes list
  - delivery note
  - featured flag
  - manual backers counter
- Support requests are visible in the admin panel.

## Karolina Seed Campaign

Seed class: `PoligoniumKarolinaCampaignSeeder`

Campaign:

- Title: `Кароліна і чарівна дорога`
- Slug: `karolina-i-charivna-doroha`
- Currency: `USD`
- Target amount: `150000`
- Existing/manual amount: `91240`
- Reward tiers: `18`
- Manual backers total: `475`

All imported reward prices are 20% lower than the source table supplied by the owner.

## Verification Checklist

- `php artisan migrate --force`
- `php artisan db:seed --class=PoligoniumKarolinaCampaignSeeder --force`
- `php artisan optimize:clear`
- Open `/support/karolina-i-charivna-doroha`
- Confirm the page shows 18 rewards.
- Click a reward and confirm the form selected reward and amount change.
- Submit a support request only in a controlled test or from the real public page.
- Confirm backend stores selected reward id and reward amount, not a tampered custom amount.

## Next Payment Layer

Courses already have a Monopay implementation. Campaigns currently use support requests. The next step is to add Monopay invoices for campaign rewards:

- add provider fields to campaign support requests or campaign contributions
- create a campaign Monopay service or generalize the courses Monopay service
- redirect supporters to Monopay after selecting a reward
- process the Monopay webhook and convert paid requests into confirmed contributions
- update public totals from confirmed payments
