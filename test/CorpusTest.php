<?php

use olcaytaner\Corpus\Corpus;
use olcaytaner\Dictionary\Dictionary\Word;

class CorpusTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void{
        $this->corpus = new Corpus("../corpus.txt");
        $this->simpleCorpus = new Corpus("../simpleCorpus.txt");
    }
    public function testNumberOfWords(){
        $this->assertEquals(826680, $this->corpus->numberOfWords());
        $this->assertEquals(24, $this->simpleCorpus->numberOfWords());
    }

    public function testContains(){
        $this->AssertTrue($this->simpleCorpus->contains("mehmet"));
        foreach ($this->simpleCorpus->getWordList() as $word) {
            $this->AssertTrue($this->simpleCorpus->contains($word));
        }
        $this->AssertTrue($this->corpus->contains("atatürk"));
        foreach ($this->corpus->getWordList() as $word) {
            $this->AssertTrue($this->corpus->contains($word));
        }
    }

    public function testWordCount(){
        $this->assertEquals(12, $this->simpleCorpus->wordCount());
        $this->assertEquals(98199, $this->corpus->wordCount());
    }

    public function testGetCount(){
        $this->assertEquals(309, $this->corpus->getCount(new Word("mustafa")));
        $this->assertEquals(109, $this->corpus->getCount(new Word("kemal")));
        $this->assertEquals(122, $this->corpus->getCount(new Word("atatürk")));
        $this->assertEquals(4, $this->simpleCorpus->getCount(new Word("ali")));
        $this->assertEquals(3, $this->simpleCorpus->getCount(new Word("gitti")));
        $this->assertEquals(4, $this->simpleCorpus->getCount(new Word("at")));
    }

    public function testSentenceCount(){
        $this->assertEquals(50000, $this->corpus->sentenceCount());
        $this->assertEquals(5, $this->simpleCorpus->sentenceCount());
    }

    public function testMaxSentenceLength(){
        $this->assertEquals(1092, $this->corpus->maxSentenceLength());
        $this->assertEquals(6, $this->simpleCorpus->maxSentenceLength());
    }

}