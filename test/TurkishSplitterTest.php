<?php

use olcaytaner\Corpus\TurkishSplitter;

class TurkishSplitterTest extends \PHPUnit\Framework\TestCase
{
    public function testSplit1(){
        $splitter = new TurkishSplitter();
        $this->assertCount(14, $splitter->split("Cin Ali, bak! " .
            "At. " .
            "Bak, Cin Ali, bak. " .
            "Bu at. " .
            "Baba, o atı bana al. " .
            "Cin Ali, bu at. " .
            "O da ot. " .
            "Baba, bu ata ot al. " .
            "Cin Ali, bu ot, o da at. " .
            "Otu al, ata ver. " .
            "Bak, Suna! " .
            "Cin Ali, ata ot verdi. " .
            "Su verdi. " .
            "Cin Ali, ata bir kova da su verdi."));
    }

    public function testSplit2(){
        $splitter = new TurkishSplitter();
        $this->assertCount(1, $splitter->split("WWW.GOOGLE.COM"));
    }

    public function testSplit3(){
        $splitter = new TurkishSplitter();
        $this->assertCount(1, $splitter->split("www.google.com"));
    }

    public function testSplit4(){
        $splitter = new TurkishSplitter();
        $splited = $splitter->split("1.adımda ve 2.adımda ne yaptın");
        $this->assertCount(1, $splited);
        $this->assertEquals(7, $splited[0]->wordCount());
    }

    public function testSplit5(){
        $splitter = new TurkishSplitter();
        $splited = $splitter->split("1. adımda ve 2. adımda ne yaptın");
        $this->assertCount(1, $splited);
        $this->assertEquals(7, $splited[0]->wordCount());
    }

    public function testSplit6(){
        $splitter = new TurkishSplitter();
        $splited = $splitter->split("Burada II. Murat ve I. Ahmet oyun oynadı");
        $this->assertCount(1, $splited);
        $this->assertEquals(8, $splited[0]->wordCount());
    }

    public function testSplit7(){
        $splitter = new TurkishSplitter();
        $split = $splitter->split("1.87 cm boyunda ve 84 kg ağırlığındaydı");
        $this->assertCount(1, $split);
        $this->assertEquals(7, $split[0]->wordCount());
    }

    public function testSplit8(){
        $splitter = new TurkishSplitter();
        $this->assertEquals("AAA", $splitter->split("AA piller, AAA pillerin yaklaşık üç kat kapasitesine sahiptir")[0]->getWord(3)->getName());
        $this->assertEquals("yakala", $splitter->split("Topu atıp yakalaaaa diye bağırdı")[0]->getWord(2)->getName());
    }

    public function testSplit9(){
        $splitter = new TurkishSplitter();
        $split = $splitter->split("Bunun yanı sıra erkek t-shirt modellerini klasik giyim tarzına uyarlayarak kullanmak da mümkündür");
        $this->assertCount(1, $split);
        $this->assertEquals(13, $split[0]->wordCount());
        $split = $splitter->split("USB-C, USB-A’ya göre çok daha yüksek hızlarda aktarım sağlayabilir");
        $this->assertCount(1, $split);
        $this->assertEquals(10, $split[0]->wordCount());
    }

}