<?php

class BeatClass
{
  public $file = 'stamp.json';
  public $timeout = 2 * 60;
  public $content;
  public $stamp;
  public $state_ok;

  function __construct() {
      $this->content = getFile;
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
        return $contents;
      } else {
        return 0;
      }
    }
  }

  public function updateFile() {
    $now = json_encode(array('last'=>time()));
    file_put_contents($file, $now);
    if(!getFIle()){
      return 0;
    }
  }
}
