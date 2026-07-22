# Course Editor Workspace Spec

Source document audited: `G:\Концепция_редактора_курса.docx` on 2026-07-21.

This is the working checklist for the Polygonium course editor. The admin UI language is Ukrainian. Course content fields can be Ukrainian or English.

## Main Goal

The course editor must feel like a workspace, not a long technical form.

Primary workflow:

1. Card course
2. Course program
3. Price and access
4. Publication
5. Translations

The author works in one section at a time. The right side shows the current result as a live preview.

## Progress Model

Current checkpoint: **Ukrainian admin checkpoint, 2026-07-21**

Actual admin state now: **standard Botble course edit form is restored.**

The custom `admin-polish` workspace is intentionally disabled from `CourseForm` for now. Do not continue building on the colored 5-tab workspace until a new course-editor direction is approved.

Previous staged implementation status: **10 / 10 complete, then rolled back visually from the admin course form.**

Current allowed small correction after rollback: **software and skills use compact checkbox lists in the standard Botble form.**

Current language rule: **the admin interface is Ukrainian by default; do not reintroduce Russian labels into course editor UI.**

Stages are intentionally separated so each development update can say exactly where we are.

| Stage | Status | Scope |
| --- | --- | --- |
| 1 | Done | Import concept into project checklist and compare against current implementation. |
| 2 | Done | Replace long form with workspace shell: sticky topbar, back link, actions, 5 sections. |
| 3 | Done | Card course screen: title, short description, hidden slug edit, cover block, classification, live card preview. |
| 4 | Done | Software and skills selectors: searchable checkboxes, popular items, selected tags, expanded options list. |
| 5 | Done | Program builder baseline: course -> section -> lesson, inline section add, insert section after another section, section edit, lesson drawer, drag-and-drop reorder with undo. |
| 6 | Done | Lesson drawer full edit: edit existing lesson, text/files, access modes, status separation, video preview/replace/delete, quick actions, restore delete, bulk draft lessons. |
| 7 | Done | Price and access backend split: visibility, price type, sales start, preorder, release date, gradual access. |
| 8 | Done | Public course behavior: catalog card state, scheduled course modal, purchased-but-not-open countdown, reminders. |
| 9 | Done | Publication workflow: critical/warning checklist, scheduled publishing, draft vs published version, preview roles. |
| 10 | Done | Translations and autosave: translation completion, text-only localization, real autosave, retry on failure. |

## What Is Implemented Now

- [x] Existing database fields are preserved so course saving remains stable.
- [x] Admin form is visually rebuilt into a workspace with section navigation.
- [x] Sticky topbar exists:
  - back to all courses
  - course title
  - course language hint
  - save state text
  - preview/save/publish actions
- [x] Only one main editor section is visible at a time.
- [x] Live course card preview exists on the card screen.
- [x] A second live card preview exists on price/access screen.
- [x] Slug is generated automatically and manual edit is hidden behind a button.
- [x] Title counter is present.
- [x] Short description counter is present.
- [x] Short description and full course description are visually separated.
- [x] Cover field is enlarged and styled as a course cover zone.
- [x] Level is shown as radio choices.
- [x] Software is shown as searchable checkbox choices and selected tags.
- [x] Skills are shown as searchable checkbox choices and selected tags.
- [x] Manual technical fields are hidden from the main workflow:
  - lesson count
  - duration
  - early access sold
  - sort order
  - course-level free preview toggle
- [x] Early access fields appear only when early access is selected.
- [x] Curriculum builder uses the terms section and lesson in the UI.
- [x] Section creation is inline, not a permanent huge form.
- [x] Sections can be inserted after an existing section.
- [x] Existing sections can be edited inside the course workspace.
- [x] Lesson creation opens a right-side drawer.
- [x] Existing lesson opens the same drawer in edit mode.
- [x] Lesson drawer stores one access mode instead of conflicting free/purchase checkboxes.
- [x] Lesson drawer includes material type, text, video, embed, attached file add, status, fixed opening date and drip-days fields.
- [x] Lesson list shows clearer access/video states.
- [x] Drag-and-drop reorder still uses the existing reorder endpoint.
- [x] Drag-and-drop reorder shows an Undo action after saving.
- [x] Lesson quick menu is available in the course workspace.
- [x] Lesson deletion can be restored for a short undo window.
- [x] Bulk video paths can create draft lessons inside a selected section.
- [x] Price/access backend has separate fields for visibility, pricing type, sales mode and course access mode.
- [x] Price/access workspace hides irrelevant fields based on selected mode.
- [x] Legacy `sale_status` is synchronized from the new sales mode for public compatibility.
- [x] Publication screen has a readiness checklist.
- [x] Public catalog hides courses that are not visible in the catalog.
- [x] Public catalog scheduled cards remain clickable and show release timing.
- [x] Scheduled paid courses can be purchased before materials open.
- [x] Future access enrollments start at the course opening date.
- [x] Course page explains purchase now / access later behavior.
- [x] Lesson/video access checks course release, lesson fixed date and drip delay.
- [x] Student cabinet shows purchased-but-not-open courses with countdown.
- [x] Course opening reminder table, model and public route are available.
- [x] Publication workflow fields exist: state, scheduled publish date, published snapshot and unpublished changes flag.
- [x] Backend readiness check distinguishes critical errors from warnings.
- [x] Publishing is blocked server-side when critical fields are missing.
- [x] Scheduled publication is stored separately from course release/access dates.
- [x] Public catalog and course pages ignore scheduled publications until publish time.
- [x] Published course snapshots keep public course/card data stable while draft changes are saved.
- [x] Preview links exist for visitor, buyer and student roles through signed temporary URLs.
- [x] Duplicate Botble form action footer is hidden inside the course workspace when present.
- [x] Course editor has a real autosave endpoint for existing courses.
- [x] Autosave retries failed requests before asking for a manual save.
- [x] Autosave normalizes `software` and `skills` so string/JSON/list values do not break saving.
- [x] Course translations use separate Ukrainian and English text payloads.
- [x] Translation editing is limited to text fields:
  - course name
  - short description
  - full description
  - section names/descriptions
  - lesson names/descriptions/content
- [x] Translation editing does not duplicate price, video, dates, software, skills or lesson order.
- [x] Translation completion percentages are calculated on the backend.
- [x] Public course catalog, course page and lesson page apply locale translations.
- [x] JS syntax checked with `node --check`.
- [x] Laravel Blade blocks and full `CourseForm` render in bootstrap test.

## Missing Compared To The Docx

These are not forgotten. They are not fully implemented yet.

### Stage 7 Gaps

- [x] Course visibility must be separate from publication status.
- [x] Paid/free course type must hide price when free.
- [x] Sales start date must be separate from course release date.
- [x] Preorder/early access should be controlled by the new sales mode, not only `sale_status`.
- [x] Early access sold counter must be read-only statistics only.
- [x] Add timezone selector, default `Europe/Kyiv`.
- [x] Add "show release date on card" setting.
- [x] Add gradual material opening setting.

### Stage 8 Gaps

- [x] Catalog card must reflect exact state: visible, preorder, release date, price.
- [x] Course page must explain that purchase is allowed but materials open later.
- [x] Purchased student view must show countdown until opening.
- [x] Lesson access block must show a modal when course or lesson is not open.
- [x] Add "remind me when course opens".

### Stage 9 Gaps

- [x] Publication checklist must distinguish blocking critical errors and warnings.
- [x] Add scheduled publication state.
- [x] Add draft changes for already published courses.
- [x] Public page should update only after "publish changes".
- [x] Add preview as visitor, buyer, student.
- [x] Remove duplicate save buttons if Botble renders both.

### Stage 10 Gaps

- [x] Real autosave endpoint.
- [x] Autosave retry on failure.
- [x] Translation completion percent:
  - Ukrainian filled
  - English percent
- [x] Switching course language edits text-only fields.
- [x] Do not duplicate price/video/dates/software/order between translations.

## UX Rules

- Blue: primary actions.
- Green: success states.
- Yellow: warnings.
- Red: errors and delete.
- Gray: neutral states.
- Do not show every property as a bright badge.
- Use the terms only:
  - Course
  - Section
  - Lesson

## Current Verification Checklist

- [x] Course editor Blade renders.
- [x] Workspace scripts pass syntax check.
- [x] Only one editor section is designed to be visible at a time.
- [x] Existing course values are kept in original Botble fields.
- [x] Software and skills still submit as arrays.
- [x] Live preview updates from form input/change events.
- [x] Early access fields are conditionally displayed.
- [x] Curriculum can add a section through existing endpoint.
- [x] Curriculum can add a lesson through existing endpoint.
- [x] Curriculum can update a section through workspace endpoint.
- [x] Curriculum can update a lesson through workspace endpoint.
- [x] Lesson workspace migration was applied locally.
- [x] Laravel route cache was cleared after adding curriculum endpoints.
- [x] Drag-and-drop sends reorder request through existing endpoint.
- [x] Browser-admin visual check while logged in.
- [x] Signed public course preview opens without frontend/server errors.
- [x] Public `/courses` opens without SQL/server errors.
- [x] Public `/en/courses` opens without SQL/server errors.
- [x] Course translation tables exist locally.
- [x] Translation save/load service verified inside a DB transaction.
- [x] Autosave normalizes string and JSON software/skills values inside a DB transaction.
- [x] Course form render includes the workspace JS and translation URLs.
- [x] Post-stage correction: course launch/timer controls moved to the real Botble right sidebar, next to publish/language/status controls.
- [x] Post-stage correction: right sidebar course controls are grouped into Cost, Launch/timer and Publication blocks.
- [x] Post-stage correction: price/access editor redesigned as a light dashboard with status banner, Sales, Launch/timer, Publication, card preview, summary and quick actions.
- [x] Post-stage correction: `software` and `skills` normalization now flattens nested arrays and JSON-array strings before validation.
- [x] Post-stage correction: admin editor JavaScript is loaded as a real Botble asset (`admin-polish.js`) with Blade data moved into a JSON config block.
- [x] Post-stage rollback: course workspace is mounted back inside the standard Botble edit layout; the old Botble right column is no longer hidden.
- [x] Full rollback: custom `admin-polish` course workspace is disabled from `CourseForm`; the admin course edit page uses the standard Botble form again.
- [x] Admin language correction: default admin locale is Ukrainian; the top admin locale switcher exposes Ukrainian only.
- [x] Manual save test in admin.
- [ ] Manual add-section test in admin.
- [ ] Manual add-lesson test in admin.
