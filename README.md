📋 Progress Perbaikan Setelah Pertemuan 02-08-2026

📱 Scan Website (Mobile) ⬜ Belum
🖼️ Upload Gambar ✅ Selesai
📍 GPS Location Scan ⬜ Belum
🔍 Detail QR Code ✅ Selesai
📸 Foto Saat Login ⬜ Belum
📍 GPS / Fake GPS Detection ⬜ Belum
📖 SOP / Kitab Sutasoma ⬜ Belum

# Build and run docker

## First time build and run docker

docker compose -f docker-compose.dev.yml up -d --build

## When update code

git pull
docker compose -f docker-compose.dev.yml restart

## Run artisan command

docker exec -it ddm-web-app php artisan migrate
docker exec -it ddm-web-app php artisan tinker
docker exec -it ddm-web-app php artisan queue:restart
