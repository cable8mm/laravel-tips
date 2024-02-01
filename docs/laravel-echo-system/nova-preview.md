# 라라벨 노바 훑어보기

라라벨 노바는 라라벨의 제작자인 테일러 오트웰\(Taylor Otwell\)이 직접 제작한 라라벨 기반의 어드민 대시보드입니다. 여타 어드민과 다른점은 라라벨 기반 위에 어드민 대시보드를 올린것이 아니라 패키지 형태를 취하기 때문에 현재 사용하는 웹서비스에 그대로 올릴 수 있다는 것이 다른 제품과 차별점이라고 할 수 있습니다.

전 지금까지 서비스를 제작할 때 프론트를 라라벨로, 운영툴을 케이크PHP를 사용해 왔습니다. 케이크PHP는 Rapid를 지향하는 PHP 프레임워크 중 가장 오래되었고 그만큼 완성도가 높습니다. 하지만, 프론트와 어드민을 별도의 프로젝트로 제작할 때 생기는 피로감이 존재합니다.

1. 어느 한 쪽에서 제작한 코드를 다른 프로젝트에서 사용해야 할 때 공통 코드가 두번 들어갈 수 있다.
2. 어느 한 쪽의 큐/잡 프로세스를 관리할 통일된 개발 방법론을 찾기가 힘들다.

1번의 경우에 전 Composer 기반으로 코드를 작성하는 것으로 해결하고 있지만, 2번의 경우는 해결 방법이 딱히 떠오르지 않고, 큐/잡을 위한 별도의 프로젝트를 만들기에는 부담이 컸습니다.

이를 해결하기 위해서 라라벨 노바를 적극적으로 활용하기 위한 테스트 프로젝트를 진행했습니다.

## 라라벨 노바의 구조

루비온레일즈 류의 MVC 프레임워크에 익숙하신 분들은 라라벨에서 제공하는 뷰 쪽이 부실하게 보일지도 모릅니다. 케이크PHP만 하더라도 뷰 쪽에서 폼의 라벨과 벨리데이트, 그리고 에러메시지 처리를 위한 코드는 단 한줄입니다.

```php
<?php echo $this->Form->input('field_name'); ?>
```

그리고, 이 코드는 스케폴딩이라는 커맨드로 생성되기 때문에 어드민을 제작할 때 전 뷰에서는 검색 처리만 별도로 작업합니다. 밸리데이션과 에러메시지 코드는 모두 모델에 들어가 있습니다.

라라벨은 모델과 밸리데이션이 분리되어 있기 때문에 이런 식의 구조를 만들기가 어렵고, 라라벨 구조와 맞지 않는 느낌도 듭니다. 그것은 뷰 스케폴딩 프로젝트가 그리 활성화 되지 않았다는게 이를 반증한다고 생각합니다.

라라벨 노바는 이 부분을 이렇게 해결하고 있습니다.

1. 콘트롤러 없음
2. 뷰 없음
3. 모델은 기존에 제작된 것을 이용하거나 artisan 커맨드로 나온 그대로 작동

이런 마법같은 일을 하는건 바로 리소스\(Resource\)입니다.

라라벨에서 리소스는 모델과 뷰를 연결하는 매우 강력한 기능을 제공합니다. 라라벨에서 API 코드를 작성하신 분이라면 뷰 코드 없이 리소스만으로 처리가 된다는 사실에 놀란 분들이 있을 것입니다.

라라벨 노바도 마찬가지로 리소스에 모델과 콘트롤러, 뷰에 필요한 것들을 정의하는 것만으로 어드민 대시보드를 제작할 수 있게 디자인 되었습니다. 이해가 쉽도록 라라벨 노바에서 제공하는 빌트인 리소스인 User 리소스를 몇개로 나누어 보겠습니다. 글이 다소 길어지겠지만, 리소스 전체 코드를 리뷰하는 것이 도움이 될 것이라 생각합니다.

```php
<?php
class User extends Resource
{
    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\\User';

    /**
    * The single value that should be used to represent the resource when being displayed.
    *
    * @var string
    */
    public static $title = 'name';

    /**
    * The columns that should be searched.
    *
    * @var array
    */
    public static $search = [
        'id', 'name', 'email',
    ];

...
```

User 리소스에서는 모델을 지정하고, 타이틀과 검색해야 되는 필드를 지정합니다. 이 것만으로 라라벨 노바는 앱에 타이틀과 검색창이 표현됩니다.

```php
<?php

/**
* Get the fields displayed by the resource.
*
* @param  \Illuminate\Http\Request  $request
* @return array
*/
public function fields(Request $request)
{
    return [
        ID::make()->sortable(),

        Gravatar::make(),

        Text::make('Name')
            ->sortable()
            ->rules('required', 'max:255'),

        Text::make('Email')
            ->sortable()
            ->rules('required', 'email', 'max:254')
            ->creationRules('unique:users,email')
            ->updateRules('unique:users,email,{{resourceId}}'),

        Password::make('Password')
            ->onlyOnForms()
            ->creationRules('required', 'string', 'min:8')
            ->updateRules('nullable', 'string', 'min:8'),
    ];
}
```

fields 메쏘드는 화면에 노출할 필드와 폼 타입 그리고 밸리데이션 등을 선언합니다.

예를 들어서,

```php
<?php

Text::make('Name')
    ->sortable()
    ->rules('required', 'max:255'),
```

이 코드는 HTML에서 폼타입으로 text를 사용하고, 필수입력되어야 하며, 최대 사이즈가 255자이어야 하며, 소팅기능을 제공합니다. 물론 필드 이름은 name이 됩니다.

이 코드만으로 라라벨 노바는 이 규칙에 맞는 CRUD\(Create Read Update Delete, 즉 생성 읽기 갱신 삭제를 의미\)를 만들어 줍니다.

라라벨 노바로 CRUD만을 제작한다면 여기까지만 해도 충분히 사용할 수 있지만, 세상은 쉬운 일이 없더군요. 그래서, 라라벨 노바는 추가로 다음의 메쏘드를 제공하고 있습니다.

```php
<?php

/**
* Get the cards available for the request.
*
* @param  \Illuminate\Http\Request  $request
* @return array
*/
public function cards(Request $request)
{
    return [];
}

/**
* Get the filters available for the resource.
*
* @param  \Illuminate\Http\Request  $request
* @return array
*/
public function filters(Request $request)
{
    return [];
}

/**
* Get the lenses available for the resource.
*
* @param  \Illuminate\Http\Request  $request
* @return array
*/
public function lenses(Request $request)
{
    return [];
}

/**
* Get the actions available for the resource.
*
* @param  \Illuminate\Http\Request  $request
* @return array
*/
public function actions(Request $request)
{
    return [];
}
```

필터와 액션은 쉽게 검색과 버튼을 만들 수 있게 해 줍니다. 이 부분은 간단하지만, 이 글의 범위 밖에 있기 때문에 자세히 설명하지는 않겠습니다.

카드와 렌즈는 CRUD 이외에 별도의 코드로 작성된 노바 카드 패키지나 빌트인 된 기능 예를 들어서 오늘 가입자 수 같은 기능을 보여주는데 사용됩니다.

## 라라벨 노바 개발

라라벨 노바의 구조는 라라벨처럼 프레임워크 안에서 개발하게 되어 있지 않습니다. 여러분이 개발자라면 리소스의 선언만으로 모든 기능을 제공할 수 있어야 합니다. 그렇다면, 라라벨 노바에서 제공하지 않는 기능을 구현해야 할 때는 어떻게 해야 할까요?

라라벨 노바에서의 개발은 패키지를 만들어서 composer로 넣고, 위에서 설명한 리소스의 필터, 액션, 카드, 렌즈 중 하나를 이용해서 인테그레이션해야 합니다. 공식 사이트는 아니지만, [노바 패키지](https://novapackages.com/) 라는 홈페이지에서는 이미 상당한 규모의 패키지를 무료로 제공하고 있습니다.

## 테마나 디자인 수정

당연히 테마나 디자인은 수정되는거 아냐? 라고 생각하실 수 있겠지만, 물론 쉽게 되지 않습니다. 라라벨 노바는 이 부분 마져도 패키지로 해결을 하고 있습니다.\(라라벨 노바 1.0에선 수정 자체가 되지 않았지만, 3.0에 와서 해법을 찾은 느낌입니다.\)

노바 패키지에는 기능 뿐만이 아니라 테마 패키지도 올라가 있습니다. 테마라고 해서 거창한 것은 아니고, 몇가지 간단한 css를 미리 선언해 놓았을 뿐입니다.

만약 노바 패키지에 원하는 테마가 없거나 테마를 수정해야 할 때는 어떻게 할까요?

라라벨 노바 공식 문서를 보면 artisan 커맨드를 이용해서 제작된다고 되어 있는데, 사실 이보다 매우 복잡합니다. 라라벨 노바의 테마는 자체적으로 제작한 테마 코드를 패키지로 만들어서 공유할 수 있는 기능 전체를 제공하고 있습니다. 아마 추후에 소개할 기회가 있을 것으로 생각합니다.

## 사용해? 말어?

라라벨 노바는 처음 사용한 분들은 "와우~! 선언만으로 이게 된다고?" 하고 놀라시는 것 같습니다. 하지만, 오래지 않아 내 코드를 어떻게 넣어야 하는지에 대해서 혼란스러워 하게 됩니다. 심지어 좌측 상단의 로고조차 어떻게 수정해야 하는지 알 수가 없습니다.

만약 CRUD와 검색 그리고 필터 기능만 있어도 된다면 라라벨 노바는 최선의 선택입니다. 그리고 라라벨 노바에서 사용하는 Trix 리치에디터는 투박하긴 하지만 첨부파일들을 엘레강스하게 처리해 줍니다.

반면, 어드민에 추가적인 기능이 노바 패키지 만으로 해결되지 않는다면, 라라벨 노바의 도입에 신중할 필요가 있습니다. 라라벨 노바 패키지를 제작한다는건 그 나름의 학습이 되어야 한다는걸 의미합니다.

잊지 마세요. 여러분은 단 한줄의 콘트롤러 코드도, 뷰 코드도 쓸 수 없습니다. 오롯이 리소스파일과 패키지만으로 개발을 해야 한다는걸 말이죠.

다음 글에서는 이번 글을 더 쉽게 파악할 수 있도록 스크린샷 위주로 구성해 보겠습니다.
