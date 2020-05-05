라라벨 forge는 서버의 세팅에서 부터 라라벨의 공식 패키지를 가장 쉽게 설치하는 서비스를 유료로 제공하며, Envoyer는 라라벨 프로젝트의 무중단 배포를 유료로 지원합니다.

저는 라라벨을 이용해서 처음 개발하는 분들에게 Sentry(어플리케이션 모니터링 플랫폼, 에러 리포팅 도구로 흔히 사용됨)와 더불어 라라벨 forge 혹은 Envoyer를 사용해 보시라고 권유합니다. 특히 Envoyer를 추천하는 이유는 세가지 입니다.

1. 깃헙을 이용한 배포
2. 무중단 배포
3. 배포 오류시 자동 혹은 수동으로 롤백

배포 스크립트를 작성하고, 슬랙으로 배포 결과를 받고, 3개의 대륙으로 부터 서버가 살아있는지를 슬랙으로 알람받는 등의 편한 기능을 제공하고 있는 Envoyer지만, 위의 세가지가 가장 중요했습니다.

Envoyer를 자세히 알기 전에 간단하지만 강력한 Envoyer의 기능을 사용하는 순서를 알려드릴 필요가 있을 것 같습니다. 이 설명은 제가 사용하는 방법일 뿐이며, 회사마다 방법은 다를 수 있으니, 참고용으로만 읽어주셔야 합니다.

## Envoyer 사용 전 준비 사항

### 원격 소스 저장소 생성 및 운영

로컬에서 라라벨로 개발한 후에 .gitignore를 [gitignore.io](http://gitignore.io)에서 아래의 키워드로 만든 후 프로젝트에 삽입합니다.(이 [링크](https://www.gitignore.io/?templates=macos,windows,laravel,composer,phpstorm,visualstudiocode)를 클릭하시면 바로 결과 화면에 접근할 수 있습니다.)

```
macos,windows,laravel,composer,phpstorm,visualstudiocode
```

그 후에 깃헙과 같은 git 원격저장소에 레포지터리를 생성한 후 로컬의 프로젝트를 깃헙에 푸쉬합니다. 이 레포지터리는 추후 Envoyer에서 배포 소스로 활용됩니다.

###
