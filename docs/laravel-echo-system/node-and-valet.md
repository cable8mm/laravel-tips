# 라라벨 발렛(Laravel Valet)에서 Node 실행하기

Valet에서 Node 를 실행하는 가장 간편한 방법을 소개합니다. 순서대로 따라 해 보세요.

우선 Valet에서 하던데로 디렉토리를 만들고 https를 설정합니다.

```sh
mkdir node

valet secure
```

간단한 커맨드 만으로 Valet은 브라우져에서 <https://node.test> 로 접속을 할 수 있게 만들어 줍니다.

이 상태에서 Node를 실행하는 방법은 간단합니다.

```sh
cd /Users/[MAC_NAME]/.config/valet/Nginx
```

Nginx 폴더에 가면 node.test가 있습니다. 이 폴더에는 `valet secure`로 https를 설정한 사이트들만 파일을 생성합니다.

node.test 파일에서 다음을 추가합니다.

```nginx
map $sent_http_content_type $expires {
    "text/html"                 epoch;
    "text/html; charset=utf-8"  epoch;
    default                     off;
}
```

그리고, 아래의 코드를 찾아서

```nginx
location / {
    rewrite ^ "/Users/[MAC_NAME]/.composer/vendor/laravel/valet/server.php" last;
}
```

이렇게 수정합니다.

```nginx
location / {
    expires $expires;

    proxy_redirect                      off;
    proxy_set_header Host               $host;
    proxy_set_header X-Real-IP          $remote_addr;
    proxy_set_header X-Forwarded-For    $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto  $scheme;
    proxy_read_timeout          1m;
    proxy_connect_timeout       1m;
    proxy_pass                          http://127.0.0.1:3000; # set the adress of the Node.js instance here
}
```

마지막으로 서버 이름을 접속 주소로 수정합니다.

```nginx
server_name node.test www.node.test *.node.test;
```

그 후에 `valet restart` 커맨드를 실행하면 <https://node.test> 로 접속을 하면 <http://127.0.0.1:3000> 으로 접속됩니다.(proxy_pass)

자, 이제 간단한 node로 웹서버를 띄웁니다.

```js
const http = require("http");

http
  .createServer((req, res) => {
    res.statusCode = 200;
    res.setHeader("Content-Type", "text/plain");
    res.end("Hello World");
  })
  .listen(3000);
```

이제 위의 코드를 `index.js`로 저장한 후 `node index.js` 명령어로 실행합니다.

브라우져에 <https://node.test> 를 입력하면 Hello World를 볼 수 있습니다!

다음은 `node.test` 설정파일 전문입니다.

```nginx
server {
    listen 127.0.0.1:80;
    #listen 127.0.0.1:80; # valet loopback
    server_name node.test www.node.test *.node.test;
    return 301 https://$host$request_uri;
}

map $sent_http_content_type $expires {
    "text/html"                 epoch;
    "text/html; charset=utf-8"  epoch;
    default                     off;
}

server {
    listen 127.0.0.1:443 ssl http2;
    #listen 127.0.0.1:443 ssl http2; # valet loopback
    server_name node.test www.node.test *.node.test;
    root /;
    charset utf-8;
    client_max_body_size 512M;
    http2_push_preload on;

    location /41c270e4-5535-4daa-b23e-c269744c2f45/ {
        internal;
        alias /;
        try_files $uri $uri/;
    }

    ssl_certificate "/Users/cable8mm/.config/valet/Certificates/node.test.crt";
    ssl_certificate_key "/Users/cable8mm/.config/valet/Certificates/node.test.key";

    location / {
        expires $expires;

        proxy_redirect                      off;
        proxy_set_header Host               $host;
        proxy_set_header X-Real-IP          $remote_addr;
        proxy_set_header X-Forwarded-For    $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto  $scheme;
        proxy_read_timeout          1m;
        proxy_connect_timeout       1m;
        proxy_pass                          http://127.0.0.1:3000; # set the adress of the Node.js instance here
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log "/Users/cable8mm/.config/valet/Log/nginx-error.log";

    error_page 404 "/Users/cable8mm/.composer/vendor/laravel/valet/server.php";

    location ~ [^/]\.php(/|$) {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass "unix:/Users/cable8mm/.config/valet/valet.sock";
        fastcgi_index "/Users/cable8mm/.composer/vendor/laravel/valet/server.php";
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME "/Users/cable8mm/.composer/vendor/laravel/valet/server.php";
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location ~ /\.ht {
        deny all;
    }
}

server {
    listen 127.0.0.1:60;
    #listen 127.0.0.1:60; # valet loopback
    server_name node.test www.node.test *.node.test;
    root /;
    charset utf-8;
    client_max_body_size 128M;

    add_header X-Robots-Tag 'noindex, nofollow, nosnippet, noarchive';

    location /41c270e4-5535-4daa-b23e-c269744c2f45/ {
        internal;
        alias /;
        try_files $uri $uri/;
    }

    location / {
        rewrite ^ "/Users/cable8mm/.composer/vendor/laravel/valet/server.php" last;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log "/Users/cable8mm/.config/valet/Log/nginx-error.log";

    error_page 404 "/Users/cable8mm/.composer/vendor/laravel/valet/server.php";

    location ~ [^/]\.php(/|$) {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass "unix:/Users/cable8mm/.config/valet/valet.sock";
        fastcgi_index "/Users/cable8mm/.composer/vendor/laravel/valet/server.php";
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME "/Users/cable8mm/.composer/vendor/laravel/valet/server.php";
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location ~ /\.ht {
        deny all;
    }
}
```
