<?php

class Outbeat
{
  public $file = 'stamp.json';
  public $timeout = 2 * 60; //2mins
  public $content;
  public $stamp;
  public $state_ok;
  public $error = false;

  function __construct() {
      if (!$this->getFile()){
        $this->error = true;
      }
      $obj = json_decode($this->content);
      $this->stamp = $obj->{'last'};
      if ($this->stamp + $this->timeout > time()){
        $this->state_ok = false;
      } else {
        $this->state_ok = true;
      }
  }

  // https://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-php
  function isJson($str) {
      $json = json_decode($str);
      return $json && $str != $json;
  }

  function getFile() {
    if (!file_exists($this->file)) {
      return 0;
    } else {
      $contents = file_get_contents($this->file);
      if ($this->isJson($contents)){
        $this->content = $contents;
        return 1;
      } else {
        return 0;
      }
    }
  }

  public function updateFile() {
    $now = json_encode(array('last'=>time()));
    if(file_put_contents($file, $now)) {
      return $this->getFile();
    } else {
      return 0;
    }
  }

  public function update(){
    if ($this->updateFile()){
      return $this->content;
    } else {
      return json_encode(array('error'=>'can not update'));
    }

  }
}

if (isset($_REQUEST['token'])){
  $ob = new Outbeat();

  //update
  if ($_REQUEST['token'] === "J5E66Q1H5S0TLXSNYF493MLU9N1A8S19OL3J_SECURE_UPDATE_TOKEN"){
    header('Content-Type: application/json');
    $ob->update();
  }

  //json state
  if (isset($_REQUEST['token']) && $_REQUEST['token'] === "AQ6127Z3KNSA_JSON_READ_TOKEN"){
    header('Content-Type: application/json');
    $ob->content;
  }

  //text state
  if (isset($_REQUEST['token']) && $_REQUEST['token'] === "7PHIWRM2LI7B_READ_TOKEN"){
    echo $ob->state;
  }

} else {
  echo "error";
}

?>
