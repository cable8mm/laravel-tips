---
description: 라라벨 생활의 소소한 이야기와 팁
---

# Introduction

For read in convenient :

```
$ return redirect('https://cable8mm.gitbook.com');
```

For edit or write articles :

```php
<?php
if(is_question()) {
    $client->send('issue')->to('cable8mm/laravel-tips');
    return;
}

$client->send('PR')->to('cable8mm/laravel-tips');
```
