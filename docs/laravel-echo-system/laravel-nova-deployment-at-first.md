# 라라벨 노바의 Envoyer 배포의 실전

라라벨 노바가 현재의 시스템과 같은 프로젝트에서 운용할 수 있다는 구조적인 잇점 때문에 적극적으로 사용해 보려고 시도하는 중 첫번째 난관에 부딪혔습니다. 바로 `Envoyer` 배포에서 말이죠.

이 글에선 라라벨 노바로 `Envoyer`에서 배포할 때 라라벨 프로젝트와는 다른 부분을 설명해 보려 합니다.

## 라라벨 노바의 인증 해결하기

라라벨 노바는 상용 소스입니다. 소스는 공식 웹사이트의 Release에서 다운로드를 받을 수도 있고 컴포져로 받을 수도 있습니다. 라라벨 사용자라면 컴포져를 선호할 텐데요, 처음 컴포져로 받을 때는 커맨드를 이용한 인증을 하게 됩니다. 한번 인증하면 업데이트를 할 때는 별도의 인증을 거치지 않습니다.

```javascript
"repositories": [
    {
        "type": "composer",
        "url": "https://nova.laravel.com"
    }
]
```

하지만, 배포를 할 때는 어떻게 할까요?

라라벨 노바는 컴포져에서 지원하는 `HTTP basic authentication`을 이용합니다. 전 이번에 처음 알게 되었습니다만, 컴포져에서는 `auth.json` 파일에 아래와 같이 [아이디와 비번을 넣어서 인증을 처리하는 규격](https://getcomposer.org/doc/articles/http-basic-authentication.md)이 있었습니다.

노바를 배포하기 전 다음과 같은 순서로 인증을 하면 `auth.json`파일 만으로 배포할 때 컴포져 인스톨에서 라라벨 노바의 인증을 통과할 수 있습니다.

```bash
composer config http-basic.nova.laravel.com ${NOVA_USERNAME} ${NOVA_PASSWORD}
```

위의 커맨드를 참고로 노바 아이디와 비밀번호를 이용해서 `auth.json`파일을 만듭니다. 만약 비밀번호 노출이 염려되는 상황이라면 라라벨 노바의 `Account Settings` 메뉴의 Password를 클릭 후 가장 밑에 나오는 `API Token`에 나오는 값을 비밀번호로 대신합니다.

## 배포 후 뜨는 404 화면

로컬에서 개발한 후 서버로 배포를 하게 되면 모든 페이지가 404 에러 화면으로 보이게 됩니다. 그리고, 디버거나 로그파일에 아무것도 남지 않습니다. 그건 라라벨 노바에서 기본으로 제공하는 Gate 때문입니다. 이 Gate는 로컬환경에서는 적용되지 않고, 로컬환경이 아닐때만 적용이 됩니다.\(`.env`파일의 `APP_ENV` 값을 따라갑니다.\)

라라벨 노바의 `app/Providers/NovaServiceProvider.php` 파일에는 아래와 같이 노바를 볼 수 있는 이메일을 작성하는 코드가 있습니다.

```php
<?php

protected function gate()
{
    Gate::define('viewNova', function ($user) {
        return in_array($user->email, [
            'taylor@laravel.com',
        ]);
    });
}
```

이 코드를 다음과 같이 수정합니다.

```php
<?php

protected function gate()
{
    Gate::define('viewNova', function ($user) {
        return true;
    });
}
```

이 코드는 사용자의 속성을 이용해서 적당히 수정해서 사용할 수 있고, 예제파일처럼 사용자의 이메일을 지정할 수도 있습니다. 제가 알려드린 코드는 모든 사용자에게 노바 페이지를 보여주게 만듭니다.

## 배포 후 리소스 파일 처리

라라벨 노바는 어드민 대시보드이기 때문에 개발 도중에 실데이터를 미리 입력해 놓는 경우가 있을텐데요, 바로 이미지입니다.

원래대로라면 코드를 배포한 후 운영자가 이미지를 올려야 겠지만, 저처럼 실데이터를 이용해서 미리 데이터를 등록해 두는 개발자라면 다음과 같이 rsync를 이용해서 업로드한 이미지를 실서버에 올릴 수 있습니다.

```bash
rsync -arvuz --exclude=".gitignore" /local_project_path/storage/app/public/ sshid@1.2.3.4:/server_path/current/storage/app/public
```

전 이 파일을 `sync_upload_image.sh`이라는 이름의 파일로 저장해 놓고, 프로젝트 런칭 전까지 로컬의 이미지를 서버에 전송합니다. 물론 `.gitignore`에는 추가해 놓고 말이죠.

## 배포 요약

실제로 라라벨 노바 배포에 있어서의 문제는 이 정도입니다. [라라벨 노바의 Install 문서](https://nova.laravel.com/docs/installation.html)를 꼼꼼히 확인했다면 하지 않아도 될 시행착오가 대부분이었습니다.\(라라벨은 정말 공문을 세세히 봐야 하는 것 같습니다.\)

전체적인 프로젝트 진행 순서를 알려드리며 여기서 마치겠습니다.

1. `valet`을 이용하여 개발환경을 구성하고 `laravel new` 커맨드로 프로젝트 시작
2. 라라벨 노바를 구입하고, 컴포져를 이용해서 설치 후 개발 시작
3. 깃헙 팀 무료버전에 Private 저장소를 만들고, 개발된 코드를 푸쉬\(`.env`의 배포버전인 `.env.production`은 별도로 제작 후 함께 푸쉬\)
4. `Envoyer`에 깃헙 주소를 등록하고, 깃헙의 권한을 조정
5. `Envoyer`로 배포한 후 `Slack`으로 배포 알람 세팅
6. \(런칭 전까지\) 깃헙 푸쉬 시 자동 배포 옵션 활성화
