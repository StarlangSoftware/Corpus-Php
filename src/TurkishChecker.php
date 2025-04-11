<?php

namespace olcaytaner\Corpus;

use olcaytaner\Dictionary\Language\TurkishLanguage;

class TurkishChecker extends LanguageChecker
{

    private static string $SEPARATORS = "()[]{}\"'\u05F4\uFF02\u055B";
    private static string $SENTENCE_ENDERS = ".?!…";
    private static $PUNCTUATION_CHARACTERS = ",:;";

    /**
     * The isValidWord method takes an input String as a word than define all valid characters as a validCharacters String which has
     * letters (abcçdefgğhıijklmnoöprsştuüvyzABCÇDEFGĞHIİJKLMNOÖPRSŞTUÜVYZ),
     * extended language characters (âàáäãèéêëíîòóôûúqwxÂÈÉÊËÌÒÛQWX),
     * digits (0123456789),
     * separators ({@literal ()[]{}"'״＂՛}),
     * sentence enders (.?!…),
     * arithmetic chars (+-/=\*),
     * punctuation chars (,:;),
     * special-meaning chars
     * <p>
     * Then, loops through input word's each char and if a char in word does not in the validCharacters string it returns
     * false, true otherwise.
     *
     * @param string $word String to check validity.
     * @return bool true if each char in word is valid, false otherwise.
     */
    public function isValidWord(string $word): bool
    {
        $specialMeaningCharacters = "$\\_|@%#£§&><";
        $validCharacters = TurkishLanguage::$LETTERS . TurkishLanguage::$EXTENDED_LANGUAGE_CHARACTERS .
            TurkishLanguage::$DIGITS . TurkishChecker::$SEPARATORS . TurkishChecker::$SENTENCE_ENDERS .
            TurkishLanguage::$ARITHMETIC_CHARACTERS . TurkishChecker::$PUNCTUATION_CHARACTERS . $specialMeaningCharacters;
        for ($i = 0; $i < mb_strlen($word); $i++) {
            if (mb_strpos($validCharacters, mb_substr($word, $i, 1)) === false) {
                return false;
            }
        }
        return true;
    }
}