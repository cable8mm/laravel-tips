---
description: 라라벨 생활의 소소한 이야기와 팁
---

# Introduction

For read in convenient : [Gitbook](https://laravel.palgle.com)

```php
<?php

Route::redirect('/')->away('https://laravel.palgle.com');
```

For edit or write articles : [Github](https://github.com/cable8mm/laravel-tips)

```php
<?php

Route::middleware('auth:love-laravel')->group(function () {
    Route::redirect('/question', '/cable8mm/laravel-tips/issues');
    Route::redirect('/edit-or-write', '/cable8mm/laravel-tips/pulls');
});
```

