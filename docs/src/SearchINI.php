<?php

$maching_word = 'exception';

$inis = ini_get_all(null, true);

foreach ($inis as $k => $ini) {
    echo pl($k, $ini);
}

function pl($k, $v)
{
    global $maching_word;

    if (is_array($v)) {
        foreach ($v as $kk => $vv) {
            echo pl($k . '.' . $kk, $vv);
        }

        return;
    }

    $type = gettype($v);

    if (preg_match('/' . $maching_word . '/', $k)) {
        return '| ' . sprintf('%30s', $k) . ' | ' . $v . ' | ' . $type . ' | ' . PHP_EOL;
    }

    return;
}
