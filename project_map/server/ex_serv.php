<?php

/**
 * Server part that receives and handle GET / POST requests.
 */

require_once('Server.php');
require "../resources/logics/mapClass.php"; 
class ExerciseServer extends ServiceServer
{
 public function __construct()
    {
        IF(!empty($_GET['action'])){
            if($_GET['action'] == "new_map") 
            {   

                $width = $_REQUEST["width"];
                $height = $_REQUEST["height"];
                if ($width < 1 || $height < 1 ) {
                    echo "parameters problem";
                    exit();
                }
                $map = new Map(0,$width,$height);

                $this->displayJSONResult($map->getDataArray());
            }
            if($_GET['action'] == "map_list") 
            {   
                $this->displayJSONResult(Map::getListOfMaps());
            }
            if($_GET['action'] == "get_map") 
            {   
               $id = $_REQUEST["id"];
               if ($id == 0 ) {
                    echo "parameters problem";
                    exit();
                }
                
               $map = new Map($id,0,0);
               $this->displayJSONResult($map->getDataArray());
            }
            if($_GET['action'] == "get_calculations") 
            {   
                $id = $_REQUEST["id"];
                $start_point = $_REQUEST["start_point"];
                $end_point = $_REQUEST["end_point"];
                if ($id == 0 ) {
                    echo "parameters problem";
                    exit();
                }
                
                $map = new Map($id,0,0);
                $result =[
                    "fastest" => $map->findFastestPath($start_point,$end_point),
                    "longest" => $map->findLongestPath($start_point,$end_point)  
               ];
               $this->displayJSONResult($result);
            }
            if($_GET['action'] == "save_map") 
            {   
                $id = $_REQUEST["id"];
                $name = $_REQUEST["name"];
                if ($id == 0 || $name == "" ) {
                    echo "parameters problem";
                    exit();
                }
                $map = new Map($id,0,0);
                $map->setMapName($name);
            }
        }
   }
}
$obj = new ExerciseServer();
?>