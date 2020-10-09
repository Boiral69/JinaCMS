<?php
/**
 * Created by PhpStorm.
 * User: boiral
 * Date: 12/07/19
 * Time: 09:30
 */
class Jina_Column extends Jina_Component
{
  public $id_parent = 1;
  public static $containers = array('Jina_Column_Container');
  public static $class_label = 'Colonne';
  public static $category = [];
  public static $fields = ['onglet1'=>['label'=>'Onglet 1', 'sections'=>
    ['section1'=>['label'=>'Section 1', 'fields'=>
      ['title' => ['label' => 'Titre', 'class' => 'text', 'multilingual'=>false, 'attributes' => ['mandatory' => true, 'max_size' => 30]]]]]]];
  public static function afterTemplate() {
    if (Jina_Context::$context->mode == 'admin') {
      echo '<div v-if="cindex > 0" class="JinaColumnHandle"><div></div></div>';
    }
  }
}
