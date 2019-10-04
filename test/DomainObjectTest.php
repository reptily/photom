<?php
namespace Ph\Test;

use PHPUnit\Framework\TestCase;
use \Ph\DomainObject as DomainObject;

class DomainObjectTest extends TestCase
{
    /**
	 * Testing is begin!
	 */
    
    public function testGet(){
        $domain = new DomainObject(["id" => 1, "name" => "Mark"]);
        $this->assertSame(1, $domain->getId());
        $this->assertSame("Mark", $domain->getName());
    }
    
    public function testSet(){
        $domain = new DomainObject(["id" => 1, "name" => "Mark"]);
        
        $domain->setId(6);
        $domain->setName("Piter");
        
        $this->assertSame(6, $domain->getId());
        $this->assertSame("Piter", $domain->getName());
    }
    
    public function testToString(){
        $domain = new DomainObject(["id" => 1, "name" => "Mark"]);
        $this->assertSame("id=`1` name=`Mark`", $domain->toString());
    }
    
    public function testToArray(){
        $domain = new DomainObject(["id" => 1, "name" => "Mark"]);
        $this->assertSame(["id" => 1, "name" => "Mark"], $domain->toArray());
    }
    
    public function testToJson(){
        $domain = new DomainObject(["id" => 1, "name" => "Mark"]);
        $this->assertSame(json_encode(["id" => 1, "name" => "Mark"]), $domain->toJson());
    }
    
}