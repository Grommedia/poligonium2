# Poligonium Storage Media Package

These files are split parts of `public/storage/storage.zip`.

They are stored in Git only as a deployment bridge for shared hosting where
manual media upload is unreliable.

## Restore On Hosting

Run from the project root:

```bash
cd /home/poligoni/poligonium
cat deploy/storage-parts/storage.zip.part-* > storage.zip
mkdir -p storage/app/public
unzip -o storage.zip -d storage/app/public
php artisan storage:link
php artisan optimize:clear
```

Then check:

```bash
ls -la storage/app/public/main
ls -la storage/app/public/vfx
```

The public URL `/storage/...` should point to `storage/app/public/...`.
