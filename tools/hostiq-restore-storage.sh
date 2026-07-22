#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

parts_dir="deploy/storage-parts"
archive="storage/app/public/.poligonium-storage.zip"

if [ ! -d "$parts_dir" ]; then
    echo "Storage parts directory not found: $parts_dir"
    exit 1
fi

if ! ls "$parts_dir"/storage.zip.part-* >/dev/null 2>&1; then
    echo "Storage archive parts not found in $parts_dir"
    exit 1
fi

mkdir -p storage/app/public

echo "Rebuilding storage archive..."
cat "$parts_dir"/storage.zip.part-* > "$archive"

echo "Extracting media into storage/app/public..."
unzip -o "$archive" -d storage/app/public
rm -f "$archive"

php artisan storage:link --force

echo
echo "Checking required media..."
for file in "storage/app/public/main/i-am-4.png" "storage/app/public/main/fav.png"; do
    if [ -e "$file" ]; then
        ls -la "$file"
    else
        echo "MISSING $file"
        exit 1
    fi
done

echo
echo "Storage media restored."
