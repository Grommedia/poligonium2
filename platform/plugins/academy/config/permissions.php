<?php

return [
    [
        'name' => 'Academy',
        'flag' => 'academy.index',
    ],
    [
        'name' => 'Categories',
        'flag' => 'academy.categories.index',
        'parent_flag' => 'academy.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'academy.categories.create',
        'parent_flag' => 'academy.categories.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'academy.categories.edit',
        'parent_flag' => 'academy.categories.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'academy.categories.destroy',
        'parent_flag' => 'academy.categories.index',
    ],
    [
        'name' => 'Articles',
        'flag' => 'academy.articles.index',
        'parent_flag' => 'academy.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'academy.articles.create',
        'parent_flag' => 'academy.articles.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'academy.articles.edit',
        'parent_flag' => 'academy.articles.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'academy.articles.destroy',
        'parent_flag' => 'academy.articles.index',
    ],
];
