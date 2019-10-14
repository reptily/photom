<?php
namespace Ph\Test;

use PHPUnit\Framework\TestCase;
use Ph\Storage as Storage;

class StorageTest extends TestCase
{
    
    public function testPullGetRemove(){
        Storage::pull("upload/test.txt","hello");
        $str = Storage::get("upload/test.txt");
        $this->assertSame("hello",$str);
        
        Storage::copy("upload/test.txt","tmp/test1.txt");
        $str = Storage::get("tmp/test1.txt");
        $this->assertSame("hello",$str);
        
        Storage::delete("tmp/test1.txt");
        
        Storage::move("upload/test.txt","upload_move/test_move.txt");
        $str = Storage::get("upload_move/test_move.txt");
        $this->assertSame("hello",$str);
        
        Storage::delete("upload_move/test_move.txt");
        
        mkdir('storage');
    }
        
}