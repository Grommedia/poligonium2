<?php

namespace Botble\Academy\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademyCategory extends BaseModel
{
    protected $table = 'plg_academy_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'slug' => SafeContent::class,
        'description' => SafeContent::class,
        'icon' => SafeContent::class,
        'color' => SafeContent::class,
        'status' => BaseStatusEnum::class,
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(AcademyArticle::class, 'category_id');
    }
}
