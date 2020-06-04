# Knowledge Acquisition

## phpunit에서 IDE의 자동완성기능이 작동하지 않아요.

phpunit이 설치가 안되어 있다면:

```sh
composer require phpunit/phpunit --dev
```

phpunit이 설치가 되어 있다면:

```sh
composer remove phpunit/phpunit --dev
composer require phpunit/phpunit --dev
```