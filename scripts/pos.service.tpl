[Unit]
Description=PosDreamPool Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php %APP_ROOT%/artisan queue:work --sleep=3 --tries=3 --timeout=90
RestartSec=5

[Install]
WantedBy=multi-user.target
