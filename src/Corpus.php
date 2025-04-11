<?php

namespace olcaytaner\Corpus;

use olcaytaner\DataStructure\CounterHashMap;
use olcaytaner\Dictionary\Dictionary\Word;
use Random\Engine\Mt19937;
use Random\Randomizer;

class Corpus
{
    protected array $paragraphs = [];
    protected array $sentences = [];
    protected CounterHashMap $wordList;
    protected string $fileName;

    /**
     * Another constructor of {@link Corpus} class which takes {@link SentenceSplitter}  as an input besides the file name.
     * It reads input file line by line and calls the sentenceSplitter method with each line, then calls addSentence method
     * with each sentence.
     *
     * @param ?string $fileName         String file name input that will be read.
     * @param SentenceSplitter | LanguageChecker | null $splitterOrChecker {@link SentenceSplitter} type input.
     */
    public function __construct(?string $fileName = null, SentenceSplitter | LanguageChecker | null $splitterOrChecker = null){
        $this->wordList = new CounterHashMap();
        if ($fileName != null) {
            $this->fileName = $fileName;
            $fh = fopen($fileName, 'r');
            while ($line = fgets($fh)) {
                $line = trim($line);
                if ($splitterOrChecker == null) {
                    $this->addSentence(new Sentence($line));
                } else {
                    if ($splitterOrChecker instanceof SentenceSplitter) {
                        $sentences = $splitterOrChecker->split($line);
                        $paragraph = new Paragraph();
                        foreach ($sentences as $sentence) {
                            $paragraph->addSentence($sentence);
                        }
                        $this->addParagraph($paragraph);
                    } else {
                        if ($splitterOrChecker instanceof LanguageChecker) {
                            $this->addSentence(new Sentence($line, $splitterOrChecker));
                        }
                    }
                }
            }
            fclose($fh);
        }
    }

    /**
     * The combine method takes a {@link Corpus} as an input and adds each sentence of sentences {@link Array}.
     *
     * @param corpus {@link Corpus} type input.
     */
    public function combine(Corpus $corpus){
        foreach ($corpus->sentences as $sentence) {
            $this->addSentence($sentence);
        }
    }

    /**
     * The addSentence method takes a Sentence as an input. It adds given input to sentences {@link Array} and loops
     * through each word in sentence and puts these words into wordList {@link CounterHashMap}.
     *
     * @param Sentence $sentence Sentence type input that will be added to sentences {@link Array} and its words will be added
     * to wordList {@link CounterHashMap}.
     */
    public function addSentence(Sentence $sentence): void{
        $this->sentences[] = $sentence;
        for ($i = 0; $i < $sentence->wordCount(); $i++) {
            $word = $sentence->getWord($i);
            $this->wordList->put($word->getName());
        }
    }

    /**
     * The numberOfWords method loops through the sentences {@link Array} and accumulates the number of words
     * in sentence.
     *
     * @return int size which holds the total number of words.
     */
    public function numberOfWords(): int{
        $size = 0;
        foreach ($this->sentences as $sentence) {
            $size += $sentence->wordCount();
        }
        return $size;
    }

    /**
     * The contains method takes a String word as an input and checks whether wordList {@link CounterHashMap} has the
     * given word and returns true if so, otherwise returns false.
     *
     * @param string $word String input to check.
     * @return bool true if wordList has the given word, false otherwise.
     */
    public function contains(string $word): bool{
        return $this->wordList->count($word) > 0;
    }

    /**
     * The addParagraph method takes a {@link Paragraph} type input. It gets the sentences in the given paragraph and
     * add these to the sentences {@link Array} and the words in the sentences to the wordList {@link CounterHashMap}.
     *
     * @param Paragraph $paragraph {@link Paragraph} type input to add sentences and wordList.
     */
    public function addParagraph(Paragraph $paragraph): void{
        $this->paragraphs[] = $paragraph;
        for ($i = 0; $i < $paragraph->sentenceCount(); $i++) {
            $this->addSentence($paragraph->getSentence($i));
        }
    }

    /**
     * Getter for the file name.
     *
     * @return file name.
     */
    public function getFileName(): string{
        return $this->fileName;
    }

    /**
     * Getter for the wordList.
     *
     * @return array the keySet of wordList.
     */
    public function getWordList(): array{
        return $this->wordList->keys();
    }

    /**
     * The wordCount method returns the size of the wordList {@link CounterHashMap}.
     *
     * @return int the size of the wordList {@link CounterHashMap}.
     */
    public function wordCount(): int{
        return $this->wordList->size();
    }

    /**
     * The getCount method returns the count value of given word.
     *
     * @param Word $word Word type input to check.
     * @return int the count value of given word.
     */
    public function getCount(Word $word): int{
        return $this->wordList->count($word->getName());
    }

    /**
     * The sentenceCount method returns the size of the sentences {@link Array}.
     *
     * @return int the size of the sentences {@link Array}.
     */
    public function sentenceCount(): int{
        return count($this->sentences);
    }

    /**
     * Getter for getting a sentence at given index.
     *
     * @param int $index to get sentence from.
     * @return Sentence sentence at given index.
     */
    public function getSentence(int $index): Sentence{
        return $this->sentences[$index];
    }

    /**
     * The paragraphCount method returns the size of the paragraphs {@link Array}.
     *
     * @return int the size of the paragraphs {@link Array}.
     */
    public function paragraphCount(): int{
        return count($this->paragraphs);
    }

    /**
     * Getter for getting a paragraph at given index.
     *
     * @param int $index to get paragraph from.
     * @return Paragraph paragraph at given index.
     */
    public function getParagraph(int $index): Paragraph{
        return $this->paragraphs[$index];
    }

    /**
     * The maxSentenceLength method finds the sentence with the maximum number of words and returns this number.
     *
     * @return int maximum length.
     */
    public function maxSentenceLength(): int{
        $maxLength = 0;
        foreach ($this->sentences as $sentence) {
            if ($sentence->wordCount() > $maxLength) {
                $maxLength = $sentence->wordCount();
            }
        }
        return $maxLength;
    }

    /**
     * The getAllWordsAsArrayList method creates new {@link Array} of ArrayLists and adds each word in each sentence
     * of sentences {@link Array} into new {@link Array}.
     *
     * @return array newly created and populated {@link Array}.
     */
    public function getAllWordsAsArrayList(): array{
        $allWords = [];
        for ($i = 0; $i < $this->sentenceCount(); $i++) {
            $allWords[] = $this->getSentence($i)->getWords();
        }
        return $allWords;
    }

    /**
     * The shuffleSentences method randomly shuffles sentences {@link Array} with given seed value.
     *
     * @param int $seed value to randomize shuffling.
     */
    public function shuffleSentences(int $seed): void{
        $r = new Randomizer(new Mt19937($seed));
        $this->sentences = $r->shuffleArray($this->sentences);
    }

    /**
     * The getTrainCorpus method takes two integer inputs foldNo and foldCount for determining train data size and count of fold respectively.
     * Initially creates a new empty Corpus, then finds the sentenceCount as N. Then, starting from the index 0 it loops through
     * the index (foldNo * N) / foldCount and add each sentence of sentences {@link Array} to new Corpus. Later on,
     * starting from the index ((foldNo + 1) * N) / foldCount, it loops through the index N and add each sentence of
     * sentences {@link Array} to new Corpus.
     *
     * @param int $foldNo    Integer input for train set size.
     * @param int $foldCount Integer input for counting fold.
     * @return Corpus the newly created and populated Corpus.
     */
    public function getTrainCorpus(int $foldNo, int $foldCount): Corpus{
        $trainCorpus = new Corpus();
        $N = $this->sentenceCount();
        for ($i = 0; $i < ($foldNo * $N) / $foldCount; $i++) {
            $trainCorpus->addSentence($this->getSentence($i));
        }
        for ($i = (($foldNo + 1) * $N) / $foldCount; $i < $foldCount; $i++) {
            $trainCorpus->addSentence($this->getSentence($i));
        }
        return $trainCorpus;
    }

    /**
     * The getTestCorpus method takes two integer inputs foldNo and foldCount for determining test data size and count of
     * fold respectively. Initially creates a new empty Corpus, then finds the sentenceCount as N.
     * Then, starting from the index (foldNo * N) / foldCount it loops through the index ((foldNo + 1) * N) / foldCount and
     * add each sentence of sentences {@link Array} to new Corpus.
     *
     * @param int $foldNo    Integer input for test size.
     * @param int $foldCount Integer input counting fold.
     * @return Corpus the newly created and populated Corpus.
     */
    public function getTestCorpus(int $foldNo, int $foldCount): Corpus{
        $testCorpus = new Corpus();
        $N = $this->sentenceCount();
        for ($i = ($foldNo * $N) / $foldCount; $i < (($foldNo + 1) * $N) / $foldCount; $i++) {
            $testCorpus->addSentence($this->getSentence($i));
        }
        return $testCorpus;
    }

    /**
     * The allSubStrings method takes a Word and an integer as inputs. If the length of the word's name is less than
     * given input k, it concatenates the each word's name with {@literal </s>} and adds to result which starts with
     * {@literal <s>}. Else,  it finds out the substring, concatenates with {@literal </s>} and adds to the
     * String result.
     *
     * @param Word $word Word type input to find substrings.
     * @param int $k    Integer for substring length.
     * @return string String result that has all substrings.
     */
    public function allSubStrings(Word $word, int $k): string{
        $result = "<s> ";
        if (mb_strlen($word->getName()) < $k){
            $result .= $word->getName() . " </s>\n";
        } else {
            $result .= mb_substr($word->getName(), 0, $k);
            for ($j = 1; $j < $word->charCount() - $k + 1; $j++) {
                $result .= " " . mb_substr($word->getName(), $j, $k);
            }
            $result .= " </s>\n";
        }
        return $result;
    }
}