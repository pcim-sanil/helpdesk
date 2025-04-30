# create supervisor, with content from ./laravel-queue.conf

$ cat /etc/supervisor/conf.d/laravel-worker.conf


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

________________________________________
#### Create queue worker using PM2, node based solutions #####

### 1. Make sure you have Node.js & npm installed, then:
$ sudo npm install -g pm2


## 2. Create a PM2 ecosystem file
In your project root (/var/www/html/helpdesk), create ecosystem.config.cjs:

module.exports = {
  apps: [
    {
      name: 'laravel-queue',
      script: 'artisan',
      args: 'queue:work database --sleep=3 --tries=3 --timeout=600',
      interpreter: '/usr/bin/php',
      cwd: '/var/www/html/helpdesk',
      instances: 4,
      autorestart: true,
      watch: false,
      uid: 'nginx',
      gid: 'nginx'
    },
  ],
};


## 3. Start and Save  
$ cd /var/www/html/helpdesk
$ sudo pm2 start ecosystem.config.cjs
$ sudo pm2 save


### 4. Configure PM2 to auto-start on reboot
sudo pm2 startup systemd




