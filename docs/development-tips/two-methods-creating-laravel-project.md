# 라라벨 프로젝트를 만드는 두가지 방법 비교

라라벨을 처음 접하는 분들은 공식문서에 나오는 두가지 방법에 대해서 혼란이 생길 수도 있습니다. 두가지 방법이 어떻게 다른지 알아보도록 하죠.

## Update 2020-11-19

라라벨 인스톨러의 최신버전(v4.x)에서는 `laravel new`와 `composer create-project`로 만들어지는 프로젝트의 차이가 없어졌습니다.

`laravel new`로 생성할 경우 `composer create-project`로 우선 생성 후 퍼미션과 .env파일 그리고 제트스트림 처리까지 해 주기 때문에 신규 프로젝트 생성에는 `laravel new`를 사용하는 것을 강하게 추천합니다.

아래의 내용은 라라벨 인스톨러 v3.x에 해당되는 내용입니다.

## 방법 1. laravel new blog

우선 라라벨 공식문서에 나오는 내용을 확인해 보죠:

```bash
laravel new blog
```

라라벨 인스톨러를 컴포져로 설치한 후 `laravel` 커맨드를 이용해서 프로젝트를 생성합니다.

어떤 일이 벌어질까요?

라라벨 인스톨러는 매우 단순합니다. 심포니의 커맨드 프레임워크를 베이스로 단 [하나의 커맨드](https://github.com/laravel/installer/blob/master/src/NewCommand.php)만 생성하고 있습니다.

```php
<?php

/**
* Configure the command options.
*
* @return void
*/
protected function configure()
{
    $this
        ->setName('new')
        ->setDescription('Create a new Laravel application')
        ->addArgument('name', InputArgument::OPTIONAL)
        ->addOption('dev', null, InputOption::VALUE_NONE, 'Installs the latest "development" release')
        ->addOption('auth', null, InputOption::VALUE_NONE, 'Installs the Laravel authentication scaffolding')
        ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forces install even if the directory already exists');
}
```

위의 코드로 추론해 볼 수 있는건 커맨드 이름은 `new`이며, 옵션으로 `dev`, `auth`, `force`를 사용할 수 있습니다.

커맨드에서 `laravel help new`를 하면 더 많은 옵션이 있으나, 심포니 콘솔 프레임워크에서 제공하는 것으로 라라벨 인스톨러와는 직접적인 연관은 되지 않습니다.

코드에서 묘사된 설명과 같이 `dev`는 마지막 개발 릴리즈를, `auth`는 인증 스케폴딩을, `force`는 이미 설치된 라라벨 프로젝트가 있다고 해서 강제 설치합니다.

위의 세가지 라라벨 코드는 어디에서 가져오는 것일까요? 네, 깃헙이 아닙니다.

다음의 코드를 살펴보시죠:

```php
<?php

/**
* Download the temporary Zip to the given file.
*
* @param  string  $zipFile
* @param  string  $version
* @return $this
*/
protected function download($zipFile, $version = 'master')
{
    switch ($version) {
        case 'develop':
            $filename = 'latest-develop.zip';
            break;
        case 'auth':
            $filename = 'latest-auth.zip';
            break;
        case 'master':
            $filename = 'latest.zip';
            break;
    }

    $response = (new Client)->get('http://cabinet.laravel.com/'.$filename);

    file_put_contents($zipFile, $response->getBody());

    return $this;
}
```

`laravel new` 커맨드는 깃헙이 아닌, [http://cabinet.laravel.com/](http://cabinet.laravel.com/) 이라는 별도의 저장소에서 가져옵니다.

그 후 Zip 파일을 다운로드 받고, 간단한 체크를 한 후 `laravel new` 다음에 나오는 문자열로 폴더를 생성해서 그 곳에 압축파일을 풉니다.

라라벨 커맨드는 그 후에 `bootstrap/cache`과 `storage` 폴더의 권한을 0755로 바꾸어 줍니다. 이 과정에서 권한 수정이 안될 경우 직접 수정하라는 메시지가 출력됩니다.

## 방법 2. composer create-project

```bash
composer create-project --prefer-dist laravel/laravel blog
```

컴포져의 `create-project` 커맨드가 하는 일은 무엇일까요?

컴포져의 공식문서에 따르면 이 옵션이 하는 일은 git의 clone 혹은 svn의 체크아웃과 하는 일이 완벽히 같다고 합니다. 말하자면, git과 svn의 래퍼\(wrapper\)가 되겠네요.

깃헙을 예로 든다면, 컴포져의 `create-project`를 사용해서 특정 버전을 받는다던지, `stable`한 버전만 받는다던지 하는 일들을 컴포져 명령어로 할 수 있습니다.

더 중요한 것은 컴포저에 개발환경까지 맞춰주는 기능을 제공한다는 점일 것입니다. php의 버전과 익스텐션의 유무로 라라벨이 정상적으로 설치되었는지를 알려줍니다.

이 명령에 사용할 수 있는 옵션은 이렇습니다:

```text
--stability (-s): Minimum stability of package. Defaults to stable.
--prefer-source: Install packages from source when available.
--prefer-dist: Install packages from dist when available.
--repository: Provide a custom repository to search for the package, which will be used instead of packagist. Can be either an HTTP URL pointing to a composer repository, a path to a local packages.json file, or a JSON string which similar to what the repositories key accepts.
--add-repository: Add the repository option to the composer.json.
--dev: Install packages listed in require-dev.
--no-dev: Disables installation of require-dev packages.
--no-scripts: Disables the execution of the scripts defined in the root package.
--no-progress: Removes the progress display that can mess with some terminals or scripts which don't handle backspace characters.
--no-secure-http: Disable the secure-http config option temporarily while installing the root package. Use at your own risk. Using this flag is a bad idea.
--keep-vcs: Skip the deletion of the VCS metadata for the created project. This is mostly useful if you run the command in non-interactive mode.
--remove-vcs: Force-remove the VCS metadata without prompting.
--no-install: Disables installation of the vendors.
--ignore-platform-reqs: ignore php, hhvm, lib-* and ext-* requirements and force the installation even if the local machine does not fulfill these.
```

## 여러분의 선택은?

완전히 같다고 생각했던 두개의 설치 방법이 실제로 코드 리뷰를 해 보니 받아오는 저장소가 다르고 하는 일도 약간씩 달랐습니다.

컴포져를 이용해 설치를 하면 `laravel new`에서 해 주는 폴더 권한을 직접 해야하며, 그 반대의 경우에는 컴포져의 여러가지 옵션을 사용할 수 있습니다.

전 지금까지 `laravel new`를 이용해서 설치를 했습니다. 설치 후에 해야 할 일에 대한 고민을 하지 않기 위해서 였는데요, 개발환경과 라라벨의 업데이트를 생각해 보니 컴포져를 이용한 설치도 고려를 해 봐야 겠다는 생각이 들었습니다.

개발 환경 세팅은 아무리 말해도 그 중요성이 지나치지 않을 것입니다.

무심코 넘어간 부분이라도 이 글이 작게나마 도움이 되었으면 좋겠습니다.

오늘도 행복한 라라벨 생활 되시기 바랍니다.
