module.exports = {
  apps: [
    {
      name: 'laravel-queue',
      script: 'artisan',
      args: 'queue:work database --sleep=3 --tries=3 --timeout=600',
      interpreter: '/usr/bin/php',
      cwd: '/var/www/html/helpdesk',
      instances: 1,
      autorestart: true,
      max_memory_restart: '512M',
      kill_timeout: 30000,
      watch: false,
      uid: 'nginx',
      gid: 'nginx'
    },
  ],
};
