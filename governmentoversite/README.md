## How to get application running
1. You need mysql
2. Configure .env accordingly
    - DB_CONNECTION=mysql
    - DB_HOST=127.0.0.1   Note: sometimes this has to be set to localhost
    - DB_PORT=3306
    - DB_DATABASE=GOPrototype
    - DB_USERNAME=root
    - DB_PASSWORD=
3. Open up a command line, navigate to project sub-directory, and run the following command to create the database:
    - php artisan migrate
4. Run the following to create users to allow you to log in:
    - php artisan db:seed --class=CreateUserGroupsSeeder
    - php artisan db:seed --class=CreateTestUsersSeeder
    - php artisan db:seed --class=CreateVideoCategoriesSeeder
    - php artisan db:seed --class=CreateWordContractionsSeeder
    - php artisan db:seed --class=VideoProcessingStatesSeeder
    - php artisan db:seed --class=KeywordsSeeder
    - php artisan db:seed --class=ConfigurationSeeder
    - php artisan db:seed --class=AgendaItemTypeSeeder
    - php artisan db:seed --class=CreateUserSubscriptionsSeeder
    - php artisan db:seed --class=VideosSeeder

    - php artisan db:seed --class=CreateUserGroupsSeeder && php artisan db:seed --class=CreateTestUsersSeeder && php artisan db:seed --class=CreateVideoCategoriesSeeder && php artisan db:seed --class=CreateWordContractionsSeeder && php artisan db:seed --class=VideoProcessingStatesSeeder && php artisan db:seed --class=KeywordsSeeder && php artisan db:seed --class=ConfigurationSeeder && php artisan db:seed --class=AgendaItemTypeSeeder && php artisan db:seed --class=CreateUserSubscriptionsSeeder && php artisan db:seed --class=VideosSeeder && php artisan db:seed --class=ChannelsSeeder
5. These are the users created above:
    - Guest:        User@domain.com/testpw
    - Subscriber:   Subscriber@domain.com/testpw
    - Subscribed:   Subscribed@domain.com/testpw - emulates a paid subscription
    - Admin:        Admin@domain.com/testpw
6. If you want the daemons to fire, run the following:
    - php artisan schedule:work
7. To allow application to start up, run the following:
    - php artisan serve
8. Using the browser of your choice, navigate to:
    - http://localhost:8000/
	
## State Transitions
<table>
	<tr>
		<th>
			Work/Event Causing Transition Into This State
		</th>
		<th>
			State
		</th>
		<th>
			CRON Job Operating On This State
		</th>
	</tr>
	<tr>
		<td>
			Administrator Uploads a New Video on Web Site
		</td>
		<td>
			Admin Uploaded Video(1)
		</td>
		<td>
			AWSCreateSubDirectoryCRONController
		</td>
	</tr>
	<tr>
		<td>
			A SubDirectory has been created in AWS S3
		</td>
		<td>
			Created SubDirectory in AWS(2)
		</td>
		<td>
			AWSVideoUploadCRONController
		</td>
	</tr>	
	<tr>
		<td>	
			Video being upload to AWS S3
		</td>
		<td>
			Uploading to AWS(3)
		</td>
		<td>
			? (Adam need help here)
		</td>
	</tr>
	<tr>
		<td>
			Checking to see if uploaded completed to AWS S3
		</td>
		<td>
			Uploaded to AWS(4)
		</td>
		<td>
			AWSVideoCompressionCRONController
		</td>
	</tr>
	<tr>
		<td>
			Video Compression Initiated
		</td>
		<td>
			Compressing Video in AWS(5)
		</td>
		<td>
			AWSVideoCompressionStatusCheckCRONController
		</td>
	</tr>
	<tr>
		<td>
			Compression Completed
		</td>
		<td>
			Compressed Video in AWS(6)
		</td>
		<td>
			SonixUploadCRONController
		</td>
	</tr>
	<tr>
		<td>
			Transcription upload to Sonix
		</td>
		<td>
			Uploaded to Sonix(7)
		</td>
		<td>
			SonixTranscriptionStatusCheckCRONController
		</td>
	</tr>
	<tr>
		<td>
			Transcription completed
		</td>
		<td>
			Sonix Transcription Done(8)
		</td>
		<td>
			SonixTranscriptionDownLoadCRONController
		</td>
	</tr>
	<tr>
		<td>
			Transcription downloaded from Sonix
		</td>
		<td>
			Downloaded Sonix Transcription(9)
		</td>
		<td>
			AWSTranscriptionUploadCRONController
		</td>
	</tr>
	<tr>
		<td>
			Transcription uploaded to AWS
		</td>
		<td>
			Uploaded transcript to AWS(10)
		</td>
		<td>
			TranscriptionParseCRONController
		</td>
	</tr>
	<tr>
		<td>
			Transcription parsed
		</td>
		<td>
			Transcription Parsed(11)
		</td>
		<td>
			N/A - Transition is caused by admin
		</td>
	</tr>
	<tr>
		<td>
			Administrator Publishes a video
		</td>
		<td>
			Published(12)
		</td>
		<td>
			VideoNotificationCRONController & EMailBodyPreparationCRONController
		</td>
	</tr>
	<tr>
		<td>
			Email Notifications Built & Sent out
		</td>
		<td>
			Emails Sent(13)
		</td>
		<td>
			SendNotificationsCRONController
		</td>
	</tr>					
</table>

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Unit Tests
To run Unit Test, run the following command in the root directory of the Laravel project:
    vendor/bin/phpunit

## Server Commands
sudo supervisorctl fg laravel-worker:cron-worker
sudo supervisorctl fg laravel-job-worker:long-job-worker 

sudo supervisorctl start laravel-worker:*
sudo supervisorctl start laravel-job-worker:*

sudo supervisorctl stop laravel-worker:* && 
sudo supervisorctl stop laravel-job-worker:*


sudo supervisorctl status
sudo supervisorctl reread
sudo supervisorctl update


sudo vim /etc/supervisor/conf.d/laravel-cron-worker.conf
sudo vim /etc/supervisor/conf.d/laravel-job-worker.conf

php artisan schedule:work
php artisan queue:work --queue=adam --once


## Laravel Scheduled Worker Program
[program:laravel-worker]
process_name=cron-worker
command=php artisan schedule:work
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/laravel/storage/logs/worker.log
directory=/var/www/laravel

## Laravel Queue Program          
[program:laravel-job-worker]
process_name=long-job-worker
command=php artisan queue:work database --queue=adam
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/laravel/storage/logs/job-worker.log
directory=/var/www/laravel         

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
