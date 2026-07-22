<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CachePublicPages
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (! $this->canUseCache($request)) {
            return $next($request);
        }

        $cachePath = $this->cachePath($request);

        if ($this->isFresh($cachePath)) {
            return response(File::get($cachePath), 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
                'X-Poligonium-Page-Cache' => 'HIT',
            ]);
        }

        $response = $next($request);

        if ($this->canStore($request, $response)) {
            File::ensureDirectoryExists(dirname($cachePath));
            File::put($cachePath, $response->getContent());
            $response->headers->set('X-Poligonium-Page-Cache', 'MISS');
        }

        return $response;
    }

    private function canUseCache(Request $request): bool
    {
        if (! (bool) env('POLIGONIUM_PUBLIC_PAGE_CACHE', true)) {
            return false;
        }

        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->expectsJson()) {
            return false;
        }

        if ($request->query->has('preview') || $request->query->has('token')) {
            return false;
        }

        if ($this->isAuthenticated()) {
            return false;
        }

        return ! $request->is(
            'admin*',
            'api*',
            'ajax*',
            'install*',
            'school*',
            'login*',
            'logout*',
            'register*',
            'storage*',
            'themes*',
            'vendor*',
            'sanctum*'
        );
    }

    private function canStore(Request $request, SymfonyResponse $response): bool
    {
        if (! $response instanceof Response || $response->getStatusCode() !== 200) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type');

        if ($contentType && ! str_contains(strtolower($contentType), 'text/html')) {
            return false;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return false;
        }

        foreach (['<form', 'name="_token"', "name='_token'", 'csrf-token'] as $needle) {
            if (stripos($content, $needle) !== false && ! $this->canStoreFormPage($request, $content)) {
                return false;
            }
        }

        foreach (['data-no-page-cache'] as $needle) {
            if (stripos($content, $needle) !== false) {
                return false;
            }
        }

        return $this->canUseCache($request);
    }

    private function cachePath(Request $request): string
    {
        $locale = app()->getLocale();
        $key = sha1($request->getSchemeAndHttpHost() . '|' . $request->fullUrl() . '|' . $locale);

        return storage_path("framework/page-cache/$key.html");
    }

    private function isFresh(string $path): bool
    {
        if (! File::isFile($path)) {
            return false;
        }

        $ttl = max(60, (int) env('POLIGONIUM_PUBLIC_PAGE_CACHE_TTL', 600));

        return File::lastModified($path) >= (time() - $ttl);
    }

    private function isAuthenticated(): bool
    {
        return rescue(fn () => Auth::check(), false, report: false);
    }

    private function canStoreFormPage(Request $request, string $content): bool
    {
        $path = trim($request->path(), '/');

        if (! in_array($path, ['', 'uk', 'en'], true)) {
            return false;
        }

        return stripos($content, 'contact-form') !== false;
    }
}
