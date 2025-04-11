<?php

use olcaytaner\Corpus\FileDescription;

class FileDescriptionTest extends \PHPUnit\Framework\TestCase
{
    public function testGetIndex(){
        $fileDescription = new FileDescription("mypath", "1234.train");
        $this->assertEquals(1234, $fileDescription->getIndex());
        $fileDescription = new FileDescription("mypath", "0000.test");
        $this->assertEquals(0, $fileDescription->getIndex());
        $fileDescription = new FileDescription("mypath", "0003.dev");
        $this->assertEquals(3, $fileDescription->getIndex());
        $fileDescription = new FileDescription("mypath", "0020.train");
        $this->assertEquals(20, $fileDescription->getIndex());
        $fileDescription = new FileDescription("mypath", "0304.dev");
        $this->assertEquals(304, $fileDescription->getIndex());
    }

    public function testGetGetExtension(){
        $fileDescription = new FileDescription("mypath", "1234.train");
        $this->assertEquals("train", $fileDescription->getExtension());
        $fileDescription = new FileDescription("mypath", "0000.test");
        $this->assertEquals("test", $fileDescription->getExtension());
        $fileDescription = new FileDescription("mypath", "0003.dev");
        $this->assertEquals("dev", $fileDescription->getExtension());
    }

    public function testGetFileName(){
        $fileDescription = new FileDescription("mypath", "0003.train");
        $this->assertEquals("mypath/0003.train", $fileDescription->getFileName());
        $this->assertEquals("newpath/0003.train", $fileDescription->getFileName("newpath"));
        $this->assertEquals("newpath/0000.train", $fileDescription->getFileName("newpath", 0));
        $this->assertEquals("newpath/0020.train", $fileDescription->getFileName("newpath", 20));
        $this->assertEquals("newpath/0103.train", $fileDescription->getFileName("newpath", 103));
        $this->assertEquals("newpath/0000.dev", $fileDescription->getFileName("newpath", 0, "dev"));
        $this->assertEquals("newpath/0020.dev", $fileDescription->getFileName("newpath", 20, "dev"));
        $this->assertEquals("newpath/0103.dev", $fileDescription->getFileName("newpath", 103, "dev"));
    }
}