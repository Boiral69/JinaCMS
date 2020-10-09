<?php

class Jina_Root extends Jina_Component
{
  public $id_parent = 0;
  public static $containers = array('Jina_Root_Container');
  public static $class_label = 'Racine';

  public function index()
  {
    $this->name = $this->data['name'];
    $this->indx1 = $this->data['name'];
    $this->indx2 = '';
    $this->indx3 = '';
    $this->indx4 = '';
  }

  public static function getTemplate()
  {
    include __DIR__ . '/templates/Jina_Root.php';
  }
  public static function vueComponent()
  {
    $tag = str_replace('_', '', strtolower(get_called_class()));
    $flow = '<script type="text/javascript">Vue.component(\'' . $tag . '\', {template: \'#' . get_called_class() . '-template\', props: {component: Object, values: Object, manager: Object, index: Number},
    updated: function() {
      this.manager.doSortable()  
    }})</script>';
    return $flow;
  }
}