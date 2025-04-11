<?php

namespace olcaytaner\Corpus;

use olcaytaner\Dictionary\Dictionary\Word;
use olcaytaner\Dictionary\Language\Language;
use Transliterator;

abstract class SentenceSplitter
{
    private static string $SEPARATORS = "\n()[]{}\"'’”‘“-–—\t&";
    private static string $SENTENCE_ENDERS = ".?!…";
    private static string $PUNCTUATION_CHARACTERS = ",:;‚";
    private static string $APOSTROPHES = "'’‘";
    private static string $HYPHENS = "-–—";

    protected abstract function shortcuts(): array;

    protected abstract function lowerCaseLetters(): string;

    protected abstract function upperCaseLetters(): string;

    /**
     * The listContains method has a String array shortcuts which holds the possible abbreviations that might end with a '.' but not a
     * sentence finisher word. It also takes a String as an input and loops through the shortcuts array and returns
     * true if given String has any matching item in the shortcuts array.
     *
     * @param string $currentWord String input to check.
     * @return bool true if contains any abbreviations, false otherwise.
     */
    private function listContains(string $currentWord): bool
    {
        foreach ($this->shortcuts() as $shortcut) {
            if (Transliterator::create("tr-Lower")->transliterate($currentWord) === Transliterator::create("tr-Lower")->transliterate($shortcut)) {
                return true;
            }
        }
        return false;
    }

    /**
     * The isNextCharUpperCaseOrDigit method takes a String line and an int i as inputs. First it compares each char in
     * the input line with " " and SEPARATORS ({@literal ()[]{}"'״＂՛}) and increment i by one until a mismatch or end of line.
     * <p>
     * When i equals to line length or contains one of the uppercase letters or digits it returns true, false otherwise.
     *
     * @param string $line String to check.
     * @param int $i int defining starting index.
     * @return bool true if next char is uppercase or digit, false otherwise.
     */
    private function isNextCharUpperCaseOrDigit(string $line, int $i): bool
    {
        while ($i < mb_strlen($line) && (mb_substr($line, $i, 1) == " " || str_contains(SentenceSplitter::$SEPARATORS, mb_substr($line, $i, 1)))) {
            $i++;
        }
        if ($i == mb_strlen($line) || str_contains($this->upperCaseLetters() . Language::$DIGITS . "-", mb_substr($line, $i, 1))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * The isPreviousWordUpperCase method takes a String line and an int i as inputs. First it compares each char in
     * the input line with " " and checks each char whether they are lowercase letters or one of the qxw. And decrement
     * input i by one till this condition is false.
     * <p>
     * When i equals to -1 or contains one of the uppercase letters or one of the QXW it returns true, false otherwise.
     *
     * @param string $line String to check.
     * @param int $i int defining ending index.
     * @return bool true if previous char is uppercase or one of the QXW, false otherwise.
     */
    private function isPreviousWordUpperCase(string $line, int $i): bool
    {
        while ($i >= 0 && (mb_substr($line, $i, 1) == " " || mb_substr($this->lowerCaseLetters() . "qxw", mb_substr($line, $i, 1)))) {
            $i--;
        }
        if ($i == -1 || str_contains($this->upperCaseLetters() . "QWX", mb_substr($line, $i, 1) == " ")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * The isNextCharUpperCase method takes a String line and an int i as inputs. First it compares each char in
     * the input line with " " and increment i by one until a mismatch or end of line.
     * <p>
     * When i equals to line length or contains one of the uppercase letters it returns true, false otherwise.
     *
     * @param string $line String to check.
     * @param int $i int defining starting index.
     * @return bool true if next char is uppercase, false otherwise.
     */
    private function isNextCharUpperCase(string $line, int $i): bool
    {
        while ($i < mb_strlen($line) && mb_substr($line, $i, 1) == " ") {
            $i++;
        }
        if ($i == mb_strlen($line) || str_contains($this->upperCaseLetters() . "\"\'", mb_substr($line, $i, 1))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * The isNameShortcut method takes a String word as an input. First, if the word length is 1, and $currentWord
     * contains UPPERCASE_LETTERS letters than it returns true.
     * <p>
     * Secondly, if the length of the word is 3 (i.e it is a shortcut) and it has a '.' at its 1st index and
     * $currentWord's 2nd  index is an uppercase letter it also returns true. (Ex : m.A)
     *
     * @param string $currentWord String input to check whether it is a shortcut.
     * @return bool true if given input is a shortcut, false otherwise.
     */
    private function isNameShortcut(string $currentWord): bool
    {
        if (mb_strlen($currentWord) == 1 && str_contains($this->upperCaseLetters(), $currentWord)) {
            return true;
        }
        if (mb_strlen($currentWord) == 3 && mb_substr($currentWord, 1, 1) == "." && str_contains($this->upperCaseLetters(), mb_substr($currentWord, 2, 1))) {
            return true;
        }
        return false;
    }

    /**
     * The repeatControl method takes a String word as an input, and a boolean exceptionMode and compress the repetitive chars. With
     * the presence of exceptionMode it directly returns the given word. Then it declares a counter i and loops till the end of the
     * given word. It compares the char at index i with the char at index (i+2) if they are equal then it compares the char at index i
     * with the char at index (i+1) and increments i by one and returns concatenated  result String with char at index i.
     *
     * @param string $word String input.
     * @param bool $exceptionMode boolean input for exceptional cases.
     * @return string String result.
     */
    private function repeatControl(string $word, bool $exceptionMode): string
    {
        if ($exceptionMode) {
            return $word;
        }
        $i = 0;
        $result = "";
        while ($i < mb_strlen($word)) {
            if ($i < mb_strlen($word) - 2 && mb_substr($word, $i, 1) == mb_substr($word, $i + 1, 1) && mb_substr($word, $i, 1) == mb_substr($word, $i + 2, 1) &&
                mb_substr($word, $i, 1) == mb_substr($word, $i + 3, 1)) {
                while ($i < mb_strlen($word) - 1 && mb_substr($word, $i, 1) == mb_substr($word, $i + 1, 1)) {
                    $i++;
                }
            }
            $result = $result . mb_substr($word, $i, 1);
            $i++;
        }
        return $result;
    }

    /**
     * The isApostrophe method takes a String line and an integer i as inputs. Initially declares a String apostropheLetters
     * which consists of abcçdefgğhıijklmnoöprsştuüvyzABCÇDEFGĞHIİJKLMNOÖPRSŞTUÜVYZ, âàáäãèéêëíîòóôûúqwxÂÈÉÊËÌÒÛQWX and 0123456789.
     * Then, it returns true if the result of contains method which checks the existence of previous char and next char
     * at apostropheLetters returns true, returns false otherwise.
     *
     * @param string $line String input to check.
     * @param int $i index.
     * @return bool true if apostropheLetters contains previous char and next char, false otherwise.
     */
    private function isApostrophe(string $line, int $i): bool
    {
        $apostropheLetters = $this->upperCaseLetters() . $this->lowerCaseLetters() . Language::$EXTENDED_LANGUAGE_CHARACTERS . Language::$DIGITS;
        if ($i > 0 && $i + 1 < mb_strlen($line)) {
            $previousChar = mb_substr($line, $i - 1, 1);
            $nextChar = mb_substr($line, $i + 1, 1);
            return str_contains($apostropheLetters, $previousChar) && str_contains($apostropheLetters, $nextChar);
        } else {
            return false;
        }
    }

    /**
     * The numberExistsBeforeAndAfter method takes a String line and an integer i as inputs. Then, it returns true if
     * the result of contains method, which compares the previous char and next char with 0123456789, returns true and
     * false otherwise.
     *
     * @param string $line String input to check.
     * @param int $i index.
     * @return bool true if previous char and next char is a digit, false otherwise.
     */
    private function numberExistsBeforeAndAfter(string $line, int $i): bool
    {
        if ($i > 0 && $i + 1 < mb_strlen($line)) {
            $previousChar = mb_substr($line, $i - 1, 1);
            $nextChar = mb_substr($line, $i + 1, 1);
            return str_contains(Language::$DIGITS, $previousChar) && str_contains(Language::$DIGITS, $nextChar);
        } else {
            return false;
        }
    }

    /**
     * The isTime method takes a String line and an integer i as inputs. Then, it returns true if
     * the result of the contains method, which compares the previous char, next char and two next chars with 0123456789,
     * returns true and false otherwise.
     *
     * @param string $line String input to check.
     * @param int $i index.
     * @return bool true if previous char, next char and two next chars are digit, false otherwise.
     */
    private function isTime(string $line, int $i): bool
    {
        if ($i > 0 && $i + 2 < mb_strlen($line)) {
            $previousChar = mb_substr($line, $i - 1, 1);
            $nextChar = mb_substr($line, $i + 1, 1);
            $twoNextChar = mb_substr($line, $i + 2, 1);
            return str_contains(Language::$DIGITS, $previousChar) && str_contains(Language::$DIGITS, $nextChar)
                && str_contains(Language::$DIGITS, $twoNextChar);
        } else {
            return false;
        }
    }

    /**
     * The onlyOneLetterExistsBeforeOrAfter method takes a String line and an integer i as inputs. Then, it returns true if
     * only one letter exists before or after the given index, false otherwise.
     *
     * @param string $line String input to check.
     * @param int $i    index.
     * @return bool true if only one letter exists before or after the given index, false otherwise.
     */
    private function onlyOneLetterExistsBeforeOrAfter(string $line, int $i): bool
    {
        if ($i > 1 && $i < mb_strlen($line) - 2) {
            return str_contains(SentenceSplitter::$PUNCTUATION_CHARACTERS, mb_substr($line, $i - 2, 1)) || str_contains(SentenceSplitter::$SEPARATORS, mb_substr($line, $i - 2, 1)) ||
                mb_substr($line, $i - 2, 1) == " " || (str_contains(SentenceSplitter::$SENTENCE_ENDERS, mb_substr($line, $i - 2, 1)) || str_contains(SentenceSplitter::$PUNCTUATION_CHARACTERS, mb_substr($line, $i + 2, 1)) ||
                    str_contains(SentenceSplitter::$SEPARATORS, mb_substr($line, $i + 2, 1)) || mb_substr($line, $i + 2, 1) == " ") || str_contains(SentenceSplitter::$SENTENCE_ENDERS, mb_substr($line, $i + 2, 1));
        } else {
            if ($i == 1 && str_contains($this->lowerCaseLetters(), mb_substr($line, 0, 1)) || str_contains($this->upperCaseLetters(), mb_substr($line, 0, 1))) {
                return true;
            } else {
                return $i == mb_strlen($line) - 2 && str_contains($this->lowerCaseLetters(), mb_substr($line, mb_strlen($line) - 1, 1));
            }
        }
    }

    /**
     * The split method takes a String line as an input. Firstly it creates a new sentence as currentSentence a new {@link Array}
     * as sentences. Then loops till the end of the line and checks some conditions;
     * If the char at ith index is a separator;
     * <p>
     * ' : assigns currentWord as currentWord'
     * { : increment the curlyBracketCount
     * } : decrement the curlyBracketCount
     * " : increment the specialQuotaCount
     * " : decrement the specialQuotaCount
     * ( : increment roundParenthesisCount
     * ) : decrement roundParenthesisCount
     * [ : increment bracketCount
     * ] : decrement bracketCount
     * " : assign quotaCount as 1- quotaCount
     * ' : assign apostropheCount as 1- apostropheCount
     * <p>
     * If the currentWord is not empty, it adds the currentWord after repeatControl to currentSentence.
     * <p>
     * If the char at index i is " and  bracketCount, specialQuotaCount, curlyBracketCount, roundParenthesisCount, and
     * quotaCount equal to 0 and also the next char is uppercase or digit, it adds currentSentence to sentences.
     * <p>
     * If the char at ith index is a sentence ender;
     * <p>
     * . and currentWord is www : assigns webMode as true. Ex: www.google.com
     * . and currentWord is a digit or in web or e-mail modes : assigns currentWord as currentWord+char(i) Ex: 1.
     * . and currentWord is a shortcut or an abbreviation : assigns currentWord as currentWord+char(i) and adds currentWord to currentSentence. Ex : bkz.
     * ' and next char is uppercase or digit: add word to currentSentence as ' and add currentSentence to sentences.
     *
     * <p>
     * If the char at index i is ' ', i.e space, add word to currentSentence and assign "" to currentSentence.
     * If the char at index i is -,  add word to currentSentence and add sentences when the wordCount of currentSentence greater than 0.
     * <p>
     * If the char at ith index is a punctuation;
     * : and if currentWord is "https" : assign webMode as true.
     * , and there exists a number before and after : assign currentWord as currentWord+char(i) Ex: 1,2
     * : and if line is a time : assign currentWord as currentWord+char(i) Ex: 12:14:24
     * - and there exists a number before and after : assign currentWord as currentWord+char(i) Ex: 12-1
     * {@literal @} : assign emailMode as true.
     *
     * @param string $line String input to split.
     * @return array sentences {@link Array} which holds split line.
     */
    public function split(string $line): array{
        $emailMode = false;
        $webMode = false;
        $i = 0;
        $specialQuotaCount = 0;
        $roundParenthesisCount = 0;
        $bracketCount = 0;
        $curlyBracketCount = 0;
        $quotaCount = 0;
        $apostropheCount = 0;
        $currentSentence = new Sentence();
        $currentWord = "";
        $sentences = [];
        while ($i < mb_strlen($line)) {
            if (str_contains(SentenceSplitter::$SEPARATORS, mb_substr($line, $i, 1))) {
                if (str_contains(SentenceSplitter::$HYPHENS, mb_substr($line, $i, 1)) && $this->onlyOneLetterExistsBeforeOrAfter($line, $i)) {
                    $currentWord = $currentWord . mb_substr($line, $i, 1);
                } else {
                    if (str_contains(SentenceSplitter::$APOSTROPHES, mb_substr($line, $i, 1)) && $currentWord != "" && $this->isApostrophe($line, $i)) {
                        $currentWord = $currentWord . mb_substr($line, $i, 1);
                    } else {
                        if ($currentWord != "") {
                            $currentSentence->addWord(new Word($this->repeatControl($currentWord, $webMode || $emailMode)));
                        }
                        if (mb_substr($line, $i, 1) != '\n') {
                            $currentSentence->addWord(new Word(mb_substr($line, $i, 1)));
                        }
                        $currentWord = "";
                        switch (mb_substr($line, $i, 1)) {
                            case '{':
                                $curlyBracketCount++;
                                break;
                            case '}':
                                $curlyBracketCount--;
                                break;
                            case '\uFF02':
                                $specialQuotaCount++;
                                break;
                            case '\u05F4':
                                $specialQuotaCount--;
                                break;
                            case '“':
                                $specialQuotaCount++;
                                break;
                            case '”':
                                $specialQuotaCount--;
                                break;
                            case '‘':
                                $specialQuotaCount++;
                                break;
                            case '’':
                                $specialQuotaCount--;
                                break;
                            case '(':
                                $roundParenthesisCount++;
                                break;
                            case ')':
                                $roundParenthesisCount--;
                                break;
                            case '[':
                                $bracketCount++;
                                break;
                            case ']':
                                $bracketCount--;
                                break;
                            case '"':
                                $quotaCount = 1 - $quotaCount;
                                break;
                            case '\'':
                                $apostropheCount = 1 - $apostropheCount;
                                break;
                        }
                        if (mb_substr($line, $i, 1) == '"' && $bracketCount == 0 && $specialQuotaCount == 0 && $curlyBracketCount == 0 &&
                            $roundParenthesisCount == 0 && $quotaCount == 0 && $this->isNextCharUpperCaseOrDigit($line, $i + 1)) {
                            $sentences[] = $currentSentence;
                            $currentSentence = new Sentence();
                        }
                    }
                }
            } else {
                if (str_contains(SentenceSplitter::$SENTENCE_ENDERS, mb_substr($line, $i, 1))) {
                    if (mb_substr($line, $i, 1) == '.' && Transliterator::create("tr-Lower")->transliterate($currentWord) == "www") {
                        $webMode = true;
                    }
                    if (mb_substr($line, $i, 1) == '.' && $currentWord != "" && ($webMode || $emailMode || (str_contains(Language::$DIGITS, mb_substr($line, $i - 1, 1)) && !$this->isNextCharUpperCaseOrDigit($line, $i + 1)))) {
                        $currentWord = $currentWord . mb_substr($line, $i, 1);
                        $currentSentence->addWord(new Word($currentWord));
                        $currentWord = "";
                    } else {
                        if (mb_substr($line, $i, 1) == '.' && ($this->listContains($currentWord) || $this->isNameShortcut($currentWord))) {
                            $currentWord = $currentWord . mb_substr($line, $i, 1);
                            $currentSentence->addWord(new Word($currentWord));
                            $currentWord = "";
                        } else {
                            if (mb_substr($line, $i, 1) == '.' && $this->numberExistsBeforeAndAfter($line, $i)) {
                                $currentWord = $currentWord . mb_substr($line, $i, 1);
                            } else {
                                if ($currentWord != "") {
                                    $currentSentence->addWord(new Word($this->repeatControl($currentWord, $webMode || $emailMode)));
                                }
                                $currentWord = mb_substr($line, $i, 1);
                                do {
                                    $i++;
                                } while ($i < mb_strlen($line) && str_contains(SentenceSplitter::$SENTENCE_ENDERS, mb_substr($line, $i, 1)));
                                $i--;
                                $currentSentence->addWord(new Word($currentWord));
                                if ($roundParenthesisCount == 0 && $bracketCount == 0 && $curlyBracketCount == 0 && $quotaCount == 0) {
                                    if ($i + 1 < mb_strlen($line) && mb_substr($line, $i + 1, 1) == "'" && $apostropheCount == 1 && $this->isNextCharUpperCaseOrDigit($line, $i + 2)) {
                                        $currentSentence->addWord(new Word("'"));
                                        $i++;
                                        $sentences[] = $currentSentence;
                                        $currentSentence = new Sentence();
                                    } else {
                                        if ($i + 2 < mb_strlen($line) && mb_substr($line, $i + 1, 1) == " " && mb_substr($line, $i + 2, 1) == "'" && $apostropheCount == 1 && $this->isNextCharUpperCaseOrDigit($line, $i + 3)) {
                                            $currentSentence->addWord(new Word("'"));
                                            $i += 2;
                                            $sentences[] = $currentSentence;
                                            $currentSentence = new Sentence();
                                        } else {
                                            if ($this->isNextCharUpperCaseOrDigit($line, $i + 1)) {
                                                $sentences[] = $currentSentence;
                                                $currentSentence = new Sentence();
                                            }
                                        }
                                    }
                                }
                                $currentWord = "";
                            }
                        }
                    }
                } else {
                    if (mb_substr($line, $i, 1) == ' ') {
                        $emailMode = false;
                        $webMode = false;
                        if ($currentWord != "") {
                            $currentSentence->addWord(new Word($this->repeatControl($currentWord, false)));
                            $currentWord = "";
                        }
                    } else {
                        if (str_contains(SentenceSplitter::$HYPHENS, mb_substr($line, $i, 1)) && !$webMode && $roundParenthesisCount == 0 && $this->isNextCharUpperCase($line, $i + 1) && !$this->isPreviousWordUpperCase($line, $i - 1)) {
                            if ($currentWord != "" && !str_contains(Language::$DIGITS, $currentWord)) {
                                $currentSentence->addWord(new Word($this->repeatControl($currentWord, $emailMode)));
                            }
                            if ($currentSentence->wordCount() > 0) {
                                $sentences[] = $currentSentence;
                            }
                            $currentSentence = new Sentence();
                            $roundParenthesisCount = $bracketCount = $curlyBracketCount = $quotaCount = $specialQuotaCount = 0;
                            if ($currentWord != "" && preg_match("/^\\d+$/", $currentWord) === 1) {
                                $currentSentence->addWord(new Word($currentWord . " -"));
                            } else {
                                $currentSentence->addWord(new Word("-"));
                            }
                            $currentWord = "";
                        } else {
                            if (str_contains(SentenceSplitter::$PUNCTUATION_CHARACTERS, mb_substr($line, $i, 1)) || str_contains(Language::$ARITHMETIC_CHARACTERS, mb_substr($line, $i, 1))) {
                                if (mb_substr($line, $i, 1) == ':' && (Transliterator::create("tr-Lower")->transliterate($currentWord) == "http" || Transliterator::create("tr-Lower")->transliterate($currentWord) == "https")) {
                                    $webMode = true;
                                }
                                if ($webMode) {
                                    //Constructing web address. Web address can contain both punctuation and arithmetic characters
                                    $currentWord = $currentWord . mb_substr($line, $i, 1);
                                } else {
                                    if (mb_substr($line, $i, 1) == ',' && $this->numberExistsBeforeAndAfter($line, $i)) {
                                        $currentWord = $currentWord . mb_substr($line, $i, 1);
                                    } else {
                                        if (mb_substr($line, $i, 1) == ':' && $this->isTime($line, $i)) {
                                            $currentWord = $currentWord . mb_substr($line, $i, 1);
                                        } else {
                                            if (mb_substr($line, $i, 1) == '-' && $this->numberExistsBeforeAndAfter($line, $i)) {
                                                $currentWord = $currentWord . mb_substr($line, $i, 1);
                                            } else {
                                                if ($currentWord != "") {
                                                    $currentSentence->addWord(new Word($this->repeatControl($currentWord, $emailMode)));
                                                }
                                                $currentSentence->addWord(new Word(mb_substr($line, $i, 1)));
                                                $currentWord = "";
                                            }
                                        }
                                    }
                                }
                            } else {
                                if (mb_substr($line, $i, 1) == '@') {
                                    //Constructing e-mail address
                                    $currentWord = $currentWord . mb_substr($line, $i, 1);
                                    $emailMode = true;
                                } else {
                                    $currentWord = $currentWord . mb_substr($line, $i, 1);
                                }
                            }
                        }
                    }
                }
            }
            $i++;
        }
        if ($currentWord != "") {
            $currentSentence->addWord(new Word($this->repeatControl($currentWord, $webMode || $emailMode)));
        }
        if ($currentSentence->wordCount() > 0) {
            $sentences[] = $currentSentence;
        }
        return $sentences;
    }
}