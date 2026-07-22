#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

echo "== PHP =="
php -v | head -n 1

echo
echo "== Laravel =="
php artisan about | sed -n '/Environment/,$p' | head -n 35

echo
echo "== Public storage =="
if [ -L public/storage ]; then
    ls -la public/storage
else
    echo "public/storage is not a symlink"
fi

echo
echo "== Media files on disk =="
for file in "storage/app/public/main/i-am-4.png" "storage/app/public/main/fav.png" "public/storage/main/i-am-4.png" "public/storage/main/fav.png"; do
    if [ -e "$file" ]; then
        ls -la "$file"
    else
        echo "MISSING $file"
    fi
done

for path in "storage/main/i-am-4.png" "storage/main/fav.png" "robots.txt" "sitemap.xml"; do
    echo
    echo "== https://poligonium.com/$path =="
    curl -L -o /dev/null -s -w "HTTP:%{http_code} TOTAL:%{time_total} SIZE:%{size_download}\n" "https://poligonium.com/$path"
done

echo
echo "== Page timings =="
for pass in 1 2; do
    echo "-- pass $pass --"
    for path in "" "vfx-showreel" "courses" "projects/crm-system" "admin/login"; do
        curl -L -o /dev/null -s -w "/$path HTTP:%{http_code} TOTAL:%{time_total} SIZE:%{size_download}\n" "https://poligonium.com/$path"
    done
done

echo
echo "== Optimization headers =="
for path in "" "vfx-showreel" "courses"; do
    echo "-- /$path --"
    curl -L -s -D - -o /dev/null "https://poligonium.com/$path" | grep -i "x-poligonium\\|cache-control" || true
done

echo
echo "== Static page cache files =="
for file in "public/page-cache/__root/index.html" "public/page-cache/vfx-showreel/index.html" "public/page-cache/courses/index.html"; do
    if [ -e "$file" ]; then
        ls -la "$file"
    else
        echo "MISSING $file"
    fi
done

echo
echo "== Recent Laravel errors =="
latest_log="$(ls -1t storage/logs/laravel-*.log 2>/dev/null | head -n 1 || true)"
if [ -n "$latest_log" ]; then
    tail -n 80 "$latest_log"
else
    echo "No daily Laravel log found."
fi
