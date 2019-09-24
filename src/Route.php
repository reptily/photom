<?php
namespace Ph;

class Route{

    static public function redirect($m){
        $m=explode("/",$m);

        if (count($m)>2){
            foreach ($m as $u){
                $_REQUEST['arg'][]=$u;
            }
        } else {
            $_REQUEST['arg']=[];
        }

        $mFile = ucfirst($m[0]);
        if (is_dir("App/Controller/".$mFile)){
            if (is_file("App/Controller/".$mFile."/controller.php") && is_file("App/Controller/".$mFile."/config.php")){
                require "App/Controller/".$mFile."/controller.php";
                require "App/Controller/".$mFile."/config.php";

                if (isset($middleware) && isset($m[1]) && isset($middleware[$m[1]])){
                  $next=null;
                  foreach ($middleware[$m[1]] as $val) {
                    $val = ucfirst($val);
                    if (is_file("App/Middleware/".$val.".php")){
                      require "App/Middleware/".$val.".php"; 
                      if (!class_exists("\App\\Middleware\\".$val, false)){
                          Header::send(404);
                      }

                      if (!method_exists("\App\\Middleware\\".$val,"Init")){
                          Header::send(404);
                      }

                      $val = "\App\\Middleware\\".$val;
                      $mid = new $val;
                      $next = $mid->Init($next);                  
                    } else {
                      Header::send(404);
                    }
                  }
                }
                
                if (isset($css)){
                    $_SERVER['config']['module']['css']=$css;
                }
                
                if (isset($js)){
                    $_SERVER['config']['module']['js']=$js;
                }

                $m[0] = "\App\\Controller\\".$mFile;
                if (!class_exists($m[0], false)){
                    Header::send(404);
                }
                $controller = new $m[0];
                if (isset($m[1])){
                    $m[1]=ucfirst($m[1]);
                    $action="action".$m[1];
                    if (!method_exists($m[0],$action)){
                        Header::send(404);
                    }
                    
                    if (count($m) > 2){
                        $args = array_slice($m,2);
                    }
                    
                    $init=$controller->$action($args ?? null, $next ?? null);
                } else {
                    if (!method_exists($m[0],"Init")){
                        Header::send(404);
                    }
                    $init=$controller->Init($next ?? null);
                }

                if (isset($init['tpl'])){
                    $Tpl = new Tpl;
                    if (isset($init['value'])){
                        foreach ($init['value'] as $key=>$val){
                            $Tpl->setValue($key,$val);
                        }
                    }

                    if (isset($init['array'])){
                        foreach ($init['array'] as $name=>$array){
                            $Tpl->setArray($name,$array);
                        }
                    }
                    $Tpl->Read($init['tpl'],$layout ?? 'main');
                }
            } else {
                Header::send(404);
                exit("Can not load module ".$mFile);
            }
        } else {
            Header::send(404);
            exit("Can not load module ".$mFile);
        }
    }

    static private function methodExists($class,$method){

    }

    static private function classExists($class){

    }

}