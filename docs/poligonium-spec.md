# Poligonium Working Specification

This file is the implementation checklist for agreed Poligonium behavior. Before finishing related work, verify the relevant items here.

## Workflow

- When the user describes a change, first analyze the existing implementation and identify the real data path: admin form, save request, database/metadata, frontend rendering.
- If a requirement is ambiguous, ask before implementing.
- After implementing, verify both backend state and frontend/admin rendering whenever the change touches both.
- Do not report a feature as done only because the field exists in PHP; confirm it is visible in the admin UI data structure and receives existing values.
- Update this file when a new durable requirement is agreed.

## Portfolio Admin

- Portfolio edit form must show current saved data, not empty placeholders.
- `images[]` gallery must display already selected project images.
- Presentation gallery uses 3 vertical images, recommended 1080 x 1920 px.
- If more than 3 gallery images are uploaded, frontend Presentation Frame shows only the first 3.
- Video is a separate admin field: `project_video`.
- Project video format is vertical 1080 x 1920 px, MP4/WebM.
- Video appears as the fourth Presentation Frame item after the 3 vertical images.
- Modal project video must start with low playback volume (`0.12`) so pressing Play does not blast sound.

## Portfolio Frontend

- Project detail header must stay compact and must not block the global site header menu.
- Presentation Frame shows cards directly, not a single large inline slider.
- Image cards use vertical 9:16 framing.
- Video card uses vertical 9:16 framing, matching the three image cards.
- Clicking a Presentation Frame card opens an in-page modal viewer.
- Closed modal must not intercept clicks; it must be `display: none` or otherwise physically non-interactive.
