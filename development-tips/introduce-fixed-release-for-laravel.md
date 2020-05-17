# 라라벨에서의 버전의 의미

라라벨의 버전은 7로 넘어오면서 약간 복잡해 진 면이 있습니다. 라라벨 커뮤니티에서도 라라벨 버전이 빠르게 올라가는 것에 대한 막연한 두려움\(?\)도 있는 것 같고요.

이번 글에서는 라라벨의 버전이 무엇을 말하는건지, 그리고 하나의 팁을 알려드리려 합니다.

## 라라벨의 버전이란

공식문서에 따르면 라라벨의 버전은 이렇게 알 수 있습니다.

```bash
php artisan --version
```

방금 컴포져로 업데이트를 했다면 이렇게 나와야 합니다.

```bash
Laravel Framework 7.9.2
```

우리가 흔히 알고 있는 라라벨의 버전은 라라벨 프레임워크의 버전이며, [Allication.php 파일](https://github.com/laravel/framework/blob/7.x/src/Illuminate/Foundation/Application.php)에 아래와 같이 선언되어 있습니다.

```php
<?php

// 경로 : vendor/laravel/framework/illuminate/Foundation/Application.php

class Application extends Container implements ApplicationContract, CachesConfiguration, CachesRoutes, HttpKernelInterface
{
    /**
     * The Laravel framework version.
     *
     * @var string
     */
    const VERSION = '7.9.2';

    ...
}
```

라라벨 프레임워크의 저장소는 깃헙의 [laravel/framework 저장소](https://github.com/laravel/framework)이며, 패키지스트에도 동일하게 등록되어 있습니다.

## 라라벨을 설치할 때의 버전은 다르다

반면 `laravel new blog` 혹은 `composer create-project --prefer-dist laravel/laravel blog` 등으로 설치할 때에는 라라벨 프레임워크가 아닌 [laravel/laravel 저장소](https://github.com/laravel/laravel)의 코드를 사용하며, 역시 [패키지스트에도 동일하게 등록](https://packagist.org/packages/laravel/laravel)되어 있습니다.

`laravel/laravel`은 라이브러리나 패키지라기 보다는 일종의 보일러플레이트 혹은 스켈레톤 입니다. 프레임워크에 따라서 이 부분을 명시적으로 표기한 것도 있는데요, 대표적으로 [초소형 프레임워크로 알려져있는 Slim 프레임워크](https://github.com/slimphp/Slim-Skeleton)입니다.

```text
Slim Framework 4 Skeleton Application
Coverage Status

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application. This application uses the latest Slim 4 with Slim PSR-7 implementation and PHP-DI container implementation. It also uses the Monolog logger.
```

위의 설명에서 `Skeleton`이라는 표현을 볼 수 있습니다.

## 팁. laravel/laravel 코드를 업데이트하는 방법

`laravel/framework`의 업데이트는 컴포져가 해 주기 때문에 `composer.json`파일 수정만으로 끝납니다. 문제는 우리가 처음 프로젝트를 만들 때 이용했던 `laravel/laravel` 코드의 업데이트겠죠. 이 부분은 라라벨 공식문서에서 `Upgrade Guide`라는 이름으로 제공을 했습니다.

반면, 라라벨 7은 `laravel/laravel`의 버전이 업데이트가 되었는데도 불구하고 특별한 가이드를 제공하지 않습니다. 물론 시멘틱 버저닝을 충실히 지킨다면 하위호환성을 보장하기 때문에 별 문제는 없을 것으로 예상합니다만, `hotfix`의 경우는 어떻게 처리해야 할까요?

이 부분에 대해서 새로운 버전을 받아서 diff 툴을 이용해서 merge하는 분도 있습니다. 제가 추천하는 방법은 실제로 코드의 커밋 리스트를 보고 스스로 판단하는게 좋을 것 같습니다.

`laravel/laravel`의 버전은 깃헙의 tag를 이용하며, 글을 쓰는 현재 등록되어 있는 버전은 아래와 같습니다.

```text
v7.6.0
v7.3.0
v7.0.0
```

깃헙이 제공하는 Compare changes를 보면 [v7.0.0과 v7.3.0과의 비교](https://github.com/laravel/laravel/compare/v7.3.0...master)에서는 총 19개 커밋과 12개의 파일이 수정되었다고 나옵니다. [v7.6.0과의 비교](https://github.com/laravel/laravel/compare/v7.0.0...v7.6.0)에서는 28개의 커밋과 13개의 파일이 수정되었다고 나오죠.

이 정도의 수정은 한땀한땀 처리해도 될 만한 수준이라고 생각합니다. 게다가 꼭 처리해야 하는 커밋은 그다지 많지 않습니다.

## 정리

`composer update`를 이용해서 `laravel/framework`를 업데이트하는 것은 수시로 하는 것이 좋은 것 같습니다. 왜냐하면 버그가 지속적으로 수정되고 있기 때문입니다.

반면 전통적으로 라라벨을 업데이트한다는 의미로서의 `laravel/laravel`의 업데이트는 제가 위에 소개해 드린 커밋리스트를 보시고 처리하는 것을 추천드립니다. 실제 해 보시면 몇개 되지는 않습니다.

누가 라라벨 버전을 몇 쓰는지 물어보신다고요? `php artisan --version`의 값을 이야기하는건 반만 맞습니다. 전 앞으로 이렇게 이야기 하려 합니다.

```text
라라벨 버전은 7.6.0 이고, 프레임워크는 7.9.2 네요!
```

오늘도 즐거운 라라벨 생활 되시기 바랍니다!
