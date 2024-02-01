# 라라벨이 애용하는 ::class 톺아보기

라라벨을 처음 개발할 때 라라벨 공식 문서를 보면 `config/app.php`을 보면 수많은 ::class를 볼 수 있습니다. 반면 엘로퀀트 모델에서는 같은 내용을 홑따옴표를 사용합니다.

```php
<?php

'providers' => [
    /*
    * Laravel Framework Service Providers...
    */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    ...
```

```php
<?php

public function phone()
{
    return $this->hasOne('App\Phone');
}
```

물론 위의 `'App\Phone'`을 `Phone::class`로 바꾸어도 같으며, 개발자 성향에 따라서 따옴표를 쓰거나 ::class를 쓸 수 있습니다.

이 둘은 완벽히 같지만, 그래도 다른 무언가가 있을 것 같습니다.

[php.net 공식홈페이지](https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class)에서 워낙 간단하게 설명이 되어서 가볍게 관련 내용을 정리 해 보겠습니다.

## ::class의 타입

`::class`는 php.net 공식문서에는 클래스 이름을 해석할 때 사용된다고 되어 있습니다. 그리고, 출력은 string 타입의 FQN(fully qualified name)이며, `ClassName::class` 형식이라고 되어 있고요.

타입을 알아보기 위해서 간단한 코드를 실행 해 봅시다.

```php
<?php

var_dump(NumberFormat::class);

// output
// string(12) "NumberFormat"
```

공식문서의 설명대로 잘 나오네요.

말하자면, 'NumberFormat'이라는 문자열과 NumberFormat::class는 완벽히 같습니다! 네임스페이스가 있다면 네임스페이스까지 나옵니다.

## Try\

그럼 몇가지 실험을 해 보죠.

```php
<?php

namespace Cable8mm\Example;

var_dump(SampleClass::class);

// output
// string(28) "Cable8mm\Example\SampleClass"
```

실제 SampleClass는 없습니다. ::class는 실제로 클래스가 있는지를 체크하지 않는다는 것을 알 수 있습니다.

```php
<?php

namespace Cable8mm\Example;

class A {
    public static function a() : string
    {
        return __CLASS__;
    }

    public static function b() : string
    {
        return self::class;
    }

}

echo A::a() . PHP_EOL;
echo A::b() . PHP_EOL;

// output
// Cable8mm\Example\A
// Cable8mm\Example\A
```

PHP에는 클래스 이름을 얻을 수 있는 또 다른 마법 상수(magic constant)인 __CLASS__도 같은 문자열을 출력해 줍니다.

## 상속과 trait에선?

조금만 더 깊게 들어가 봅시다.

상속 :

```php
<?php

namespace Cable8mm\Example;

class A {
    public function a() : string
    {
        return __CLASS__ . PHP_EOL 
        . self::class . PHP_EOL 
        . static::class . PHP_EOL ;
    }
}

class B extends A {}

echo (new A)->a() . PHP_EOL;
echo PHP_EOL;
echo (new B)->a() . PHP_EOL;

// output
// Cable8mm\Example\A
// Cable8mm\Example\A
// Cable8mm\Example\A
//
// Cable8mm\Example\A
// Cable8mm\Example\A
// Cable8mm\Example\B
```

상속을 받은 클래스에서 static::class만 B 클래스를 리턴하는군요.

Traits:

```php
<?php

namespace Cable8mm\Example;

trait T {
    public function a() : string
    {
        return __TRAIT__ . PHP_EOL 
            . __CLASS__ . PHP_EOL 
            . self::class . PHP_EOL 
            . static::class;
    }
}

class A {
    use T;
}

echo (new A)->a() . PHP_EOL;

// output
// Cable8mm\Example\T
// Cable8mm\Example\A
// Cable8mm\Example\A
// Cable8mm\Example\A
```

trait에서도 잘 작동하는 것을 알 수 있습니다.

## 다시 라라벨로

라라벨의 코드를 리뷰 해 보면 클래스의 FQN을 얻기 위해서 거의 모든 코드에는 `::class`를 사용하고 있습니다.

반면 엘로퀀트 모델의 관계를 정의할 때에는 string을 직접 넣도록 공식 문서의 가이드에 나와 있습니다.

왜 그런걸까요?

제 생각에는 보통 관계를 `::class`를 넣을 때는 IDE에서 자동으로 use를 넣어주고, 인자에는 클래스 이름만 들어가게 됩니다. 이렇게 말이죠.

```php
<?php

use App\Phone;

public function phone()
{
    return $this->hasOne(Phone::class);
}
```

문제는 모델의 클래스 이름과 동일한 이름의 클래스 이름이 존재할 경우 IDE에서 넣어주는 클래스 경로가 잘못 들어갈 가능성이 있다는 사실입니다.

특히 라라벨의 규격상 모델의 이름과 리소스(JsonResource)의 이름은 공문조차도 동일하게 가이드되어 있습니다.

전 네이밍 규칙은 같은 프로젝트에서는 하나만 존재해야 한다고 생각합니다.

지금까지 ::class를 이용해서 관계를 정의했는데요, 다음 프로젝트에서는 어떻게 할지 약간의 고민을 하고 있습니다.

오늘도 즐거운 라라벨 생활 되시기 바랍니다.
