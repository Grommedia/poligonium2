# Codex Self-Check For Poligonium

Use this checklist before and after substantial Poligonium work.

## Must Read First

- `docs/POLIGONIUM_COURSES_LMS_SPEC.md` for courses/LMS/admin work.
- `docs/POLIGONIUM_STUDENT_CABINET_SPEC.md` for student cabinet, early access, purchases, ranks, or school gallery work.

## Global Product Decisions

- Blog is not used.
- Standard public comments are not used.
- Do not re-enable `blog` or `fob-comment` unless the user explicitly requests it.
- Courses must be a dedicated system/module.
- Public course navigation should be `Курси` / `Навчальні курси`, not `Блог`.

## Before Editing

- Inspect the current files/routes/database state.
- Prefer existing Botble/Laravel patterns.
- Keep edits scoped.
- Use `apply_patch` for manual file edits.
- Do not delete old blog/comment data unless the user explicitly asks.

## Required Checks After Admin/Plugin Work

- `php artisan cms:plugin:list`
- `php artisan route:list` for affected routes
- `php artisan optimize:clear` when providers/routes/views change
- `php artisan package:discover` when plugin/module discovery changes
- HTTP check for `/admin/login`
- For admin session/CSRF work, verify an invalid AJAX POST returns HTTP `419` or `401` with `additional.login_url`, not HTTP `200`.
- HTTP check for important frontend pages

## Required Checks After Frontend Work

- Check desktop layout with browser/headless screenshot when visual changes are significant.
- Check mobile/responsive rules when the changed block can wrap or overlap.
- Confirm public assets are synced to `public/themes/zelio/...` when editing theme source assets.

## Current Admin Cleanup State

Expected inactive plugins:

- `blog`
- `fob-comment`

Expected active custom plugin:

- `courses`

Expected missing routes:

- `/admin/blog/...`
- `/admin/comments`
- `/fob-comment/...`

Expected course routes:

- `/admin/courses/categories`
- `/admin/courses/courses`
- `/admin/courses/chapters`
- `/admin/courses/lessons`
- `/admin/courses/files`
- `/admin/courses/enrollments`
- `/admin/courses/courses/{course}/curriculum/chapters`
- `/admin/courses/courses/{course}/curriculum/lessons`
- `/courses`

Expected student school routes:

- `/school/login`
- `/school/register`
- `/school/cabinet`

Expected course purchase routes:

- `POST /courses/{course}/purchase`
- `/admin/courses/purchases`
- `POST /admin/courses/purchases/{purchase}/confirm`
- `POST /courses/monopay/webhook`

Expected Monopay settings:

- `.env` has `MONOPAY_TOKEN`, `MONOPAY_WEBHOOK_URL`, and `MONOPAY_REDIRECT_URL`.
- Monopay webhook must return `403` for missing/invalid `X-Sign`, not `419`.

## Admin Locale Direction

- Admin UI is Russian-first for internal work.
- English is available from the top admin switcher.
- Public site/course content should continue to be filled in Ukrainian unless the user explicitly changes that direction.
- Expected admin locale switch route: `/admin/admin-locale`.

## Admin Session Direction

- Expired admin sessions and CSRF failures must send the user back to `/admin/login`.
- AJAX requests must receive a real HTTP `419` for CSRF/session expiry or `401` for unauthenticated access.
- The response should include `additional.login_url` so frontend code can redirect cleanly.
- Do not leave admin requests hanging with only a red `CSRF token mismatch` toast.
