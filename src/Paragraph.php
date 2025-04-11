<?php

namespace olcaytaner\Corpus;

class Paragraph
{
    private array $sentences = [];

    /**
     * A constructor of {@link Paragraph} class which creates an {@link Array} sentences.
     */
    public function __construct(){
    }

    /**
     * The addSentence method adds given sentence to sentences {@link Array}.
     *
     * @param Sentence $sentence Sentence type input to add sentences.
     */
    public function addSentence(Sentence $sentence){
        $this->sentences[] = $sentence;
    }

    /**
     * The sentenceCount method finds the size of the {@link Array} sentences.
     *
     * @return int the size of the {@link Array} sentences.
     */
    public function sentenceCount(): int{
        return count($this->sentences);
    }

    /**
     * The getSentence method finds the sentence from sentences {@link Array} at given index.
     *
     * @param int $index used to get a sentence.
     * @return Sentence sentence at given index.
     */
    public function getSentence(int $index): Sentence{
        return $this->sentences[$index];
    }
}