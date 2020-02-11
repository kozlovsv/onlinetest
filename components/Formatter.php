<?php

namespace app\components;

use DateTime;
use Yii;

/**
 * Компонента форматирования данных
 */
class Formatter extends \yii\i18n\Formatter
{
    const MAX_MONEY_BIT = 15;

    /**
     * Как число по разрядам
     * @param $value
     * @return string
     */
    public function asNumber($value)
    {
        return $this->asDecimal($value, 0);
    }

    /**
     * Как будто денюжка
     * @param $value
     * @param int $decimals
     * @return mixed
     */
    public function asMoney($value, $decimals = 2)
    {
        return $this->asDecimal($value, $decimals);
    }

    /**
     * Число прописью (alias for num2str)
     * @param $num
     * @return string
     */
    public function numberAsCursive($num)
    {
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ];
        $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
        $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
        $unit = [ // Units
            ['', '', '', 1],
            ['', '', '', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['миллиард', 'милиарда', 'миллиардов', 0],
            ['триллион', 'триллиона', 'триллионов', 0],
        ];
        if (intval($num) == 0) return $nul;
        $format = '%0' . self::MAX_MONEY_BIT . '.0f';
        $rub = sprintf($format, floatval($num));
        $out = [];
        foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit) - $uk - 1; // unit key
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
            else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) /** @noinspection TranslationsCorrectnessInspection */ $out[] = Yii::t('app', "{val, plural, one{{$unit[$uk][0]}} few{{$unit[$uk][1]}} other{{$unit[$uk][2]}}}", ['val' => $v]);
        } //foreach
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    /**
     * Число прописью
     * @param float $value
     * @param int $decimals
     * @return string
     */
    public function moneyAsCursive($value, $decimals = 2)
    {
        $number = $this->asMoney($value, $decimals);
        if ($decimals > 0) {
            $format = '%0' . (self::MAX_MONEY_BIT + 3) . '.2f';
            list(, $kop) = explode('.', sprintf($format, floatval($value)));
            /** @noinspection TranslationsCorrectnessInspection */
            $kopText = Yii::t('app', '{val, plural, one{копейка} few{копейки} other{копеек}}', ['val' => $kop]);
        } else {
            $kop = '00';
            $kopText = 'копеек';
        }
        $numberAsCursive = $this->numberAsCursive($value);
        /** @noinspection TranslationsCorrectnessInspection */
        $rubText = Yii::t('app', '{val, plural, one{рубль} few{рубля} other{рублей}}', ['val' => $value]);
        return  "$number ($numberAsCursive $rubText $kop $kopText) $rubText";
    }

    /**
     * @param string $value
     * @return array|DateTime
     */
    public function dateTimeFromString($value) {
        return parent::normalizeDatetimeValue($value);
    }
}