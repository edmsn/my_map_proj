<?php

 abstract class ServiceServer
{
 
 public function __construct(){}
 
 protected function displayJSONResult($data)
 {
  header('Content-type: application/json');
 
  echo json_encode($data);
 
  exit();
 }
}