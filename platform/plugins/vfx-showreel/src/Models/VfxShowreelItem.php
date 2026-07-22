<?php

namespace Botble\VfxShowreel\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class VfxShowreelItem extends BaseModel
{
    protected $table = 'plg_vfx_showreel_items';

    protected $fillable = [
        'name',
        'type',
        'description',
        'poster',
        'preview_video',
        'tools',
        'year',
        'url',
        'is_featured',
        'order',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'type' => SafeContent::class,
        'description' => SafeContent::class,
        'tools' => SafeContent::class,
        'is_featured' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];
}
