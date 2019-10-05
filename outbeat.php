<?php
// CONFIG
define("UPDATE_TOKEN", "B1JHLGLJ4GJKJ0WPDWZ2XRQGNWKK9MSCPTHA");
define("READJSON_TOKEN", "TN82CUD7E6C6");
define("READ_TOKEN", "SGVQZ5KXC7QM");
define("FILENAME", "stamp.json");
define("TIMEOUT", 2*60);

class Outbeat
{
  public $file = FILENAME;
  public $timeout = TIMEOUT; //2mins
  public $content;
  public $stamp;
  public $now;
  public $diff = 0;
  public $state_ok;
  public $error = false;
  public $wrote_int;

  function __construct() {
      if (!$this->getFile()){
        $this->error = true;
      } else {
        $obj = json_decode($this->content);
        $this->now = time();
        $this->stamp = $obj->{'last'};
        $this->diff = $this->now - $this->stamp;
        if ($this->diff > $this->timeout){
          $this->state_ok = false;
        } else {
          $this->state_ok = true;
        }
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

  function responseObject($wrote){
    $result->{'last'} = $this->stamp;
    $result->{'now'} = $this->now;
    $result->{'wrote'} = $wrote;
    $result->{'diff'} = $this->diff;
    $result->{'timeout'} = $this->timeout;
    $result->{'status'} = $this->state_ok?'good':'bad';
    $result->{'state_ok'} = $this->state_ok;
    return $result;
  }

  public function readJson(){
    if($this->error){
      return json_encode(array('error'=>'can not read file'));
    }

    return json_encode($this->responseObject(true));
  }

  public function updateFile() {
    $now = json_encode(array('last'=>$this->now ));
    if(file_put_contents($this->file, $now)) {
      $this->wrote_int = $this->now;
      return 1;
    } else {
      return 0;
    }
  }

  public function update(){
    if ($this->updateFile()){
      return json_encode($this->responseObject(false));
    } else {
      return json_encode(array('error'=>'can not update'));
    }

  }
}

if (isset($_REQUEST['token'])){
  $ob = new Outbeat();

  if ($ob->error){
    echo "error";
    exit();
  }

  //update
  if ($_REQUEST['token'] === UPDATE_TOKEN){
    header('Content-Type: application/json');
    echo $ob->update();
    exit();
  }

  //json state
  if ($_REQUEST['token'] === READJSON_TOKEN){
    header('Content-Type: application/json');
    echo $ob->readJson();
    exit();
  }

  //text state
  if ($_REQUEST['token'] === READ_TOKEN){
    if ($ob->state_ok){
      echo "good";
    } else {
      echo "bad";
    }
    exit();
  }

} else {
  echo "error";
  exit();
}

?>
