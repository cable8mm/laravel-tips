# Knowledge Acquisition

## phpunit에서 IDE의 자동완성기능이 작동하지 않아요

`phpunit`이 설치가 안되어 있다면:

```sh
composer require phpunit/phpunit --dev
```

`phpunit`이 설치가 되어 있다면:

```sh
composer remove phpunit/phpunit --dev
composer require phpunit/phpunit --dev
```

## iOS 13에서 iPad WKWebview의 유저에이젼트가 Mac으로 표기됩니다

`WKWebpagePreferences`의 [preferredContentMode](https://developer.apple.com/documentation/webkit/wkwebpagepreferences/3194426-preferredcontentmode?language=objc)를 `.mobile`로 설정.

## 맥 업데이트 후 "gpg: WARNING: no command supplied"

맥을 업데이트하고 `gpg: WARNING: no command supplied` 워닝이 나오고 푸쉬가 되지 않을 때에는 이렇게 해 본다.

```bash
git config --global gpg.program "/usr/local/bin/gpg"
```
