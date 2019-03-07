<?php
require_once dirname(__FILE__)."/../../database/db_func.php";
class Map
{
   const side_size = 100;
   private $id;
   private $name;
   private $width;
   private $height; 
   private $total_points;
   private $total_links;
   private $points = array();
   private $links = array();
   /*
    * if id = 0 then generate new map with parameters: midth and height
    * if id != 0 then take map from DB
    * 
    */
   function __construct(int $id,int $width,int $height){
        
        if($id != 0){
            $this->id = $id;
            $this->getFromDB();
        } else {
            $this->width = $width;
            $this->height = $height;
            $this->total_points = ($width + 1) * ($height + 1) ;
            $this->total_links = 2*$width*$height + $width + $height  ;
            $this->build_map();
            $this->postToDB();
        }
    }   
    /*
     * Construct map points and links
     * part of __construct
     * 
     */
    function build_map(){
        $this->constructPoints();
        $this->connectLinks();
        $this->makeOneWayConnections();
        $this->makeBlockConnections();
    }
    /**
     * creating array[][] of points and creating objects(Point)
     * 
     */
    function constructPoints(){
        for ($i = 1; $i <= $this->width + 1 ; $i++){
            for ($j = 1; $j <= $this->height + 1 ; $j++){
                $this->points[$i][$j] = new Point(($i-1)*($this->height + 1) + $j ,$i* Map::side_size, $j* Map::side_size);
            }
        }   
    }
    /*
     * creating array[] of links and creating objects(Link)
     */
    function connectLinks(){
        for ($i = 1; $i <= $this->width ; $i++){
            for ($j = 1; $j <= $this->height ; $j++){
                $this->links[] = new Link($this->points[$i][$j]->getId(), $this->points[$i + 1][$j]->getId(),"straight",rand(1,5));
                $this->links[] = new Link($this->points[$i][$j]->getId(), $this->points[$i][$j + 1]->getId(),"straight",rand(1,5));
                if ($i == $this->width){
                    $this->links[] = new Link($this->points[$i + 1][$j]->getId(), $this->points[$i + 1][$j + 1]->getId(),"straight",rand(1,5));
                }
                if ($j == $this->height){
                    $this->links[] = new Link($this->points[$i][$j + 1]->getId(), $this->points[$i + 1][$j + 1]->getId(),"straight",rand(1,5));
                }
            }
        } 
    }
    /*
     * Make random ways that are to start or to end. 1/3 of all links.
     * parameter Type change of object link
     */
    function makeOneWayConnections(){
        $count = $this->total_links / 3;
        $random_val = 0;
        for ($i = 1; $i <= $count ; $i++){
             $random_val = rand(0,$this->total_links - 1 );
             if ($this->links[$random_val]->getType() == "straight"){
                 $this->links[$random_val]->setType(((rand(1,2)==1))?"to_end":"to_start");
             }
             else{
                 $i--;
             }
        } 
    }
    /*
     * Make random ways that are blocked. 1/5 of all links.
     * parameter Type change of object link
     */
    function makeBlockConnections(){ 
        $count = $this->total_links / 5;
        $random_val = 0;
        for ($i = 1; $i <= $count ; $i++){
             $random_val = rand(0,$this->total_links - 1 );
             if ($this->links[$random_val]->getType() == "straight"){
                 $this->links[$random_val]->setType("blocked");
             }
             else{
                 $i--;
             }
        } 
    }
    /*
     * getter of parameter id
     */
    function getId(){
        return $this->id;
    }
    /*
     * setter of parameter id
     */
    private function setId($id){
        $this->id = $id;
    }
    /*
     * Inserting full map to DB(for each point and link database inserts)
     * 
     * 
     */
    function postToDB(){
        $db_con = new db_con();
            if ( $map = $db_con->GetConnect()->prepare(
              "INSERT INTO `map` ( `name`,`width`,`height`) VALUES (?,?,?)")){
              $map->bind_param("sii", $this->name , $this->width , $this->height);
              $db_con->forExecute_val($map);
              $this->id = mysqli_insert_id($db_con->GetConnect());
              foreach ($this->points as $key => $value){
                  foreach ($value as $key_in => $value_in){
                      $this->points[$key][$key_in]->postToDB($db_con, $this->id);
                  }
              }
              foreach ($this->links as $key => $value){
                  $this->links[$key]->postToDB($db_con, $this->id);
              }
          }
    }
    /*
     * Get Map from database and assign it values to current Map
     * 
     */
    function getFromDB(){
        $db_con = new db_con();
        if ( $db_requests = $db_con->GetConnect()->prepare(
            "SELECT * FROM `map` WHERE id = ?")){
            $db_requests->bind_param("i", $this->id);
            $map_data = $db_con->forExecute($db_requests)['0'];
            $this->name  = $map_data["name"] ;
            $this->width  = $map_data["width"] ;
            $this->height  = $map_data["height"] ;
        }
        if ($db_requests = $db_con->GetConnect()->prepare(
            "SELECT * FROM `point` WHERE map_id = ?")){
            $db_requests->bind_param("i", $this->id);
            $point_data = $db_con->forExecute($db_requests);
            $i = 1;
            $j = 1;
            foreach ($point_data as $value){
                $this->points[$i][$j] = new Point($value["id"],$value["x_coordinates"], $value["y_coordinates"]);
                $j++;
                if ($j == $this->width)
                  {$i++; $j = 1;}
            }
        }   
       if($db_requests = $db_con->GetConnect()->prepare(
            "SELECT * FROM `link` WHERE map_id = ?")){
            $db_requests->bind_param("i", $this->id);
            $link_data = $db_con->forExecute($db_requests);
            foreach ($link_data as $value){
                $this->links[] = new Link($value["point_start"],$value["point_end"],$value["type"],$value["value"]);
            }
        }
    }
    /*
     * Get list of maps that are saved
     * Return list with maps id and name
     */
    static function getListOfMaps(){
        $db_con = new db_con();
        if ( $map = $db_con->GetConnect()->prepare(
            "SELECT id, name FROM `map` WHERE name <> ''")){
            return $map_set = $db_con->forExecute($map);
        }
    }
    /*
     * Set name that is passed as parameter to Map in database
     * 
     */
    function setMapName($name){
        if (!empty($name)){
            $this->name = $name;
            $db_con = new db_con();
            if ( $map = $db_con->GetConnect()->prepare(
                "UPDATE `map` SET name = ? WHERE id = ?")){
                $map->bind_param("si", $this->name, $this->id );
                $db_con->forExecute_val($map);
            }
        }        
    }
   /*
    * Gets path for longest road from point A to point B
    * Return string with point set and length
    */
    function findLongestPath($start_point, $end_point){
        return $this->pathFinder($start_point, $end_point,function($x,$y){
            return $x < $y;
        });
    }
    /*
    * Gets path for shortest road from point A to point B
    * Return string with point set and length
    */
    function findFastestPath($start_point, $end_point){
        return $this->pathFinder($start_point, $end_point,function($x,$y){
            return $x > $y;
        });
    }
    /*
     * execute for findFastestPath and findLongestPath
     */
    function pathFinder($start_point, $end_point,$compare_func){
        $out = array();
        foreach ($this->links as $key => $value){
            if($value->getType() != "blocked") {
                if ($start_point == $value->getStart() && $value->getType() != "to_start"){
                    if( $end_point == $value->getEnd()){
                        if(!isset($out['point']) || $compare_func($out['value'],$value->getValue())){
                            $out['point'] = (string) $value->getEnd();
                            $out['value'] = $value->getValue();
                        }
                    } else {
                        $updated_map = clone $this;
                        unset($updated_map->links[$key]);
                        $tmp =  $updated_map->pathFinder($value->getEnd(),$end_point,$compare_func);
                        if(isset($tmp['point']) && (!isset($out['point']) || $compare_func($out['value'],$value->getValue() + $tmp['value']))){
                            $out['point'] = (string) $value->getEnd() . ":" . $tmp['point'];
                            $out['value'] = $value->getValue() + $tmp['value'];
                        }
                    }
                }
                if ($start_point == $value->getEnd() && $value->getType() != "to_end"){
                    if( $end_point == $value->getStart()){
                        if(!isset($out['point']) || $compare_func($out['value'], $value->getValue())){
                            $out['point'] = (string) $value->getStart();
                            $out['value'] = $value->getValue();
                        }
                    } else {
                        $updated_map = clone $this;
                        unset($updated_map->links[$key]);
                        $tmp =  $updated_map->pathFinder($value->getStart(),$end_point,$compare_func);
                        if(isset($tmp['point']) && (!isset($out['point']) || $compare_func($out['value'], $value->getValue() + $tmp['value']))){
                            $out['point'] = (string) $value->getStart() . ":" . $tmp['point'];
                            $out['value'] = $value->getValue() + $tmp['value'];
                        }
                    }
                }
            }
        }
        return $out;
    }
    /*
     * convert Map to array for better usage on client side
     */
    function getDataArray(){
        $result = array();
        $result = [
                "id" => $this->id,
                "name" => $this->name,
                "width" => $this->width,
                "height" => $this->height,
                "total_points" => $this->total_points,
                "total_links" => $this->total_links,
                "points" => array(),
                "links" => array()
                ];
        foreach ($this->points as $key => $value){
            foreach ($value as $key_in => $value_in){
                $result["points"][$this->points[$key][$key_in]->getId()] = $this->points[$key][$key_in]->getPoint();
            }
        }
        foreach ($this->links as $key => $value){
            $result["links"][] = $this->links[$key]->getDataArray();
        }
        return $result;
    } 
}
class Point{
    private $id;
    private $x_point;
    private $y_point;
    /*
     * Constructor for point
     */
   function __construct(int $id,int $x_point,int $y_point){
       $this->id = $id;//(($x_point/Map::side_size - 1) + $y_point/Map::side_size);
       $this->x_point = $x_point;
       $this->y_point = $y_point;
    }
   /*
    * getter of parameter id
    */
    function getId(){
       return $this->id; 
    }
    /*
     * setter of parameter id
     */
    private function setId($id){
        $this->id = $id;
    }
    /*
     * getter for x and y coordinates
     * return array
     */
    function getPoint(){
       return array("x" => $this->x_point,"y" => $this->y_point); 
    }
    /*
     * Insert current point to database
     */
    function postToDB(db_con $db_con, int $id){
        if ( $point = $db_con->GetConnect()->prepare(
          "INSERT INTO `point` ( `id`,`map_id`,`x_coordinates`,`y_coordinates`) VALUES (?,?,?,?)")){
          $point->bind_param("iiii", $this->id ,$id , $this->x_point , $this->y_point);
          $db_con->forExecute_val($point);
        }
    }
}

class Link{
    private $id;
    private $start_point;
    private $end_point;
    private $type;
    private $value;
   /*
    * constructor for link
    */
   function __construct($start_point, $end_point,$type,$value){
       $this->start_point = $start_point;
       $this->end_point = $end_point;
       $this->type = $type;
       $this->value = $value;
    }
   /*
    * getter for parameter id
    */
    function getId(){
       return $this->id; 
    }
    /*
     * setter for parameter id
     */
    private function setId($id){
        $this->id = $id;
    }
    /*
     * getter for parameter start_point
     */
    function getStart(){
       return $this->start_point; 
    }
    /*
     * getter for parameter end_point
     */
    function getEnd(){
       return $this->end_point; 
    }
    /*
     * getter for parameter type
     */
    function getType(){
       return $this->type; 
    }
    /*
     * setter for parameter type
     */
    function setType($type){
       $this->type = $type;
    }
    /*
     * getter for parameter value
     */
    function getValue(){
       return $this->value; 
    }

    /*
     * insert current link to database
     */
    function postToDB(db_con $db_con, int $id){
        if ( $link = $db_con->GetConnect()->prepare(
          "INSERT INTO `link` ( `point_start`,`point_end`,`type`,`value`,`map_id`) VALUES (?,?,?,?,?)")){
          $link->bind_param("iisii", $this->start_point , $this->end_point , $this->type, $this->value,$id);
          $db_con->forExecute_val($link);
        }
    }
    /*
     * getter for current link data
     * return array
     */
    function getDataArray(){
        $result = array();
        $result = [
                "start_point" => $this->start_point,
                "end_point" => $this->end_point,
                "type" => $this->type,
                "value" => $this->value,
                
                ];
        return $result;
    } 
}
?>