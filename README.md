---
description: 라라벨 생활의 소소한 이야기와 팁
---

This repository have merged https://github.com/cable8mm/stack. Please to visit new repository or https://stack.palgle.com

It have been archiving forever. Thank you for all.

이 레포지토리는 https://github.com/cable8mm/stack 와 머지되었습니다. 부디 새로운 레포지토리나 https://stack.palgle.com 으로 오셔서 새로운 콘텐츠를 즐기시기 바랍니다.

본 레포지토리는 아카이빙 됩니다. 갑사합니다.

# Introduction

[![Markdown Validate](https://github.com/cable8mm/laravel-tips/actions/workflows/markdown-validate.yml/badge.svg)](https://github.com/cable8mm/laravel-tips/actions/workflows/markdown-validate.yml)
[![Check Markdown links](https://github.com/cable8mm/laravel-tips/actions/workflows/markdown-link-check.yml/badge.svg)](https://github.com/cable8mm/laravel-tips/actions/workflows/markdown-link-check.yml)
![GitHub Release](https://img.shields.io/github/v/release/cable8mm/laravel-tips)
![GitHub commits since latest release](https://img.shields.io/github/commits-since/cable8mm/laravel-tips/latest)
![GitHub contributors](https://img.shields.io/github/contributors/cable8mm/laravel-tips)

For read in convenient : [Gitbook](https://laravel.palgle.com)

```php
<?php

Route::redirect('/')->away('https://stack.palgle.com');
```

For edit or write articles : [Github](https://github.com/cable8mm/laravel-tips)

```php
<?php

Route::middleware('auth:love-laravel')->group(function () {
    Route::redirect('/question', '/cable8mm/stack/issues');
    Route::redirect('/edit-or-write', '/cable8mm/stack/pulls');
});
```

## How to contribute

```sh
git clone https://github.com/cable8mm/stack.git

cd stack
```

Before writing, you should make new branch.

```sh
git checkout -b <new-branch>
```

You have written for a while, then you can push your contents.

```sh
git push
```

Finally you should make PR.

After we have reviewed your contents, it would be merged and published.

## How to lint markdown style

```sh
brew install markdownlint-cli

find . -name \*.md -print0 | xargs -0 -n1 markdownlint
```

```sh
npm install -g markdown-link-check

find . -name \*.md -print0 | xargs -0 -n1 markdown-link-check
```
