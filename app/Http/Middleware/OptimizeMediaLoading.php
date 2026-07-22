<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class OptimizeMediaLoading
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $response = $next($request);

        if (! $this->canOptimize($request, $response)) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return $response;
        }

        $content = $this->optimizeImages($content);
        $content = $this->optimizeIframes($content);
        $content = $this->optimizeVideos($content);

        $response->setContent($content);
        $response->headers->set('X-Poligonium-Media-Loading', 'optimized');

        return $response;
    }

    private function canOptimize(Request $request, SymfonyResponse $response): bool
    {
        if (! (bool) env('POLIGONIUM_OPTIMIZE_MEDIA_LOADING', true)) {
            return false;
        }

        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->expectsJson()) {
            return false;
        }

        if (! $response instanceof Response || $response->getStatusCode() !== 200) {
            return false;
        }

        if ($request->is('admin*', 'api*', 'ajax*', 'storage*', 'themes*', 'vendor*')) {
            return false;
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type'));

        return $contentType === '' || str_contains($contentType, 'text/html');
    }

    private function optimizeImages(string $html): string
    {
        $imageIndex = 0;
        $eagerImageCount = max(1, (int) env('POLIGONIUM_EAGER_IMAGE_COUNT', 2));

        return preg_replace_callback('/<img\b([^>]*)>/i', function (array $matches) use (&$imageIndex, $eagerImageCount): string {
            $imageIndex++;
            $attributes = $matches[1];

            if ($this->isDataImage($attributes)) {
                return $matches[0];
            }

            $isImportant = $imageIndex <= $eagerImageCount || preg_match('/\b(hero|logo|brand|favicon|avatar)\b/i', $attributes);

            if ($isImportant) {
                $attributes = $this->setAttribute($attributes, 'loading', 'eager');
                $attributes = $this->setAttribute($attributes, 'fetchpriority', 'high');
            } else {
                $attributes = $this->addAttributeIfMissing($attributes, 'loading', 'lazy');
                $attributes = $this->addAttributeIfMissing($attributes, 'fetchpriority', 'low');
            }

            $attributes = $this->addAttributeIfMissing($attributes, 'decoding', 'async');

            return '<img' . $attributes . '>';
        }, $html) ?: $html;
    }

    private function optimizeIframes(string $html): string
    {
        return preg_replace_callback('/<iframe\b([^>]*)>/i', function (array $matches): string {
            $attributes = $this->addAttributeIfMissing($matches[1], 'loading', 'lazy');

            return '<iframe' . $attributes . '>';
        }, $html) ?: $html;
    }

    private function optimizeVideos(string $html): string
    {
        return preg_replace_callback('/<video\b([^>]*)>/i', function (array $matches): string {
            $attributes = $this->addAttributeIfMissing($matches[1], 'preload', 'metadata');
            $attributes = $this->addAttributeIfMissing($attributes, 'playsinline', null);

            return '<video' . $attributes . '>';
        }, $html) ?: $html;
    }

    private function addAttributeIfMissing(string $attributes, string $name, ?string $value): string
    {
        if (preg_match('/\s' . preg_quote($name, '/') . '(\s|=|$)/i', $attributes)) {
            return $attributes;
        }

        if ($value === null) {
            return rtrim($attributes) . ' ' . $name;
        }

        return rtrim($attributes) . ' ' . $name . '="' . $value . '"';
    }

    private function setAttribute(string $attributes, string $name, string $value): string
    {
        if (preg_match('/\s' . preg_quote($name, '/') . '=("[^"]*"|\'[^\']*\'|[^\s>]*)/i', $attributes)) {
            return preg_replace(
                '/\s' . preg_quote($name, '/') . '=("[^"]*"|\'[^\']*\'|[^\s>]*)/i',
                ' ' . $name . '="' . $value . '"',
                $attributes,
                1
            ) ?: $attributes;
        }

        return rtrim($attributes) . ' ' . $name . '="' . $value . '"';
    }

    private function isDataImage(string $attributes): bool
    {
        return preg_match('/\ssrc=["\']data:image\//i', $attributes) === 1;
    }
}
