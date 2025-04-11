<?php

namespace olcaytaner\Corpus;

use olcaytaner\Dictionary\Dictionary\Word;

class Sentence
{
    protected array $words = [];

    /**
     * Another constructor of {@link Sentence} class with two inputs; a String sentence and a {@link LanguageChecker}
     * languageChecker. It parses a sentence by " " and then check the language considerations. If it is a valid word,
     * it adds this word to the newly created {@link Array} words.
     *
     * @param ?string $sentence String input.
     * @param ?LanguageChecker $languageChecker {@link LanguageChecker} type input.
     */
    public function __construct(?string $sentence = null, ?LanguageChecker $languageChecker = null)
    {
        if ($sentence !== null) {
            $wordArray = explode(" ", $sentence);
            foreach ($wordArray as $word) {
                if ($word !== ' ') {
                    if ($languageChecker !== null) {
                        if ($languageChecker->isValidWord($word)) {
                            $this->words[] = new Word($word);
                        }
                    } else {
                        $this->words[] = new Word($word);
                    }
                }
            }
        }
    }

    /**
     * The getWord method takes an index input and gets the word at that index.
     *
     * @param int $index is used to get the word.
     * @return Word the word in given index.
     */
    public function getWord(int $index): Word{
        return $this->words[$index];
    }

    /**
     * The getWords method returns the {@link Array} words.
     *
     * @return array words ArrayList.
     */
    public function getWords(): array{
        return $this->words;
    }

    /**
     * The getStrings method loops through the words {@link Array} and adds each words' names to the newly created
     * {@link Array} result.
     *
     * @return array result ArrayList which holds names of the words.
     */
    public function getStrings(): array{
        $result = [];
        foreach ($this->words as $word) {
            $result[] = $word->getName();
        }
        return $result;
    }

    /**
     * The getIndex method takes a word as an input and finds the index of that word in the words {@link Array} if it exists.
     *
     * @param Word $word Word type input to search for.
     * @return int index of the found input, -1 if not found.
     */
    public function getIndex(Word $word): int{
        $index = 0;
        foreach ($this->words as $w) {
            if ($w->getName() === $word->getName()) {
                return $index;
            }
            $index++;
        }
        return -1;
    }

    /**
     * The wordCount method finds the size of the words {@link Array}.
     *
     * @return int the size of the words {@link Array}.
     */
    public function wordCount(): int{
        return count($this->words);
    }

    /**
     * The addWord method takes a word as an input and adds this word to the words {@link Array}.
     *
     * @param Word $word Word to add words {@link Array}.
     */
    public function addWord(Word $word): void{
        $this->words[] = $word;
    }

    /**
     * The charCount method finds the total number of chars in each word of words {@link Array}.
     *
     * @return int number of the chars in the whole sentence.
     */
    public function charCount(): int{
        $sum = 0;
        foreach ($this->words as $word) {
            if ($word instanceof Word) {
                $sum += $word->charCount();
            }
        }
        return $sum;
    }

    /**
     * The insertWord method takes an index and a word as inputs. It inserts the word at given index to words
     * {@link Array}.
     *
     * @param int $index       index.
     * @param Word $newWord to add the words {@link Array}.
     */
    public function insertWord(int $index, Word $newWord): void{
        array_splice($this->words, $index, 0, [$newWord]);
    }

    /**
     * The replaceWord method takes an index and a word as inputs. It removes the word at given index from words
     * {@link Array} and then adds the given word to given index of words.
     *
     * @param int $index       index.
     * @param Word $newWord to add the words {@link Array}.
     */
    public function replaceWord(int $index, Word $newWord): void{
        array_splice($this->words, $index, 1, [$newWord]);
    }

    /**
     * The safeIndex method takes an index as an input and checks whether this index is between 0 and the size of the
     * words.
     *
     * @param int $index is used to check the safety.
     * @return bool true if an index is safe, false otherwise.
     */
    public function safeIndex(int $index): bool{
        return $index >= 0 && $index < count($this->words);
    }

    /**
     * The overridden toString method returns an accumulated string of each word in words {@link Array}.
     *
     * @return string String result which has all the word in words {@link Array}.
     */
    public function toString(): string{
        if (count($this->words) > 0) {
            $result = $this->words[0]->toString();
            for ($i = 1; $i < count($this->words); $i++) {
                $result .= " " . $this->words[$i]->toString();
            }
            return $result;
        } else {
            return "";
        }
    }

    /**
     * The toWords method returns an accumulated string of each word's names in words {@link Array}.
     *
     * @return string String result which has all the names of each item in words {@link Array}.
     */
    public function toWords(): string{
        if (count($this->words) > 0) {
            $result = $this->words[0]->getName();
            for ($i = 1; $i < count($this->words); $i++) {
                $result .= " " . $this->words[$i]->getName();
            }
            return $result;
        } else {
            return "";
        }
    }
}