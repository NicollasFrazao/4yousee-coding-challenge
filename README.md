# Video Upload Processor

A Laravel 12 application that receives a video upload, extracts its metadata with FFmpeg, stores the file in S3-compatible storage and publishes a message on Redis.

## Context

Solution to the hiring challenge of 4YouSee (2025). The statement given by the company is kept in [`docs/challenge.md`](docs/challenge.md) (in Portuguese).

## What it does

- `POST /api/videos` (also `POST /videos` with a CSRF token) accepts a `multipart/form-data` field named `video`.
- Validation (in `FFMpegService`): file required, MIME type `video/mp4`, `video/quicktime` or `video/x-msvideo`, maximum 100 MB.
- Metadata extracted with FFprobe (`php-ffmpeg`): original file name, resolution (`WIDTHxHEIGHT`) and duration (`HH:MM:SS`).
- A `videos` row is created (`uuid`, `name`, `resolution`, `duration`, `url`) inside a database transaction.
- The file is uploaded with the AWS SDK to `videos/{uuid}/{original-name}_{timestamp}.{ext}` (MinIO in local development), and the public URL is saved in `url`.
- When `url` is set, `VideoObserver` dispatches `PublishMessageJob` to the queue, which publishes the video record as JSON on the Redis channel `videos.store`.
- `php artisan redis:subscribe {channel}` prints the messages received on a channel.
- A minimal upload page (`GET /videos/create`, also the target of `/`) posts the form via AJAX and shows the response message.
- AJAX requests get a JSON response `{ success, status, message }`; regular form posts redirect back with the same data in the session.
- Storage and metadata extraction sit behind interfaces (`VideoManagerServiceInterface`, `VideoStorageServiceInterface`) bound in `AppServiceProvider`.

## Tech stack

- PHP `^8.2` (the Docker image runs PHP 8.4 on Ubuntu 24.04 with `ffmpeg` installed)
- Laravel Framework 12 (locked at v12.17.0), Laravel Sanctum 4 (installed, not used by the routes)
- `aws/aws-sdk-php` 3.344, `php-ffmpeg/php-ffmpeg` 1.3, `predis/predis` 2 (`.env.example` uses the `phpredis` client)
- MySQL 8.0, Redis, MinIO (via Laravel Sail 1.43 and `docker-compose.yml`)
- PHPUnit 11
- jQuery (served from `public/js`) on the upload page

## Running locally

### Prerequisites

- **Windows:** install WSL2 with Ubuntu 20.04 ([guide](https://learn.microsoft.com/windows/wsl/install)) and [Docker Desktop](https://docs.docker.com/desktop/setup/install/windows-install/), then run everything below from the Ubuntu terminal.
- **Linux or WSL2:** you need PHP 8.2+ and Composer on the host (to install `vendor/`, which the compose file uses), Docker with Compose, and `make`. On Ubuntu:

```bash
# PHP and the extensions the project uses
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php php-xml php-curl php-gd php-sqlite3 php-mbstring php-pgsql php-mysql php-cli unzip make

# Composer (verified installer)
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=$(curl -sS https://composer.github.io/installer.sig)
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('/tmp/composer-setup.php'); } echo PHP_EOL;"
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

Docker Engine with the Compose plugin: see the [official instructions for Ubuntu](https://docs.docker.com/engine/install/ubuntu/).

### Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
./setup.sh          # chown of the project files to $USER:www-data (uses sudo)
make up             # ./vendor/bin/sail up
```

In another terminal:

```bash
make sh             # shell inside the "application" container
php artisan migrate
```

Before the first upload, create a bucket named `4yousee` in the MinIO console (http://localhost:8900, user `sail`, password from `.env`); nothing in the repository creates it.

The project can be used through the web page (http://localhost) or the API (http://localhost/api/videos):

```bash
curl -H "X-Requested-With: XMLHttpRequest" -F "video=@public/examples/example.mp4" http://localhost/api/videos
```

To see the queue message, run each command in its own `make sh` session:

```bash
php artisan queue:work
php artisan redis:subscribe videos.store
```

## Tests

Inside the container (`make sh`):

```bash
php artisan test
```

The feature tests (`tests/Feature`) cover upload validation (missing file, non-video file, file over 100 MB), name/duration/resolution extraction, the S3 upload and the `POST /api/videos` route. They use SQLite in memory and the sync queue, but call the real FFprobe binary and the MinIO endpoint, so they need the Sail stack running and the bucket created. `public/examples/example.mp4` is the fixture.

## Project structure

```
app/Http/Controllers/VideoController.php    upload form and store action
app/Services/FFMpegService.php              validation and metadata extraction
app/Services/S3Service.php                  upload to S3/MinIO
app/Services/Contracts/                     service interfaces
app/Observers/VideoObserver.php             dispatches the job when the URL is saved
app/Jobs/PublishMessageJob.php              Redis publish on "videos.store"
app/Console/Commands/RedisSubscribe.php     redis:subscribe command
docker/                                     Sail runtime (Dockerfile with ffmpeg, php.ini with 200M upload limit)
Makefile                                    up/down/start/stop/sh shortcuts
```

## Notes

- `uuid` is filled with PHP `uniqid()`, not an RFC 4122 UUID.
- Validation lives in the service and raises generic exceptions; failures return HTTP 406 with the exception message, not Laravel's 422 validation format.
- The size limit is computed as `bytes / 1024 / 1000`, a mix of binary and decimal units.
- Duration is converted with `Carbon::createFromFormat('s', ...)`, which appears to reject videos of 100 seconds or longer (PHP's `s` format reads two digits).
- Objects are uploaded with `ACL: public-read`.
- `S3Service` reads `env()` directly instead of `config()`, which stops working once the configuration is cached.
- The API route has no authentication even though Sanctum is installed.
- Tests depend on external services (MinIO, FFprobe) rather than fakes.
