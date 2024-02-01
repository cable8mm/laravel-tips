---
description: 라라벨 생활의 소소한 이야기와 팁
---

This repository have merged https://github.com/cable8mm/stack. Please to visit new repository or https://stack.palgle.com

It have been archiving forever. Thank you for all.

이 레포지토리는 https://github.com/cable8mm/stack 와 머지되었습니다. 부디 새로운 레포지토리나 https://stack.palgle.com 으로 오셔서 새로운 콘텐츠를 즐기시기 바랍니다.

본 레포지토리는 아카이빙 됩니다. 갑사합니다.

# Introduction

For read in convenient : [Gitbook](https://stack.palgle.com)

```php
<?php

Route::redirect('/')->away('https://stack.palgle.com');
```

For edit or write articles : [Github](https://github.com/cable8mm/stack)

```php
<?php

Route::middleware('auth:love-laravel')->group(function () {
    Route::redirect('/question', '/cable8mm/stack/issues');
    Route::redirect('/edit-or-write', '/cable8mm/stack/pulls');
});
```
