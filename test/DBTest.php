<?php
namespace Ph\Test;

use PHPUnit\Framework\TestCase;
use Ph\DB as DB;

class DBTest extends TestCase
{
    private $DB;
	private $table = "test";
	
	/**
	 * To work with the test
	 */
	
	public function setUp(): void{
        new \Ph\Test\Config;		
		$this->DB = new DB;
		$this->Create();
    }
	
	/**
	 * Support the method
	 */
	
	private function Create(){
		$table = [
                "id" =>[
                        "type"=>"int",
                        "count"=>11,
                        "isNull"=>false,
                        "autoIncrement"=>true
                ],
                "name"=>[]
        ];
		$this->DB->Create($this->table,$table);
	}
	
	private function insertOne(){
		$test = ["id" => "1", "name" => "Mark"];
		$this->DB->{$this->table}->Insert($test);
	}
	
	private function insertMany(){
		$test = [
			["id" => "1", "name" => "Mark"],
			["id" => "2", "name" => "James"],
			["id" => "3", "name" => "Lucas"],
		];
		$this->DB->{$this->table}->Insert($test);
	}
	
	/**
	 * After passing the test, you need to delete everything
	 */
        
    public function tearDown(): void{
		$this->DB->{$this->table}->Drop();
	}
	
	/**
	 * Testing is begin!
	 */
	
	public function testCreate(){
		$this->assertNotEmpty($this->DB->{$this->table});
	}
	
	public function testInsertOne(){
		$this->insertOne();
		$arr = $this->DB->{$this->table}->Select()->getRow();
		$this->assertSame(["id" => "1", "name" => "Mark"], $arr);
	}
	
	public function testInsertMany(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->Select()->getArray();
		
		$test = [
			["id" => "1", "name" => "Mark"],
			["id" => "2", "name" => "James"],
			["id" => "3", "name" => "Lucas"],
		];
		$this->assertSame($test, $arr);
	}
	
	public function testGetArray(){
		$this->insertOne();
		$arr = $this->DB->{$this->table}->Select()->getArray();
		$this->assertSame([["id" => "1", "name" => "Mark"]], $arr);
	}
	
	public function testGetRow(){
		$this->insertOne();
		$arr = $this->DB->{$this->table}->Select()->getRow();
		$this->assertSame(["id" => "1", "name" => "Mark"], $arr);
	}
	
	public function testGetJson(){
		$this->insertOne();
		$arr = $this->DB->{$this->table}->Select()->getJson();
		$this->assertSame(json_encode([["id" => "1", "name" => "Mark"]]), $arr);
	}
	
	public function testSelectWhere(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->where(["id" => "1"])->Select()->getRow();
		$this->assertSame(["id" => "1", "name" => "Mark"], $arr);
	}
	
	public function testSelectOnlyIdColum(){
		$this->insertOne();
		$arr = $this->DB->{$this->table}->field(["id"])->Select()->getRow();
		$this->assertSame(["id" => "1"], $arr);
	}
	
	public function testSelectWhereOr(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->where('or',["id" => 1],["id" => 3])->Select()->getArray();
		$test = [
			["id" => "1", "name" => "Mark"],
			["id" => "3", "name" => "Lucas"],
		];
		$this->assertSame($test, $arr);
	}
	
	public function testSelectWhereAndOnlyNameColum(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->field(["name"])->where(["id" => 1])->Select()->getRow();
		$this->assertSame(["name" => "Mark"], $arr);
	}
	
	public function testSelectTwoColum(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->field(["id","name"])->where(["id" => 1])->Select()->getRow();
		$this->assertSame(["id" => "1", "name" => "Mark"], $arr);
	}
	
	public function testSelectWhereOpertions(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->where("id > 1")->Select()->getArray();
		$test = [
			["id" => "2", "name" => "James"],
			["id" => "3", "name" => "Lucas"],
		];
		$this->assertSame($test, $arr);
	}
}