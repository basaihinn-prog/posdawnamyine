server {
    listen 80;
    server_name %SERVER_NAME%;

    root %APP_ROOT%/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \\.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php-fpm.sock; # adjust for your PHP-FPM socket
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~* \\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2)$ {
        try_files $uri =404;
        access_log off;
        log_not_found off;
        expires max;
    }
}
