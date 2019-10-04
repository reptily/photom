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
	
	private function createSubTable(){
		$table = [
                "id" =>[
                        "type"=>"int",
                        "count"=>11,
                        "isNull"=>false,
                        "autoIncrement"=>true
                ],
				"sId"=>[
					"type"=>"int",
                    "count"=>11,
				],
                "name"=>[]
        ];
		
		$this->DB->Create("sub",$table);
		
		$value = [
			["id" => "1", "sId" => "1", "name" => "First"],
			["id" => "2", "sId" => "1", "name" => "Second"],
			["id" => "3", "sId" => "2", "name" => "Third"],
		];
		
		$this->DB->sub->Insert($value);
	}
	
	/**
	 * After passing the test, you need to delete everything
	 */
        
    public function tearDown(): void{
		$this->DB->{$this->table}->Drop();
		if ($this->DB->sub){
			$this->DB->sub->Drop();
		}
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
	
	public function testInner(){
		$this->createSubTable();
		$this->insertMany();
		$arr = $this->DB->{$this->table}->inner("sub")->on("id","sId")->Select()->getArray();
			
		$test = [
			["id" => "1", "name" => "First", "sId" => "1"],
			["id" => "2", "name" => "Second", "sId" => "1"],
			["id" => "3", "name" => "Third", "sId" => "2"],
		];
		
		$this->assertSame($test, $arr);
	}
	
	public function testUpdate(){
		$this->insertOne();
		$this->DB->{$this->table}->set(["name" => "Piter"])->Update();
		$test = $this->DB->{$this->table}->Select()->getRow();
		$this->assertSame(["id" => "1", "name" => "Piter"], $test);
	}
	
	public function testOrder(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->order("name","asc")->Select()->getArray();
		
		$test = [			
			["id" => "2", "name" => "James"],
			["id" => "3", "name" => "Lucas"],
			["id" => "1", "name" => "Mark"],
		];
		
		$this->assertSame($test, $arr);
	}
	
	public function testLimit(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->limit(1)->Select()->getArray();
		$this->assertSame([["id" => "1", "name" => "Mark"]], $arr);
	}
	
	public function testLimitTwo(){
		$this->insertMany();
		$arr = $this->DB->{$this->table}->limit(2,3)->Select()->getArray();
		
		$this->assertSame([["id" => "3", "name" => "Lucas"]], $arr);
	}
	
	public function testCount(){
		$this->insertMany();
		$this->assertSame(3, $this->DB->{$this->table}->Count());
	}
	
	public function testTruncate(){
		$this->insertMany();
		$this->DB->{$this->table}->Truncate();
		$this->assertSame(0, $this->DB->{$this->table}->Count());
	}
	
}