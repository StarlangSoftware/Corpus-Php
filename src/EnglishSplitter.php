<?php

namespace olcaytaner\Corpus;

use olcaytaner\Dictionary\Language\EnglishLanguage;

class EnglishSplitter extends SentenceSplitter
{

    /**
     * Returns shortcut words in English language.
     * @return array Shortcut words in English language.
     */
    protected function shortcuts(): array
    {
        return ["dr", "prof", "org", "II", "III", "IV", "VI", "VII", "VIII", "IX",
            "X", "XI", "XII", "XIII", "XIV", "XV", "XVI", "XVII", "XVIII", "XIX",
            "XX", "min", "km", "jr", "mrs", "sir"];
    }

    /**
     * Returns English lowercase letters.
     * @return string English lowercase letters.
     */
    protected function lowerCaseLetters(): string
    {
        return EnglishLanguage::$LOWERCASE_LETTERS;
    }

    /**
     * Returns English UPPERCASE letters.
     * @return string English UPPERCASE letters.
     */
    protected function upperCaseLetters(): string
    {
        return EnglishLanguage::$UPPERCASE_LETTERS;
    }
}