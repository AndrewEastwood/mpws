**add required cert**

```
echo "deb http://backports.debian.org/debian-backports lenny-backports main" >> /etc/apt/sources.list
echo "deb http://php53.dotdeb.org stable all" >>   /etc/apt/sources.list
gpg --keyserver keys.gnupg.net --recv-key 89DF5277 && gpg -a --export 89DF5277 | apt-key add -
```

**aptitude update**

```
aptitude install -t lenny-backports "nginx"
sudo apt-get install nginx
apt-get install php5-cli php5-common php5-suhosin 
apt-get install php5-fpm php5-cgi
```

**Update php config:**

```
nano /etc/php5/fpm/php.ini
cgi.fix_pathinfo=0
```

**add missing folders and copy required files**
```
mkdir /var/www/{etc,lib};
cp /etc/hosts /var/www/etc/hosts;
cp /etc/resolv.conf /var/www/etc/resolv.conf;
cp /lib/libnss_dns.so.2 /var/www/lib/libnss_dns.so.2 // x86
cp /lib64/libnss_dns.so.2  /var/www/lib64/libnss_dns.so.2 // x64
```

**Update hosts file**
Add few lines into ```/ets/hosts``` file
```
127.0.0.1       dev.leogroup.com.ua
127.0.0.1       dev.www.leogroup.com.ua
127.0.0.1       dev.toolbox.leogroup.com.ua
```

**create mpws.conf**

Create empty config:
```
sudo nano /etc/nginx/sites-enabled/mpws.conf
```
and put the following configuration.
**Note:** there are two configs: first one is for _dev_ sources and another is for _dist_ sources
```
server {
    listen 5001;
    root   /var/www/mpws;
    server_name dev.leogroup.com.ua dev.www.leogroup.com.ua dev.toolbox.leogroup.com.ua;
    access_log /var/www/mpws/nginx.access.log;
    error_log /var/www/mpws/nginx.error.log;
    index /engine/controllers/display.php;
#   add_header Access-Control-Allow-Headers "X-Requested-With";
#   add_header Access-Control-Allow-Methods "GET, HEAD, OPTIONS";
#   add_header Access-Control-Allow-Origin "*";

    location ~ /static_/.*\.php$ { return 404; }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

        # With php5-cgi alone:
        #fastcgi_pass 127.0.0.1:9000;
        # With php5-fpm:
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        include fastcgi_params;
    }

    location /api/ {
        rewrite ^/api/(\w+)/(\w+)/$ /engine/controllers/api.php?api=$1:$2&t=1 last;
        rewrite ^/api/(\w+)/(\w+)/\? /engine/controllers/api.php?api=$1:$2&t=2 last;
        rewrite ^/api/(\w+)/(\w+)$ /engine/controllers/api.php?api=$1:$2&t=3 last;
        rewrite ^/api/(\w+)/(\w+)/(.*)/$ /engine/controllers/api.php?api=$1:$2&params=$3&t=p1 last;
        rewrite ^/api/(\w+)/(\w+)/(.*)/\? /engine/controllers/api.php?api=$1:$2&params=$3&t=p2 last;
        rewrite ^/api/(\w+)/(\w+)/(.*)$ /engine/controllers/api.php?api=$1:$2&params=$3&t=p3 last;
        # rewrite ^/api/(\w+)/(\w+)/?(.*) /engine/controllers/api.php?api=$1:$2&params=$3 last;
        # rewrite ^/api/(\w+)/(\w+)/ /engine/controllers/api.php?api=$1:$2 last;
        # rewrite ^/api/(\w+)/(\w+) /engine/controllers/api.php?api=$1:$2 last;
    }

    location /upload {
        rewrite ^/upload/$ /engine/controllers/upload.php last;
    }

}

server {
    listen 5555;
    root   /var/www/mpws/_dist_;
    server_name dev.leogroup.com.ua dev.www.leogroup.com.ua dev.toolbox.leogroup.com.ua;
    access_log /var/www/mpws/nginx.access.log;
    error_log /var/www/mpws/nginx.error.log;
    index /engine/controllers/display.php;
#   add_header Access-Control-Allow-Headers "X-Requested-With";
#   add_header Access-Control-Allow-Methods "GET, HEAD, OPTIONS";
#   add_header Access-Control-Allow-Origin "*";

    location ~ /static_/.*\.php$ { return 404; }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

        # With php5-cgi alone:
        #fastcgi_pass 127.0.0.1:9000;
        # With php5-fpm:
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        include fastcgi_params;
    }

    location /api/ {
        rewrite ^/api/(\w+)/(\w+)/$ /engine/controllers/api.php?api=$1:$2&t=1 last;
        rewrite ^/api/(\w+)/(\w+)/\? /engine/controllers/api.php?api=$1:$2&t=2 last;
        rewrite ^/api/(\w+)/(\w+)$ /engine/controllers/api.php?api=$1:$2&t=3 last;
        rewrite ^/api/(\w+)/(\w+)/(.*)/$ /engine/controllers/api.php?api=$1:$2&params=$3&t=p1 last;
        rewrite ^/api/(\w+)/(\w+)/(.*)/\? /engine/controllers/api.php?api=$1:$2&params=$3&t=p2 last;
        rewrite ^/api/(\w+)/(\w+)/(.*)$ /engine/controllers/api.php?api=$1:$2&params=$3&t=p3 last;
        # rewrite ^/api/(\w+)/(\w+)/?(.*) /engine/controllers/api.php?api=$1:$2&params=$3 last;
        # rewrite ^/api/(\w+)/(\w+)/ /engine/controllers/api.php?api=$1:$2 last;
        # rewrite ^/api/(\w+)/(\w+) /engine/controllers/api.php?api=$1:$2 last;
    }

    location /upload {
        rewrite ^/upload/$ /engine/controllers/upload.php last;
    }

}


```
You may change paths to log files according to your project's location.
When you complete working on configuration file then the nginx's folder will probably look like this:

```
/etc/nginx/sites-enabled$ ll
drwxr-xr-x 2 root root 4096 Feb 13 10:46 ./
drwxr-xr-x 5 root root 4096 Nov 19 14:03 ../
lrwxrwxrwx 1 root root   34 Nov 19 13:26 default -> /etc/nginx/sites-available/default
lrwxrwxrwx 1 root root   36 Feb 13 10:46 mpws.conf -> /etc/nginx/sites-available/mpws.conf
```

**Update configuration**

* set your user name and group
* change "listen" property ```/etc/php5/fpm$ nano /etc/php5/fpm/pool.d/www.conf```

```
user = %PUT_YOUR_USER_NAME_HERE%
group = www-data
listen = /var/run/php5-fpm.sock
```

```
/etc/init.d/nginx restart
/etc/init.d/php5-fpm restart
```
