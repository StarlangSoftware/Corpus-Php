<?php

namespace olcaytaner\Corpus;

use olcaytaner\Dictionary\Language\TurkishLanguage;

class TurkishSplitter extends SentenceSplitter
{

    /**
     * Returns shortcut words in Turkish language.
     * @return array Shortcut words in Turkish language.
     */
    protected function shortcuts(): array
    {
        return ["alb", "bnb", "bkz", "bşk", "co", "dr", "dç", "der", "em", "gn",
            "hz", "kd", "kur", "kuv", "ltd", "md", "mr", "mö", "muh", "müh",
            "no", "öğr", "op", "opr", "org", "sf", "tuğ", "uzm", "vb", "vd",
            "yön", "yrb", "yrd", "üniv", "fak", "prof", "dz", "yd", "krm", "gen",
            "pte", "p", "av", "II", "III", "IV", "VI", "VII", "VIII", "IX",
            "X", "XI", "XII", "XIII", "XIV", "XV", "XVI", "XVII", "XVIII", "XIX",
            "XX", "tuğa", "plt", "tğm", "tic", "srv", "bl", "dipl", "not", "min",
            "cul", "san", "rzv", "or", "kor", "tüm", "st", "sn", "fr", "pl",
            "ka", "tk", "ko", "vs", "yard", "bknz", "doç", "gör", "müz", "oyn",
            "m", "s", "kr", "ms", "hv", "uz", "re", "ph", "mc", "ed",
            "km", "yb", "bk", "jr", "bn", "os", "mrs", "bld", "sen", "alm",
            "sir", "ord", "dir", "yay", "man", "brm", "edt", "dec", "mah", "cad",
            "vol", "kom", "sok", "apt", "elk", "mad", "ort", "cap", "ste", "exc",
            "ef"];
    }

    /**
     * Returns Turkish lowercase letters.
     * @return string Turkish lowercase letters.
     */
    protected function lowerCaseLetters(): string
    {
        return TurkishLanguage::$LOWERCASE_LETTERS;
    }

    /**
     * Returns Turkish UPPERCASE letters.
     * @return string Turkish UPPERCASE letters.
     */
    protected function upperCaseLetters(): string
    {
        return TurkishLanguage::$UPPERCASE_LETTERS;
    }
}