<?php
namespace Ph\Test;

use PHPUnit\Framework\TestCase;
use Ph\DB as DB;
use Ph\Table as Table;

class TableTest extends TestCase
{
	private $DB;
	private $classTest;
	
	/**
	 * To work with the test
	 */
	
	public function setUp(): void{
		new \Ph\Test\Config;		
		$this->DB = new DB;
		$this->classTest = new Table;
		//$this->classTest->table="test"; // For the Test, you need a variable for a class Table
		
		$test = [
			["id" => "1", "name" => "Mark"],
			["id" => "2", "name" => "James"],
			["id" => "3", "name" => "Lucas"],
		];
		
		$this->DB->table->Insert($test);	
	}
	
	/**
	 * After passing the test, you need to delete everything
	 */
	
	public function tearDown(): void{
		$this->DB->table->Delete();
	}
	
	
	/**
	 * Testing is begin!
	 */
	
	public function testFindGet(){
		$var = $this->classTest->find(2)->get();
		$this->assertSame(["id" => "2", "name" => "James"], $var);
	}	
    
	public function testFindSet(){
		$this->classTest->find(2)->set(["name" => "David"]);
		$var = $this->classTest->find(2)->get();
		$this->assertSame(["id" => "2", "name" => "David"], $var);
	}
	
	public function testFindAll(){
		$arr = $this->classTest->findAll();
		$test = [
			["id" => "1", "name" => "Mark"],
			["id" => "2", "name" => "James"],
			["id" => "3", "name" => "Lucas"],
		];
		$this->assertSame($test, $arr);
	}
    
	public function testAdd(){
		$test = ["id" => "4", "name" => "David"];
		$this->classTest->add($test);
		$var = $this->classTest->find(4)->get();
		$this->assertSame(["id" => "4", "name" => "David"], $var);
	}
	
	public function testRemove(){
		$this->classTest->remove(2);
		$test = [
			["id" => "1", "name" => "Mark"],
			["id" => "3", "name" => "Lucas"],
		];
		$arr = $this->classTest->findAll();
		$this->assertSame($test,$arr);
	}
	
	public function testCount(){
		$var = $this->classTest->count();
		$this->assertSame(3,$var);
	}
    
    //count
}