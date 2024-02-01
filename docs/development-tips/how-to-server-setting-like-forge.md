# 퍼미션 에러없이 PHP를 구동하기 위한 서버 세팅법

라라벨과 같은 프레임워크를 이용하거나, 본인만의 개발환경을 구축해 놓은 개발자라도 캐시나 세션 파일 생성에 대한 문제가 생길 수 있는데요, 그 원인과 [라라벨 포지](https://forge.laravel.com/)에서는 어떻게 해결하고 있는지를 알아보도록 하겠습니다.

## 파일을 생성하는 자와 이용하는 자

라라벨과 같은 풀스택 프레임워크는 파일 생성이 꼭 웹서버를 통해서 이루어지지는 않습니다. php 커맨드나 cli를 통해서도 만들어지는데요, 대표적으로 세션이나 캐시파일, 로그파일입니다.

이런 파일은 웹서버가 만들수도 있고, 커맨드가 만들수도 있습니다. 문제는 웹서버와 커맨드의 권한이 다를때 발생합니다.

만약 CentOS와 아파치를 이용한다면, 기본적으로 `apache:apache`로 구동이 됩니다. 반면 소스는 다음과 같은 계정으로 구동이 됩니다.

1. \[user\]: users
2. \[user\]:\[user\]
3. root

1번은 CentOS를 설치할 때 만드는 계정으로 users 그룹은 `users:x:100:`으로 선언되어 있으며, 시스템 계정입니다.

2번은 OS가 설치된 후에 만든 계정입니다.

3번은 예를 들면 crontab을 root로 구동시켰을 때입니다.

정리하면 웹서버에 파일을 만드는 계정은 총 네가지로 구분할 수 있습니다.

 1. \[user\]: users
 2. \[user\]:\[user\]
 3. root
 4. apache:apache

## 어떤 문제가 생길까

일단 파일이 만들어지면 [777로 퍼미션을 변경해서 어떤 계정으로도 수정이 가능하도록 하는 방법](https://www.lesstif.com/php-and-laravel/laravel-log-file-permission-48103448.html)도 있습니다. 하지만, 세션이나 캐시와 같은 파일은 소스가 배포된 후에 만들어지기 때문에 403 등의 에러가 발생됩니다.

가장 치명적인 시나리오는 소스를 배포한 후 캐시를 지웠는데, 커맨드에서 캐시를 생성해 버리는 경우입니다.

이럴 경우 서버의 설정에 따라 에러 메시지가 나오기도 하지만, 서비스가 굉장히 느려지게 됩니다. 세션파일에서 문제가 생기면 로그인이 배포할 때 풀려버리기도 하죠.

그렇다면 라라벨의 상용 서버 관리 서비스인 라라벨 포지는 어떻게 이런 이슈를 해결할까요?

## 라라벨 포지의 퍼미션 해결책

라라벨 포지를 통해서 서버를 세팅하게 되면, forge라는 계정이 만들어집니다. 그 후에 웹서버(우분투)를 forge 계정으로 구동시키며, 포지를 통해 배포되는 코드는 모두 forge:forge 권한이 됩니다.

쉽게 말하자면, 웹서버의 구동 계정과 소스코드 및 폴더 계정을 forge로 일치시키는 것인데요, 크론탭을 이용하는 라라벨도 구동 계정이 forge입니다.

forge는 OS를 설치할 때 생성하는 계정이 아니라 OS 설치 후에 별도로 만들어지는 일반 계정입니다.

> 라라벨 포지를 통해서 AWS EC2를 세팅한다면, root, ubuntu, forge 이렇게 세개의 계정이 만들어 집니다.

## CentOS + Apache 조합일 때 해결책

라라벨 포지는 Ubuntu + NGINX 조합으로 세팅하는데요, CentOS + Apache 조합에서도 이 방법을 이용할 수 있습니다.

우선 다음과 같이 forge 계정을 만듭니다.

```console
adduser forge
```

그리고, apache 의 구동 계정을 `/etc/httpd/conf/httpd.conf` 에서 forge로 수정해 줍니다.

```console
#
# If you wish httpd to run as a different user or group, you must run
# httpd as root initially and it will switch.
#
# User/Group: The name (or #number) of the user/group to run httpd as.
# It is usually good practice to create a dedicated user and group for
# running httpd, as with most system services.
#
User forge
Group forge
```

사용되는 코드의 디렉토리 전체를 forge:forge로 바꾸어 주고, apache를 재시작합니다.

```console
chown -R forge:forge /home/someone/project_folder

service httpd restart
```

만약 별도의 배포시스템이 있다면 배포할 때의 계정도 forge로 바꾸어주어야 합니다. 당연히 crontab 구동 계정도 forge로 바꾸어 주어야 하고요.

## 우분투에서 forge를 사용하지 않을 때

CentOS + Apache 조합과 마찬가지로 Ubuntu + Nginx 조합을 세팅하면 됩니다.

Ubuntu에서 forge:forge 계정을 생성한 후 Nginx의 구동 계정을 `/etc/nginx/nginx.conf` 에서 forge로 바꿉니다.

PHP-FRM 구동 계정도 forge로 바뀌어야 하겠죠.
