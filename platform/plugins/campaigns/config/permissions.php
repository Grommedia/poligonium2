<?php

return [
    [
        'name' => 'Support Campaigns',
        'flag' => 'campaigns.index',
    ],
    [
        'name' => 'Campaigns',
        'flag' => 'campaigns.campaigns.index',
        'parent_flag' => 'campaigns.index',
    ],
    [
        'name' => 'Rewards',
        'flag' => 'campaigns.rewards.index',
        'parent_flag' => 'campaigns.index',
    ],
    [
        'name' => 'Contributions',
        'flag' => 'campaigns.contributions.index',
        'parent_flag' => 'campaigns.index',
    ],
    [
        'name' => 'Support requests',
        'flag' => 'campaigns.support-requests.index',
        'parent_flag' => 'campaigns.index',
    ],
    [
        'name' => 'Updates',
        'flag' => 'campaigns.updates.index',
        'parent_flag' => 'campaigns.index',
    ],
    [
        'name' => 'Team',
        'flag' => 'campaigns.team.index',
        'parent_flag' => 'campaigns.index',
    ],
    [
        'name' => 'FAQ',
        'flag' => 'campaigns.faqs.index',
        'parent_flag' => 'campaigns.index',
    ],
];
