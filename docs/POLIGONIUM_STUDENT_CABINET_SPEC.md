# Poligonium Student Cabinet / Early Access Specification

This document is the working product and implementation checklist for the Poligonium student cabinet, early-access course sales, ranks, and school gallery.

## Product Goal

Poligonium School should support students before and after a course is complete.

The school flow:

- A course can be sold in early access before all lessons are published.
- Early access has a lower price, for example 1000 UAH while the final course price is 4000 UAH.
- When the course is complete/released, the course switches to the full price.
- Students need a personal cabinet where they see purchased courses, progress, rank, and school activity.
- Students should later be able to share their work in a school gallery.

## Product Terms

- **Full price**: the normal course price after release. Stored in the existing course `price` field.
- **Early access price**: discounted price for students who join before release.
- **Early access student**: a student who bought access before the course was complete.
- **Released course**: course has its full lesson program available and sells at full price.
- **Student cabinet**: personal area for the student: courses, progress, rank, gallery works.
- **School gallery**: public/curated gallery of student works connected to courses or lessons.

## Course Sales States

Courses need a sale state:

- `standard`: normal course, sold at full price.
- `early_access`: course is being created; students can join at early price.
- `released`: course is complete and sold at full price.
- `closed`: course is visible if needed, but purchase is disabled.

Expected course fields:

- `sale_status`
- `early_access_price`
- `early_access_starts_at`
- `early_access_ends_at`
- `released_at`
- `early_access_slots`
- `early_access_sold`

Rules:

- Existing `price` remains the full/final course price.
- If `sale_status = early_access` and the date/slot limits allow it, public course pages should show the early access offer.
- If the course is released, public pages should show the normal full price.
- A student who bought early access keeps access after release.

## Student Cabinet Scope

First usable cabinet should show:

- Student name/avatar.
- Rank and XP.
- Purchased/enrolled courses.
- Course progress.
- Early-access badges.
- Gallery works submitted by the student.

Later cabinet additions:

- Payment history.
- Certificates.
- Course notifications.
- Comments/Q&A for lessons.
- Private feedback from the teacher.

## Student Ranks

Initial ranks:

- `newcomer`: Новичок
- `apprentice`: Ученик
- `maker`: Мейкер
- `artist`: 3D Artist
- `mentor`: Mentor

Rank can be calculated later from XP, completed lessons, gallery approvals, and course completions.
First implementation can store `rank_slug` and `xp` in the profile.

## School Gallery

Gallery work fields:

- Student/user
- Optional course
- Optional lesson
- Title
- Description
- Image
- Video
- Tools/software
- Status: `draft`, `pending`, `approved`, `rejected`
- Featured flag
- Published date

Rules:

- Student submissions should not become public immediately.
- Admin/teacher approval is required before public display.
- Works can be connected to a course/lesson.

## Access / Purchase Direction

Existing tables:

- `plg_course_enrollments`
- `plg_course_purchases`

Expected behavior:

- A successful purchase creates/updates a course enrollment.
- Early access purchase source should be visible in records.
- Purchase rows should store whether this was `early_access`, `full`, `manual`, or future `subscription`.

Payment provider is not part of the first stage.

## Implementation Stages

### Stage 1: Foundation

Status: implemented on 2026-06-16.

Tasks:

- Add course fields for early access.
- Add model helpers for current visible course price.
- Add student profile table.
- Add lesson progress table.
- Add school gallery works table.
- Extend course admin form with early-access settings.
- Update specs and translations.

Implemented:

- Added early-access fields to `plg_courses`.
- Added purchase type/discount fields to `plg_course_purchases`.
- Added `plg_student_profiles`.
- Added `plg_course_progress`.
- Added `plg_school_gallery_projects`.
- Added course model helpers for active early-access price and remaining slots.
- Added admin course fields for sale status, early price, early dates, release date, slot limit, and sold counter.
- Added public course/catalog display support for early-access price.

### Stage 2: Student Account Routes

Status: implemented on 2026-06-16.

Tasks:

- Add public login/register/password pages or connect an existing member/auth system.
- Add `/school/cabinet` route.
- Add cabinet layout in the Poligonium visual style.
- Show enrolled courses, progress, rank, and early-access badges.

Implemented:

- Added public student auth routes:
  - `GET /school/login`
  - `POST /school/login`
  - `GET /school/register`
  - `POST /school/register`
  - `POST /school/logout`
  - `GET /school/cabinet`
- Registration creates a normal site user compatible with the existing Botble `users` table.
- Registration also creates a `plg_student_profiles` row with rank `newcomer` and `xp = 0`.
- Student cabinet shows profile name, rank, XP, active courses, purchases, progress, and school gallery placeholders.
- Login/register/cabinet pages use the Poligonium grid/orb visual style and do not expose admin concepts.

### Stage 3: Purchase Flow

Status: manual-confirmation MVP implemented on 2026-06-16.

Tasks:

- Add purchase CTA on course detail.
- Create pending purchase records.
- Integrate payment provider.
- Confirm payment and create enrollment.
- Keep early-access price locked for early buyers.

Implemented:

- Added public purchase route: `POST /courses/{course}/purchase`.
- Course detail page shows purchase/early-access CTA.
- Guests are sent to `/school/login` before purchase.
- Free courses create an active enrollment immediately.
- Paid courses create a `plg_course_purchases` row with status `pending`.
- Early-access purchases store the early price, full price, discount amount, currency, and purchase type.
- Added admin purchases section: `/admin/courses/purchases`.
- Admin can manually confirm a pending purchase.
- Confirming a purchase changes status to `paid`, creates/updates active course enrollment, and increments `early_access_sold` for early-access purchases.
- Student cabinet lists purchases with readable purchase type and status.

Still pending for a production payment flow:

- Add real `MONOPAY_TOKEN` from monobank merchant/acquiring cabinet.
- Run a real Monopay test payment after token is configured.
- Failed/expired payment cleanup.
- Payment receipt emails.

Monopay implementation notes:

- Monopay config lives in `.env`:
  - `MONOPAY_TOKEN`
  - `MONOPAY_API_URL`
  - `MONOPAY_INVOICE_VALIDITY`
  - `MONOPAY_WEBHOOK_URL`
  - `MONOPAY_REDIRECT_URL`
- Public purchase creates a Monopay invoice and redirects the student to `provider_page_url`.
- Webhook endpoint is `POST /courses/monopay/webhook`.
- Webhook is excluded from CSRF and protected by Monopay `X-Sign` verification.
- Successful webhook status `success` marks the purchase as `paid` and grants course access.
- Monopay invoice id, payment page url, provider status, modified date, and raw payload are stored on `plg_course_purchases`.

### Stage 4: School Gallery

Status: pending.

Tasks:

- Student work submission form.
- Admin moderation.
- Public gallery page.
- Course/lesson-linked gallery blocks.

### Stage 5: Gamification

Status: pending.

Tasks:

- XP events.
- Rank calculation.
- Badges/achievements.
- Student profile public card.

## Non-Negotiable UX Decisions

- Students should not see admin concepts such as raw IDs, technical paths, or database-like menus.
- Admin course editing remains one course workspace.
- Early access should be obvious: old/full price, early price, status, and what the student receives.
- Payment and access logic must not depend on deleting/recreating course content.
- Public content remains Ukrainian by default; admin labels remain Russian-first for the site owner.
