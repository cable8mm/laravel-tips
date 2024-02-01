# PHP 스크립트를 php 명령어 없이 사용하는 법

이번엔 PHP를 php 명령어 지정없이 실행하는 방법을 공유합니다. 이미 쉘스크립트로 php를 활용하는 개발자들이 늘어나는 추세(?)이기도 하니까요.

PHP 코드를 실행하는 전통적인 방법은 이렇습니다.

```php
<?php

echo 'run php script!' . PHP_EOL;
```

```bash
➜ php example.php
run php script!
```

만약에 누군가의 요청으로 스크립트를 만들었고, 그 스크립트를 저렇게 쓰면 된다고 할 때 PHP 개발자가 아니라면 거부감이 생길 수도 있습니다. 게다가 PHP를 모든 분들이 전역으로 세팅하지는 않기도 하고요.

대부분의 개발환경에 PHP가 설치되어 있다는걸 감안한다면 스크립트 가장 윗줄에 `#!/usr/bin/env php`을 넣는 것 만으로도 php와 확장자를 쓰지 않아도 됩니다.

```php
#!/usr/bin/env php
<?php

echo 'run php script!' . PHP_EOL;
```

```bash
➜ chmod 755 example.php; mv example.php example;
➜ ./example
run php script!
```

## \#\!(Shabang)

가장 먼저 나오는 `#!`은 Sharp(#)+Bang(!)의 합성어로 [shabang, sha-bang, hashbang, pound-bang, hash-pling 등](<https://en.wikipedia.org/wiki/Shebang_(Unix)>)으로 읽습니다.

`#!`은 파일 첫줄에 사용되며, 프로그램 로더를 정하고 인터프리터를 지정합니다. 보통은 파일을 실행시킬 때 실행하는 사람이 프로그램을 지정하는 것과는 다르게, 파일 제작자가 지정합니다.

하지만 php의 실행 경로는 시스템마다 다르기 때문에 다른 무언가가 필요할 겁니다.

## /usr/bin/env php

`/usr/bin/env` 명령어는 환경변수를 출력해 주는 프로그램으로 대부분의 시스템에 빌트인 되어 있습니다.

커맨드에 `env` 명령어를 실행시켜 보면 현재의 쉘의 각종 정보와 디렉토리 정보들이 나옵니다.

`env`의 또 다른 기능인 `env + 언어`를 사용하면 그 언어의 실행파일을 실행할 수 있습니다.

```sh
➜ env php --version
PHP 7.3.14 (cli) (built: Jan 24 2020 03:04:31) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.3.14, Copyright (c) 1998-2018 Zend Technologies
    with Xdebug v2.9.2, Copyright (c) 2002-2020, by Derick Rethans
    with Zend OPcache v7.3.14, Copyright (c) 1999-2018, by Zend Technologies
```

### PHP에서 쉘 프로그래밍

흔히 사용하는 bash shell은 디버깅이 쉽지 않아서 코드 작성에 어려움이 있습니다. 그 이유로 파이썬으로 작성하는 개발자도 많아지고 있는데요, PHP로 쉘을 작성하면 어떨까요?

사용하는 개발자는 모르게 말이죠 ^^
