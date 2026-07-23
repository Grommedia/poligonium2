<?php

namespace Botble\Academy\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AcademyArticleRequest extends Request
{
    public function rules(): array
    {
        $articleId = $this->route('article')?->getKey();

        return [
            'category_id' => ['nullable', Rule::exists('plg_academy_categories', 'id')],
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('plg_academy_articles', 'slug')->ignore($articleId)],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:191'],
            'difficulty' => ['nullable', 'string', 'max:80'],
            'software' => ['nullable', 'string', 'max:191'],
            'skills' => ['nullable', 'string', 'max:191'],
            'cta_label' => ['nullable', 'string', 'max:191'],
            'cta_url' => ['nullable', 'string', 'max:191'],
            'seo_title' => ['nullable', 'string', 'max:191'],
            'seo_description' => ['nullable', 'string'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:120'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
