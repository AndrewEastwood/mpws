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

```
mkdir /var/www/{etc,lib};
cp /etc/hosts /var/www/etc/hosts;
cp /etc/resolv.conf /var/www/etc/resolv.conf;
cp /lib/libnss_dns.so.2 /var/www/lib/libnss_dns.so.2 // x86
cp /lib64/libnss_dns.so.2  /var/www/lib64/libnss_dns.so.2 // x64
```

```
/etc/nginx/sites-enabled$ ll
drwxr-xr-x 2 root root 4096 Feb 13 10:46 ./
drwxr-xr-x 5 root root 4096 Nov 19 14:03 ../
lrwxrwxrwx 1 root root   34 Nov 19 13:26 default -> /etc/nginx/sites-available/default
lrwxrwxrwx 1 root root   36 Feb 13 10:46 mpws.conf -> /etc/nginx/sites-available/mpws.conf
```

```
nano /etc/nginx/sites-enabled/mpws.conf
```

```
server {
    listen 5001;
    root   /var/www/mpws;
    server_name pb.com.ua www.pb.com.ua;
    access_log /var/www/mpws/nginx.access.log;
    error_log /var/www/mpws/nginx.error.log;
    index /engine/controller/controller.display.php;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

        # With php5-cgi alone:
        #fastcgi_pass 127.0.0.1:9000;
        # With php5-fpm:
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        include fastcgi_params;
    }


    location /static {
      rewrite ^/static/customer/([\.a-z_-]+)/nls/(.*) /engine/controller/controller.nls.php?customer=$1&lang=$2 last;
      rewrite ^/static/(customer|plugin|default)/([\.a-z_-]+)/(.*) /web/$1/$2/static/$3 break;
    }

    location /toolbox {
        rewrite ^/toolbox/api\.js?(.*) /engine/controller/controller.api.php?$1 last;
        rewrite ^/toolbox/?(.*) /engine/controller/controller.display.php?$1 last;
    }

    location /api {
      rewrite ^/api\.js(.*) /engine/controller/controller.api.php?$1 last;
    }

    location /auth {
      rewrite ^/auth\.js(.*) /engine/controller/controller.auth.php?$1 last;
    }

}
```


**Update configuration**

* set your user name and group
* change "listen" property ```/etc/php5/fpm$ nano /etc/php5/fpm/pool.d/www.conf```

```
user = andrew
group = www-data
listen = /var/run/php5-fpm.sock
```

```
/etc/init.d/nginx restart
/etc/init.d/php5-fpm restart
```
