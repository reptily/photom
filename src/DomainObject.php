<?php
namespace Ph;

class DomainObject
{
  private $model;

  function __construct(array $values){
    foreach ($values as $key => $value){
      $this->model[$key]=$value;
    }
  }

  public function __call($name, $args){
    $a = preg_split("/(?<=\\w)(?=[A-Z])/",$name);
    $method=null;
    switch ($a[0]) {
      case 'get':
        $method="getter";
        $args=['key'=>mb_strtolower($a[1])];
        break;
      case 'set':
        $method="setter";
        $args=['key'=>mb_strtolower($a[1]),'val'=>$args[0]];
        break;
      case 'to':
        $method="to";
        $args=['key'=>mb_strtolower($a[1])];
        break;
      default:
        return exit("no method");
    }

    return call_user_func_array([$this,$method], $args);
  }

  public function getter($key){
    return $this->model[$key];
  }

  public function setter($key,$val){
    $this->model[$key]=$val;
  }

  public function to($key){
    if ($key == "string"){
      $str = "";
      foreach ($this->model as $key => $value) {
        $str .= "$key=`$value` ";
      }
      return mb_substr($str, 0, -1);
    }

    if ($key == "array"){
      return $this->model;
    }

    if ($key == "json"){
      return json_encode($this->model);
    }
  }
  
  public function is($key,$val){
    if ($key == "int"){
      echo $val;
    }
  }

}