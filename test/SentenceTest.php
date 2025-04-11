<?php

use olcaytaner\Corpus\Sentence;
use olcaytaner\Dictionary\Dictionary\Word;

class SentenceTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void{
        $this->sentence = new Sentence("ali topu at mehmet ayşeyle gitti");
    }

    public function testGetWord(){
        $this->assertEquals(new Word("ali"), $this->sentence->getWord(0));
        $this->assertEquals(new Word("at"), $this->sentence->getWord(2));
        $this->assertEquals(new Word("gitti"), $this->sentence->getWord(5));
    }

    public function testGetIndex(){
        $this->assertEquals(0, $this->sentence->getIndex(new Word("ali")));
        $this->assertEquals(2, $this->sentence->getIndex(new Word("at")));
        $this->assertEquals(5, $this->sentence->getIndex(new Word("gitti")));
    }

    public function testWordCount(){
        $this->assertEquals(6, $this->sentence->wordCount());
    }

    public function testCharCount(){
        $this->assertEquals(27, $this->sentence->charCount());
    }

}