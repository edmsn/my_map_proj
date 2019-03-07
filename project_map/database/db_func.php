<?php
require_once 'config.php';

class DB_con{
    private $connection;
    /**
     * establish connection to database, stored in private variable
     * @global type $config - parameters for connect to DB
     * 
     */
    function __construct() {
        global $config;
        $this->connection = mysqli_connect(
            $config['db']['server'],
            $config['db']['username'],
            $config['db']['password'],
            $config['db']['name']
        );
        if($this->connection == false)
        {
            echo 'Не удалось подключиться к базе данных!<br>';
            echo mysqli_connect_error();
        }   
    }
    /*
     * function to get connection to DB.
     * @return connection
     */
    function GetConnect(){
        return $this->connection;
    }
    function forExecute($execute){
        $results = [];
        $execute->execute();
        $meta = $execute->result_metadata();
        while ($field = $meta->fetch_field()) {
          $parameters[] = &$row[$field->name];
        }
        call_user_func_array(array($execute, 'bind_result'), $parameters);
        while ($execute->fetch()) {
          foreach($row as $key => $val) {
            $x[$key] = $val;
          }
          $results[] = $x;
        }
        $execute->close();
        return $results;
    }
    function forExecute_val($execute){
        $execute->execute();
       // $execute->bind_result($result);
        $execute->fetch();
        $execute->close();
       // return $result;
    }
}

