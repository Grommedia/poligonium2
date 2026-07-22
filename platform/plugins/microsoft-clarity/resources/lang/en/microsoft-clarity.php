<?php

return [
    'name' => 'Microsoft Clarity',
    'menu_name' => 'Microsoft Clarity',
    'settings' => [
        'title' => 'Microsoft Clarity',
        'description' => 'Behavior analytics, heatmaps and session recordings integration for Microsoft Clarity.',
        'enabled' => 'Enable Clarity on the site',
        'enabled_help' => 'When enabled and Project ID is set, the Clarity script is added to public pages.',
        'project_id' => 'Project ID',
        'project_id_help' => 'Project ID from Microsoft Clarity. You can paste it manually or connect through the Clarity iframe below.',
        'tracking_mode' => 'Tracking mode',
        'manual_mode' => 'Project ID only',
        'script_mode' => 'Full HTML code',
        'tracking_code' => 'Full Clarity code',
        'tracking_code_help' => 'Optional. Use this if you want to paste the full Clarity script manually.',
        'exclude_admin' => 'Do not track admin panel',
        'exclude_admin_help' => 'Recommended to keep enabled.',
    ],
    'dashboard' => [
        'title' => 'Website behavior analytics',
        'description' => 'Clarity shows session recordings, heatmaps, popular pages and places where users get stuck.',
        'project_connected' => 'Project connected',
        'project_missing' => 'Project ID is not set yet',
        'project_hint' => 'Connect an existing project or create a new one in Microsoft Clarity. Once saved, the script is injected automatically.',
        'open_settings' => 'Settings',
        'open_clarity' => 'Open Clarity',
        'manual_project' => 'Manual connection',
        'manual_project_help' => 'If the iframe does not pass the ID automatically, paste Project ID here.',
        'save_project' => 'Save Project ID',
        'embed_title' => 'Microsoft Clarity Dashboard',
    ],
];
