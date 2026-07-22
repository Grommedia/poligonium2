# Poligonium Courses / LMS Specification

This document is the working self-check source for the Poligonium course system.
Before adding or changing LMS/admin functionality, read this file and update it when decisions change.

## Current Product Direction

Poligonium is not a blog site.
The site combines:

- Portfolio
- 3D/VFX/VR services
- Pricing/contact funnels
- Online school with paid course access

The course system must be built as a separate module, not as a repurposed blog.

Reference direction: Voxyde-style course/catalog experience with Workshops, Pro Courses, Beginner Courses, free lessons, course cards, duration, pricing, and access after purchase.

## Removed / Disabled Legacy Features

The default Botble blog and standard comment plugins are not part of the product.

Disabled plugins:

- `blog`
- `fob-comment`

Expected result:

- No admin blog menu
- No posts/categories/tags workflow
- No generic public comment system
- `/admin/blog/...` returns 404
- `/admin/comments` returns 404

Do not re-enable these plugins unless the user explicitly asks for it.

## Main Frontend Navigation Goal

Current public navigation should evolve toward:

- `Головна`
- `Послуги`
- `Портфоліо`
- `Курси` or `Навчальні курси`
- `Ціни`
- `Контакти`

Avoid bringing back `Блог`.

## Course Module Scope

The admin panel needs a new course area.

Required admin sections:

- Courses
- Course categories
- Course subcategories or learning paths
- Chapters / modules
- Lessons
- Lesson files / assets
- Purchases / subscriptions / access grants
- Students / enrolled users

Course creation should allow:

- Title
- Slug
- Short description
- Full description
- Cover image / preview media
- Intro video or teaser
- Difficulty level
- Category
- Subcategory / path
- Price
- Currency, preferably UAH by default
- Course duration
- Published / draft / hidden status
- Free preview flag
- Access rules
- Chapter ordering
- Lesson ordering
- Attached downloadable files

Chapter creation should allow:

- Chapter title
- Description
- Sort order
- Published / draft status
- Optional free-preview flag

Lesson creation should allow:

- Lesson title
- Description
- Video source
- Duration
- Sort order
- Free-preview flag
- Required subscription/course access flag
- Attached project files
- Published / draft status

## Access Model

Users can register and buy access.

Access options to support:

- Full course purchase
- Subscription access
- Manual admin grant
- Free preview lessons

Admin must be able to choose which lessons/chapters are open by default and which require payment.

When a user has no access:

- They can see course landing page.
- They can see free preview lessons.
- Paid lessons must be locked.

When a user has access:

- They can view the full course.
- They can navigate lesson by lesson.
- They can download only files that are allowed for that course/lesson.

## Video Protection Direction

Absolute video protection is impossible on the web because users can record the screen.
The goal is to avoid direct, easy downloads.

Preferred direction:

- Do not expose direct video file URLs in obvious public markup.
- Use private storage or signed/temporary routes where possible.
- Use an HTML5 player with controlled access.
- Prefer HLS/DASH-style segmented video later if feasible.
- Add expiring URLs for protected media.
- Block unauthenticated and unauthorized requests at controller level.

Minimum acceptable first version:

- Course videos are not listed in public directories.
- Video route checks user access before streaming or redirecting.
- Direct lesson URL without access returns login or locked state.

## Frontend Course Experience

Course catalog should feel like Poligonium, not a generic LMS.

Expected pages:

- Courses index/catalog
- Category/path page
- Course landing page
- Lesson player page
- Student dashboard / my courses

Catalog card should show:

- Course image/preview
- Category
- Level
- Duration
- Lesson count
- Price/free label
- Access status if logged in

Course landing page should show:

- Strong visual hero
- What student will learn
- Tools/software used
- Chapter/lesson curriculum
- Free preview lessons
- Files/assets included
- Price and buy/access CTA

Lesson page should show:

- Video player
- Current lesson title/description
- Next/previous lesson
- Chapter/lesson sidebar
- Attached files
- Access/locked states

## Admin UX Requirements

The admin area must be practical for frequent course editing.

Important:

- Do not make the user edit raw JSON for course structure.
- Chapters and lessons need clear ordering.
- Files must be attached per lesson or per course.
- Admin should easily see whether a lesson is free, paid, draft, or published.
- Keep naming in Ukrainian for user-facing/admin labels where practical.
- The admin menu must expose courses as one working area only: create, edit, delete courses.
- Course categories/directions, chapters, lessons, files, and access records are technical/internal parts of a course and must not appear as separate daily-work menu sections.
- When an admin edits a chapter or lesson through a detailed editor, saving must return back toward the parent course workflow instead of a global chapters/lessons list.
- Course intro video and lesson videos must be selected through the media library, not typed as manual file paths.
- The course editor must support the normal workflow inside one screen: add chapter, add lesson, choose lesson video, then optionally open a detailed lesson editor for long content/embed/materials.
- Early access, student cabinet, ranks, purchases, and school gallery decisions are tracked in `docs/POLIGONIUM_STUDENT_CABINET_SPEC.md`.

## Technical Direction

Preferred implementation:

- Create a dedicated Botble plugin/module for courses.
- Do not modify the disabled blog plugin into a course system.
- Keep migrations reversible.
- Keep route names and table names clear.

Possible table names:

- `plg_course_categories`
- `plg_course_subcategories`
- `plg_courses`
- `plg_course_chapters`
- `plg_course_lessons`
- `plg_course_lesson_files`
- `plg_course_enrollments`
- `plg_course_purchases`

Access-related fields to consider:

- `user_id`
- `course_id`
- `starts_at`
- `ends_at`
- `source` (`purchase`, `subscription`, `manual`)
- `status`

## Self-Check Before Any LMS Change

Before coding:

- Read this file.
- Confirm blog/comment plugins remain disabled unless explicitly requested.
- Identify whether the change belongs in the new course module, theme frontend, user auth, payments, or media protection.
- Check existing Botble patterns before inventing new admin architecture.
- Decide whether the change affects database schema, admin forms, frontend pages, or access rules.

## Self-Check After Any LMS Change

After coding:

- Run relevant Laravel/Botble cache cleanup when routes/providers change.
- Check admin route exists for new admin pages.
- Check public pages still load.
- Check unauthorized user cannot access protected lessons/files.
- Check authorized user can access purchased/free content.
- Check mobile layout for course catalog and lesson page.
- Update this document if any product decision changes.

## Immediate Next Steps

1. Confirm module name and admin menu label.
2. Scaffold dedicated courses plugin/module.
3. Add migrations and models for categories, courses, chapters, lessons, files, enrollments.
4. Add admin menu and CRUD forms.
5. Add public `/courses` page and navigation link.
6. Add course detail and locked/free lesson states.
7. Add protected media delivery.
8. Add purchase/subscription integration after core course browsing works.

## Implemented MVP State

Implemented on 2026-06-13:

- Created dedicated Botble plugin: `platform/plugins/courses`.
- Activated plugin alias: `courses`.
- Added admin menu: `Навчальні курси`.
- Added admin CRUD routes for:
  - `admin/courses/categories`
  - `admin/courses/courses`
  - `admin/courses/chapters`
  - `admin/courses/lessons`
  - `admin/courses/files`
  - `admin/courses/enrollments`
- Added database tables:
  - `plg_course_categories`
  - `plg_courses`
  - `plg_course_chapters`
  - `plg_course_lessons`
  - `plg_course_lesson_files`
  - `plg_course_enrollments`
  - `plg_course_purchases`
- Added public routes:
  - `/courses`
  - `/courses/{course}`
  - `/courses/{course}/lessons/{lesson}`
  - `/courses/{course}/lessons/{lesson}/video`
- Added public theme views:
  - `platform/themes/zelio/views/courses/index.blade.php`
  - `platform/themes/zelio/views/courses/show.blade.php`
  - `platform/themes/zelio/views/courses/lesson.blade.php`
- Added main menu link:
  - English: `Courses`
  - Ukrainian: `Курси`
- Added inline admin curriculum builder inside the course edit form:
  - Quick add chapter from `admin/courses/courses/edit/{course}`.
  - Quick add lesson from `admin/courses/courses/edit/{course}`.
  - Existing chapters and lessons are visible in the course form.
  - Full chapter/lesson editors remain available for detailed video, content, and file edits.
  - Course `lesson_count` and `duration_minutes` are recalculated after quick lesson creation.
- Simplified the admin menu so `Навчальні курси` / `Учебные курсы` is the single visible entry and opens the course list directly.
- Hid separate admin menu entries for categories/directions, chapters, lessons, files, and enrollments. Those routes remain technical/internal for course editing flows.
- Added course taxonomy setup:
  - Course level is a fixed select: beginner, intermediate, advanced, professional.
  - Course directions are stored as course categories: 3D modeling, character sculpting, rigging, animation, VFX, VR / Unreal Engine 5, product commercials, compositing.
  - Course software is selectable per course: Blender, Houdini, Houdini APEX, Houdini Copernicus, ZBrush, Unreal Engine 5, Substance tools, Photoshop, Illustrator, After Effects, Nuke, Auto-Rig Pro, Rigify, AI tools.
  - Course skills/topics are selectable per course: modeling, sculpting, retopology, texturing, rigging, facial rigging, animation, VFX, rendering, compositing, VR, product ads.
  - Removed test category names containing `павук`.

Verified:

- `blog` remains inactive.
- `fob-comment` remains inactive.
- Public `/courses` returns 200.
- Closed lesson page renders a locked state for unauthorized users.
- Protected video route returns 403 for unauthorized users.
- Inline curriculum smoke test created one temporary chapter and one temporary lesson, recalculated stats, and cleaned up test data.
- Course taxonomy smoke test created one temporary course with level/software/skills, verified labels and cleaned it up.

Next implementation work:

- Add real frontend design pass for catalog/detail/lesson pages.
- Add student registration/account dashboard.
- Add payment/subscription provider.
- Add protected file download routes.
- Improve video delivery with signed URLs or segmented HLS/DASH.
