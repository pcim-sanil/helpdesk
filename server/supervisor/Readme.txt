# Activating this config

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue:*


Scale up/down: change numprocs to the desired number of workers and 
$ sudo supervisorctl update.

Stop workers:
$ sudo supervisorctl stop laravel-queue:* 

View status: 
$ sudo supervisorctl status laravel-queue