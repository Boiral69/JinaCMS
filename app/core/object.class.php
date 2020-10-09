<?php

class Jina_Object
{
  private $db;
  public $id;
  public $class;
  public $id_parent;
  public $name;
  public $indx1;
  public $indx2;
  public $indx3;
  public $indx4;
  public $fields = [];
  public $data = [];
  public $root_component = '';

  public function __construct($id = null)
  {
    $this->id = $id;
    $this->class = get_class($this);
    $this->db = Jina_Context::getContext()->db;
    if ($this->id) $this->read($this->id);
    $this->fields['data'] = [];
  }

  public static function getInstance($id, $class = '', $id_parent = '')
  {
    if ($id && !$class) {
      $object = new Jina_Object($id);
      if ($object->class != 'H_Object') {
        $class = $object->class;
      }
    }
    if (!$class) return false;
    $object = new $class($id);
    if ($id_parent) $object->id_parent = $id_parent;
    return $object;
  }

  public function read($id)
  {
    if (!is_numeric($id)) return false;
    $sql = "select * from object where id='" . $id . "'";
    $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    if (!sizeof($result) > 0) return false;
    $this->setFields($result[0]);
    return $this->id ? true : false;
  }

  public function setFields($fields)
  {
    $this->fields = $fields;
    $this->id = $this->fields['id'];
    $this->class = $this->fields['class'];
    $this->id_parent = $this->fields['id_parent'];
    $this->name = $this->fields['name'];
    $this->data = json_decode($this->fields['data'], true);
  }

  public function get($code)
  {
    return isset($this->data[$code]) ? $this->data[$code] : null;
  }

  public function set($code, $value)
  {
    $this->data[$code] = $value;
  }

  public function setData($data)
  {
    $this->data = $data;
  }

  public function save()
  {
    $this->id = isset($this->data['id']) ? $this->data['id'] : '';
    $this->id_parent = $this->data['id_parent'];
    $this->class = $this->data['class'];
    $this->index();
    if ($this->id) {
      $result = $this->update();
    } else {
      $result = $this->insert();
      $this->id = $this->getInsertID();
      $this->data['id'] = $this->id;
      $this->update();
    }
    return $result;
  }

  public function index()
  {
    $this->name = $this->data['name'];
    $this->indx1 = '';
    $this->indx2 = '';
    $this->indx3 = '';
    $this->indx4 = '';
  }

  public function update()
  {
    $sql = "update object set id_parent = :id_parent, class = :class, data = :data, name = :name, indx1 = :indx1, indx2 = :indx2, indx3 = :indx3, indx4 = :indx4 where id = :id";
    $st = $this->db->prepare($sql);
    $result = $st->execute(array('id_parent' => $this->id_parent, 'class' => $this->class, 'name' => $this->name, 'indx1' => $this->indx1, 'indx2' => $this->indx2, 'indx3' => $this->indx3, 'indx4' => $this->indx4, 'id' => $this->id, 'data' => json_encode($this->data)));
    return $result;
  }

  public function insert()
  {
    $sql = "insert into object (id_parent, class, data, name, indx1, indx2, indx3, indx4) values(:id_parent, :class, :data, :name, :indx1, :indx2, :indx3, :indx4)";
    $st = $this->db->prepare($sql);
    $result = $st->execute(array('id_parent' => $this->id_parent, 'class' => $this->class, 'name' => $this->name, 'indx1' => $this->indx1, 'indx2' => $this->indx2, 'indx3' => $this->indx3, 'indx4' => $this->indx4, 'data' => json_encode($this->data)));
    return $result;
  }

  public function delete()
  {
    if (!Jina_Context::getSessionValue('canAdmin')) return false;
    $sql = "select count(*) from object where id_parent = '" . $this->id . "'";
    $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    if ($result[0]['count(*)'] > 0) {
      return false;
    }
    $this->deleteDocs();
    $sql = "delete from object where id = :id";
    $st = $this->db->prepare($sql);
    $result = $st->execute(array('id' => $this->id));
    return $result;
  }

  public function getInsertID()
  {
    $sql = "select last_insert_id() as id";
    $result = Jina_Context::getContext()->db->query($sql);
    $r = $result->fetchAll(PDO::FETCH_ASSOC);
    return $r[0]['id'];
  }

  public function getName()
  {
    return $this->fields['name'];
  }

  public function setContent2upload($files = [])
  {
    foreach ($files as $k => $v) {
      if (!$v) continue;
      foreach ($v as $kk => $vv) {
        $name = $this->storeFile($vv['name'], $vv['content']);
        $legend = $vv['legend'];
        $this->data[$k][] = array('url' => $name, 'legend' => $legend);
      }
    }
    $this->save();
  }

  function str2filename($s)
  {
    $file = str_replace(array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'à', 'á', 'â', 'ã', 'ä', 'å', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'È', 'É',
      'Ê', 'Ë', 'è', 'é', 'ê', 'ë', 'Ç', 'ç', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü', 'ÿ', 'Ñ', 'ñ'),
      array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'e', 'e',
        'e', 'e', 'e', 'e', 'e', 'e', 'c', 'c', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'n', 'n'),
      $s);
    $file = strtolower(preg_replace("/[^A-Za-z0-9\.]+/", '-', $file));
    $ext = preg_replace("/(.*)\./", '.', $file);
    $file = preg_replace("/\.(.*?)$/", '', $file);
    if (in_array($ext, array('php', 'cgi', 'exe', 'dll', 'cmd', 'sh', 'js'))) {
      $ext = '';
    }
    $dir = $this->getDirectory('ressources');
    $file = $dir . $file;
    $nfile = $file . $ext;
    $i = 0;
    while (file_exists($nfile)) {
      $nfile = $file . '-' . (++$i) . $ext;
    }
    return $nfile;
  }

  function getDirectory($dir)
  {
    if (!is_dir($dir)) mkdir($dir);
    if (!preg_match("/\/$/", $dir)) $dir .= '/';
    for ($i = 0; $i < strlen($this->id); $i++) {
      $dir .= substr($this->id, $i, 1);
      if (!is_dir($dir)) mkdir($dir);
      $dir .= '/';
    }
    return $dir;
  }

  function storeFile($file, $content)
  {
    $file = $this->str2filename($file);
    $f = fopen($file, 'wb');
    $content = preg_replace("/^(.*?)base64,/", '', $content);
    $content = base64_decode($content);
    fwrite($f, $content);
    fclose($f);
    return $file;
  }

  function deleteDocs()
  {
    $this->deleteRessources($this->data);
  }

  function deleteRessources($a)
  {
    foreach ($a as $k => $v) {
      if (is_array($v)) $this->deleteRessources($v);
      if ($k === 'url') {
        if ($this->isRessource($v)) {
          $this->getDirectory('ressources/trash');
          rename($v, preg_replace("/^ressources/", 'ressources/trash', $v));
        }
      }
    }
  }

  function isRessource($s)
  {
    if (preg_match("/^ressources\//", $s)) {
      if (file_exists($s)) return true;
    }
  }
}

