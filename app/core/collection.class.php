<?php

class Jina_Collection
{
  public $class;
  public $entries;

  public function __construct($class)
  {
    $this->class = $class;
  }

  public function getEntries($filter = '', $page = 0, $pageSize = 50)
  {
    $sql = "select * from object where class='" . $this->class . "'" . ($filter ? ' and ' . $filter : '');
    if ($page > 0) $sql .= ' limit ' . (($page - 1) * $pageSize) . ', ' . $pageSize;
    $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    //if (!sizeof($result) > 0) return [];
    $this->entries = $result;
    return $this->entries;
  }
}
