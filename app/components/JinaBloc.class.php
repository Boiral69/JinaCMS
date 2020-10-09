<?php

class Jina_Bloc extends Jina_Component
{
  public $id_parent = 1;
  public static $class_label = 'Bloc';
  public static $category = ['main'];
  public static $containers = array('Jina_Bloc_Container');
  public static $fields = ['onglet1'=>['label'=>'Onglet 1', 'sections'=>
    ['section1'=>['label'=>'Section 1', 'fields'=>
      ['title' => ['label' => 'Titre', 'class' => 'text', 'multilingual'=>false, 'attributes' => ['mandatory' => true, 'max_size' => 30]]]]]]];
  public static $isBloc = true;
}

