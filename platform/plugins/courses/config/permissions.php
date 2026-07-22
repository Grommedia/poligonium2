<?php

return [
    [
        'name' => 'Training courses',
        'flag' => 'courses.index',
    ],
    [
        'name' => 'Manage courses',
        'flag' => 'courses.courses.index',
        'parent_flag' => 'courses.index',
    ],
    [
        'name' => 'Preview courses',
        'flag' => 'courses.courses.preview',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'Autosave courses',
        'flag' => 'courses.courses.autosave',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'View course translations',
        'flag' => 'courses.courses.translations',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'Save course translations',
        'flag' => 'courses.courses.translations.save',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'Publish courses',
        'flag' => 'courses.courses.publish',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'Schedule course publication',
        'flag' => 'courses.courses.schedule',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'Hide published courses',
        'flag' => 'courses.courses.hide',
        'parent_flag' => 'courses.courses.index',
    ],
    [
        'name' => 'Manage course purchases',
        'flag' => 'courses.purchases.index',
        'parent_flag' => 'courses.index',
    ],
    [
        'name' => 'Create course purchases',
        'flag' => 'courses.purchases.create',
        'parent_flag' => 'courses.purchases.index',
    ],
    [
        'name' => 'Edit course purchases',
        'flag' => 'courses.purchases.edit',
        'parent_flag' => 'courses.purchases.index',
    ],
    [
        'name' => 'Confirm course purchases',
        'flag' => 'courses.purchases.confirm',
        'parent_flag' => 'courses.purchases.index',
    ],
    [
        'name' => 'Delete course purchases',
        'flag' => 'courses.purchases.destroy',
        'parent_flag' => 'courses.purchases.index',
    ],
];
