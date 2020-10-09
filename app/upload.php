<?php
if (!sizeof($_FILES)) die();
class JinaUpload {
  var $whitelist = [];
  var $blacklist = [];
  var $uploaded = [];
  var $upload_dir = '../upload/';
  var $media;
  function __construct($files=[]) {
    $this->whitelist = $this->getJSON('../config/whitelist.json');
    $this->blacklist = $this->getJSON('../config/blacklist.json');
    foreach($files as $file) {
      if (!$this->check($file)) continue;
      $this->save($file);
    }
    $this->media = $this->getJSON($this->upload_dir.'media.json', new StdClass());
    foreach($this->uploaded as &$file) {
      if ($file['add']) {
        unset($file['add']);
        $this->media->files[] = $file;
      }
      unset($file['add']);
    }
    $this->putJSON($this->upload_dir.'media.json', $this->media);
    $result = new StdClass();
    $result->uploaded = $this->uploaded;
    $result->media = $this->media;
    echo json_encode($result);
  }
  function getJSON($file, $default=[]) {
    if (!file_exists($file)) return $default;
    $flow = file_get_contents($file);
    return json_decode($flow);
  }
  function putJSON($file, $obj) {
    $flow = json_encode($obj);
    file_put_contents($file, $flow);
  }
  function check($file) {
    $type = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
    if (sizeof($this->whitelist)) {
      foreach($this->whitelist as $e) {
        if (preg_match($e, $type)) return true;
      }
      return false;
    }
    foreach($this->blacklist as $e) {
      if (preg_match('$'.$e.'$', $type)) return false;
    }
    return true;
  }
  function save($file) {
    if ($file['error']) return false;
    $base = $this->upload_dir;
    $target = date('Y-m');
    if (!is_dir($base.$target)) mkdir ($base.$target);
    $name = basename($file['name']);
    $add = (!file_exists($base.$target.'/'.$name));
    if (!move_uploaded_file($file['tmp_name'], $base.$target.'/'.$name)) return false;
    $this->uploaded[] = array('add'=>$add, 'folder'=>$target, 'name'=>$name, 'type'=>$file['type'], 'size'=>$file['size']);
  }
}
$up = new JinaUpload($_FILES);