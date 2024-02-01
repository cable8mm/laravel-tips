<?php

// NumberFormatter class required php intl extention
// Symfony polyfill(symfony/intl) replace intl, but it is limited to the locale **only** "en"

$number = 12345.52;
$locale = 'ko_KR';

$predefinedConstants = [
    '\NumberFormatter::PATTERN_DECIMAL' => \NumberFormatter::PATTERN_DECIMAL,
    '\NumberFormatter::DECIMAL' => \NumberFormatter::DECIMAL,
    '\NumberFormatter::CURRENCY' => \NumberFormatter::CURRENCY,
    '\NumberFormatter::PERCENT' => \NumberFormatter::PERCENT,
    '\NumberFormatter::SCIENTIFIC' => \NumberFormatter::SCIENTIFIC,
    '\NumberFormatter::SPELLOUT' => \NumberFormatter::SPELLOUT,
    '\NumberFormatter::ORDINAL' => \NumberFormatter::ORDINAL,
    '\NumberFormatter::DURATION' => \NumberFormatter::DURATION,
    //    '\NumberFormatter::PATTERN_RULEBASED' => \NumberFormatter::PATTERN_RULEBASED,      // required rule
    //    '\NumberFormatter::CURRENCY_ACCOUNTING' => \NumberFormatter::CURRENCY_ACCOUNTING,    // php 7.4.1
    '\NumberFormatter::DEFAULT_STYLE' => \NumberFormatter::DEFAULT_STYLE,
    '\NumberFormatter::IGNORE' => \NumberFormatter::IGNORE,
    '\NumberFormatter::TYPE_DEFAULT' => \NumberFormatter::TYPE_DEFAULT,
    '\NumberFormatter::TYPE_INT32' => \NumberFormatter::TYPE_INT32,
    '\NumberFormatter::TYPE_INT64' => \NumberFormatter::TYPE_INT64,
    '\NumberFormatter::TYPE_DOUBLE' => \NumberFormatter::TYPE_DOUBLE,
    '\NumberFormatter::TYPE_CURRENCY' => \NumberFormatter::TYPE_CURRENCY,
    '\NumberFormatter::PARSE_INT_ONLY' => \NumberFormatter::PARSE_INT_ONLY,
    '\NumberFormatter::GROUPING_USED' => \NumberFormatter::GROUPING_USED,
    '\NumberFormatter::DECIMAL_ALWAYS_SHOWN' => \NumberFormatter::DECIMAL_ALWAYS_SHOWN,
    '\NumberFormatter::MAX_INTEGER_DIGITS' => \NumberFormatter::MAX_INTEGER_DIGITS,
    '\NumberFormatter::MIN_INTEGER_DIGITS' => \NumberFormatter::MIN_INTEGER_DIGITS,
    '\NumberFormatter::INTEGER_DIGITS' => \NumberFormatter::INTEGER_DIGITS,
    '\NumberFormatter::MAX_FRACTION_DIGITS' => \NumberFormatter::MAX_FRACTION_DIGITS,
    '\NumberFormatter::MIN_FRACTION_DIGITS' => \NumberFormatter::MIN_FRACTION_DIGITS,
    '\NumberFormatter::FRACTION_DIGITS' => \NumberFormatter::FRACTION_DIGITS,
    //    '\NumberFormatter::MULTIPLIER' => \NumberFormatter::MULTIPLIER,
    '\NumberFormatter::GROUPING_SIZE' => \NumberFormatter::GROUPING_SIZE,
    '\NumberFormatter::ROUNDING_MODE' => \NumberFormatter::ROUNDING_MODE,
    '\NumberFormatter::ROUNDING_INCREMENT' => \NumberFormatter::ROUNDING_INCREMENT,
    '\NumberFormatter::FORMAT_WIDTH' => \NumberFormatter::FORMAT_WIDTH,
    '\NumberFormatter::PADDING_POSITION' => \NumberFormatter::PADDING_POSITION,
    '\NumberFormatter::SECONDARY_GROUPING_SIZE' => \NumberFormatter::SECONDARY_GROUPING_SIZE,
    '\NumberFormatter::SIGNIFICANT_DIGITS_USED' => \NumberFormatter::SIGNIFICANT_DIGITS_USED,
    '\NumberFormatter::MIN_SIGNIFICANT_DIGITS' => \NumberFormatter::MIN_SIGNIFICANT_DIGITS,
    '\NumberFormatter::MAX_SIGNIFICANT_DIGITS' => \NumberFormatter::MAX_SIGNIFICANT_DIGITS,
    //    '\NumberFormatter::LENIENT_PARSE' => \NumberFormatter::LENIENT_PARSE,
    '\NumberFormatter::POSITIVE_PREFIX' => \NumberFormatter::POSITIVE_PREFIX,
    '\NumberFormatter::POSITIVE_SUFFIX' => \NumberFormatter::POSITIVE_SUFFIX,
    '\NumberFormatter::NEGATIVE_PREFIX' => \NumberFormatter::NEGATIVE_PREFIX,
    '\NumberFormatter::NEGATIVE_SUFFIX' => \NumberFormatter::NEGATIVE_SUFFIX,
    '\NumberFormatter::PADDING_CHARACTER' => \NumberFormatter::PADDING_CHARACTER,
    '\NumberFormatter::CURRENCY_CODE' => \NumberFormatter::CURRENCY_CODE,
    '\NumberFormatter::DEFAULT_RULESET' => \NumberFormatter::DEFAULT_RULESET,
    '\NumberFormatter::PUBLIC_RULESETS' => \NumberFormatter::PUBLIC_RULESETS,
    '\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL' => \NumberFormatter::DECIMAL_SEPARATOR_SYMBOL,
    '\NumberFormatter::GROUPING_SEPARATOR_SYMBOL' => \NumberFormatter::GROUPING_SEPARATOR_SYMBOL,
    '\NumberFormatter::PATTERN_SEPARATOR_SYMBOL' => \NumberFormatter::PATTERN_SEPARATOR_SYMBOL,
    '\NumberFormatter::PERCENT_SYMBOL' => \NumberFormatter::PERCENT_SYMBOL,
    '\NumberFormatter::ZERO_DIGIT_SYMBOL' => \NumberFormatter::ZERO_DIGIT_SYMBOL,
    '\NumberFormatter::DIGIT_SYMBOL' => \NumberFormatter::DIGIT_SYMBOL,
    '\NumberFormatter::MINUS_SIGN_SYMBOL' => \NumberFormatter::MINUS_SIGN_SYMBOL,
    '\NumberFormatter::PLUS_SIGN_SYMBOL' => \NumberFormatter::PLUS_SIGN_SYMBOL,
    '\NumberFormatter::CURRENCY_SYMBOL' => \NumberFormatter::CURRENCY_SYMBOL,
    '\NumberFormatter::INTL_CURRENCY_SYMBOL' => \NumberFormatter::INTL_CURRENCY_SYMBOL,
    '\NumberFormatter::MONETARY_SEPARATOR_SYMBOL' => \NumberFormatter::MONETARY_SEPARATOR_SYMBOL,
    '\NumberFormatter::EXPONENTIAL_SYMBOL' => \NumberFormatter::EXPONENTIAL_SYMBOL,
    '\NumberFormatter::PERMILL_SYMBOL' => \NumberFormatter::PERMILL_SYMBOL,
    '\NumberFormatter::PAD_ESCAPE_SYMBOL' => \NumberFormatter::PAD_ESCAPE_SYMBOL,
    '\NumberFormatter::INFINITY_SYMBOL' => \NumberFormatter::INFINITY_SYMBOL,
    '\NumberFormatter::NAN_SYMBOL' => \NumberFormatter::NAN_SYMBOL,
    '\NumberFormatter::SIGNIFICANT_DIGIT_SYMBOL' => \NumberFormatter::SIGNIFICANT_DIGIT_SYMBOL,
    //    '\NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL' => \NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL,
    '\NumberFormatter::ROUND_CEILING' => \NumberFormatter::ROUND_CEILING,
    '\NumberFormatter::ROUND_DOWN' => \NumberFormatter::ROUND_DOWN,
    '\NumberFormatter::ROUND_FLOOR' => \NumberFormatter::ROUND_FLOOR,
    '\NumberFormatter::ROUND_HALFDOWN' => \NumberFormatter::ROUND_HALFDOWN,
    '\NumberFormatter::ROUND_HALFEVEN' => \NumberFormatter::ROUND_HALFEVEN,
    '\NumberFormatter::ROUND_HALFUP' => \NumberFormatter::ROUND_HALFUP,
    '\NumberFormatter::ROUND_UP' => \NumberFormatter::ROUND_UP,
    '\NumberFormatter::PAD_AFTER_PREFIX' => \NumberFormatter::PAD_AFTER_PREFIX,
    '\NumberFormatter::PAD_AFTER_SUFFIX' => \NumberFormatter::PAD_AFTER_SUFFIX,
    '\NumberFormatter::PAD_BEFORE_PREFIX' => \NumberFormatter::PAD_BEFORE_PREFIX,
    '\NumberFormatter::PAD_BEFORE_SUFFIX' => \NumberFormatter::PAD_BEFORE_SUFFIX,
];

echo '| Outout | Predefined Constant | Constant Value(int) |' . PHP_EOL . '| :--- | :---| :---: |' . PHP_EOL;

$i = 0;

foreach ($predefinedConstants as $k => $predefinedConstant) {
    // guard
    if ($predefinedConstant === NumberFormatter::INTL_CURRENCY_SYMBOL) {
        $aa = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $nf = $aa->getSymbol($predefinedConstant);
        echo pl($nf, $k, $predefinedConstant);
        continue;
    }

    if ($predefinedConstant === NumberFormatter::MIN_SIGNIFICANT_DIGITS
        or $predefinedConstant === NumberFormatter::MAX_SIGNIFICANT_DIGITS) {
        $aa = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        $nf = $aa->setAttribute($predefinedConstant, 2);
        echo pl($nf, $k, $predefinedConstant);
        continue;
    }

    $a = new \NumberFormatter($locale, $predefinedConstant);

    switch ($predefinedConstant) {
        case NumberFormatter::PATTERN_RULEBASED:
        break;
        case NumberFormatter::MULTIPLIER:
            $nf = $a->format($number, 0.1) . PHP_EOL;
        break;
        default:
            $nf = $a->format($number);
        break;
    }

    echo pl($nf, $k, $predefinedConstant);
}

function pl($nf, $k, $pc)
{
    return '| ' . sprintf('%30s', $nf) . ' | ' . $k . ' | ' . $pc . ' | ' . PHP_EOL;
}
