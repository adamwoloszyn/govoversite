# Government Oversite Video Processing System

A Laravel-based application for automated video processing, transcription, and notification system for government meetings.

## Overview

This system automates the entire video processing pipeline from upload to publication:
1. **Video Upload** → AWS S3 storage
2. **Video Compression** → AWS MediaConvert
3. **Transcription** → Sonix API integration
4. **Content Processing** → Automated parsing and keyword extraction
5. **Publication** → Automated notifications and email distribution

## Project Structure

```
GovernmentOversite/
├── README.md                    # This file - main documentation
├── .gitignore                   # Git ignore rules
├── governmentoversite/          # Laravel application root
│   ├── app/
│   │   ├── Console/Kernel.php   # Scheduled task definitions
│   │   ├── Http/Controllers/    # CRON controllers for video pipeline
│   │   ├── Jobs/               # Queue jobs (AWSVideoUpload.php)
│   │   └── Models/             # Database models
│   ├── config/                 # Laravel configuration
│   ├── database/               # Migrations and seeders
│   └── storage/logs/           # Application logs
└── Tools/                      # Additional utilities
```

## System Architecture

### Background Workers (Supervisor)

The system runs two main background processes managed by Supervisor:

#### 1. Laravel Scheduled Worker (`laravel-worker:cron-worker`)
- **Status**: RUNNING (uptime: 573+ days)
- **Purpose**: Executes scheduled tasks every minute
- **Command**: `php artisan schedule:work`
- **Log**: `/var/www/laravel/storage/logs/worker.log`

#### 2. Laravel Queue Worker (`laravel-job-worker:long-job-worker`) 
- **Status**: RUNNING (uptime: 96+ days)
- **Purpose**: Processes background jobs from "adam" queue
- **Command**: `php artisan queue:work database --queue=adam`
- **Log**: `/var/www/laravel/storage/logs/job-worker.log`

### Video Processing Pipeline

The system processes videos through 13 distinct states, each handled by specific CRON controllers:

| State | Description | CRON Controller | Frequency |
|-------|-------------|----------------|-----------|
| 1 | Admin Uploaded Video | `AWSCreateSubDirectoryCRONController` | Every minute |
| 2 | Created SubDirectory in AWS | `AWSVideoUploadCRONController` (via queue) | Background job |
| 3 | Uploading to AWS | `AWSVideoUpload` job | Background job |
| 4 | Uploaded to AWS | `AWSVideoCompressionCRONController` | Every minute |
| 5 | Compressing Video in AWS | `AWSVideoCompressionStatusCheckCRONController` | Every minute |
| 6 | Compressed Video in AWS | `SonixUploadCRONController` | Every minute |
| 7 | Uploaded to Sonix | `SonixTranscriptionStatusCheckCRONController` | Every minute |
| 8 | Sonix Transcription Done | `SonixTranscriptionDownLoadCRONController` | Every minute |
| 9 | Downloaded Sonix Transcription | `AWSTranscriptionUploadCRONController` | Every minute |
| 10 | Uploaded transcript to AWS | `TranscriptionParseCRONController` | Every minute |
| 11 | Transcription Parsed | N/A - Admin publishes manually | Manual |
| 12 | Published | `VideoNotificationCRONController` & `EMailBodyPreparationCRONController` | Every minute |
| 13 | Emails Sent | `SendNotificationsCRONController` | Every minute |

## Development Setup

### Prerequisites
- PHP 8.x
- MySQL/MariaDB
- Composer
- Node.js & NPM
- AWS CLI configured
- Sonix API credentials

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd GovernmentOversite/governmentoversite
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=GOPrototype
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Database setup**
   ```bash
   # Run migrations
   php artisan migrate
   
   # Seed database (all in one command)
   php artisan db:seed --class=CreateUserGroupsSeeder && \
   php artisan db:seed --class=CreateTestUsersSeeder && \
   php artisan db:seed --class=CreateVideoCategoriesSeeder && \
   php artisan db:seed --class=CreateWordContractionsSeeder && \
   php artisan db:seed --class=VideoProcessingStatesSeeder && \
   php artisan db:seed --class=KeywordsSeeder && \
   php artisan db:seed --class=ConfigurationSeeder && \
   php artisan db:seed --class=AgendaItemTypeSeeder && \
   php artisan db:seed --class=CreateUserSubscriptionsSeeder && \
   php artisan db:seed --class=VideosSeeder && \
   php artisan db:seed --class=ChannelsSeeder
   ```

6. **Test users created**
   - **Guest**: User@domain.com / testpw
   - **Subscriber**: Subscriber@domain.com / testpw  
   - **Subscribed**: Subscribed@domain.com / testpw (paid subscription)
   - **Admin**: Admin@domain.com / testpw

7. **Start development server**
   ```bash
   php artisan serve
   ```
   Navigate to: http://localhost:8000

## Production Deployment

### Supervisor Configuration

The production server uses Supervisor to manage background workers:

#### Supervisor Commands
```bash
# Check status of all workers
sudo supervisorctl status

# View available programs
sudo supervisorctl avail

# Start/stop workers
sudo supervisorctl start laravel-worker:*
sudo supervisorctl start laravel-job-worker:*
sudo supervisorctl stop laravel-worker:*
sudo supervisorctl stop laravel-job-worker:*

# View live logs
sudo supervisorctl fg laravel-worker:cron-worker
sudo supervisorctl fg laravel-job-worker:long-job-worker

# Reload configuration
sudo supervisorctl reread
sudo supervisorctl update
```

#### Configuration Files
- **Cron Worker**: `/etc/supervisor/conf.d/laravel-cron-worker.conf`
- **Job Worker**: `/etc/supervisor/conf.d/laravel-job-worker.conf`

### Manual Testing Commands

```bash
# Test scheduled tasks (development)
php artisan schedule:work

# Process single queue job
php artisan queue:work --queue=adam --once

# Run unit tests
vendor/bin/phpunit
```

## Key Components

### Scheduled Tasks (`app/Console/Kernel.php`)
All CRON controllers run every minute with overlap prevention:
- `PlayGroundCRONController` - Development testing
- `AWSCreateSubDirectoryCRONController` - Creates S3 subdirectories
- `AWSVideoCompressionCRONController` - Initiates video compression
- `AWSVideoCompressionStatusCheckCRONController` - Monitors compression
- `SonixUploadCRONController` - Uploads to Sonix for transcription
- `SonixTranscriptionStatusCheckCRONController` - Monitors transcription
- `SonixTranscriptionDownLoadCRONController` - Downloads transcriptions
- `AWSTranscriptionUploadCRONController` - Uploads transcriptions to AWS
- `TranscriptionParseCRONController` - Parses transcription data
- `VideoNotificationCRONController` - Handles video notifications
- `EMailBodyPreparationCRONController` - Prepares email content
- `SendNotificationsCRONController` - Sends notifications

### Queue Jobs (`app/Jobs/`)
- `AWSVideoUpload.php` - Handles heavy video upload operations

### Controllers (`app/Http/Controllers/`)
- Individual CRON controllers for each processing stage
- `OldVideoImportController.php` - Imports legacy video data

## Server Information

### Production Server: 161.35.113.206
```bash
# SSH access
ssh root@161.35.113.206

# Current worker status (as of documentation)
laravel-job-worker:long-job-worker   RUNNING   pid 2130436, uptime 96 days, 12:11:13
laravel-worker:cron-worker           RUNNING   pid 836, uptime 573 days, 3:20:02
```

## Troubleshooting

### Log Files
- **Worker Log**: `/var/www/laravel/storage/logs/worker.log`
- **Job Worker Log**: `/var/www/laravel/storage/logs/job-worker.log`
- **Laravel Log**: `storage/logs/laravel.log`

### Common Issues
1. **Workers not running**: Check supervisor status and restart if needed
2. **Queue jobs stuck**: Clear failed jobs with `php artisan queue:clear`
3. **AWS upload failures**: Verify AWS credentials and S3 permissions
4. **Sonix API issues**: Check API credentials and rate limits

### Development vs Production
- **Development**: Use `php artisan schedule:work` for testing
- **Production**: Use Supervisor to manage workers automatically

## External Dependencies

- **AWS S3**: Video storage and hosting
- **AWS MediaConvert**: Video compression and format conversion
- **Sonix API**: Automated transcription services
- **MySQL**: Primary database
- **Supervisor**: Process management (production)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).