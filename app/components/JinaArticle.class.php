<?php

class Jina_Article extends Jina_Component
{
  public $id_parent = 1;
  public static $class_label = 'Article';
  public static $category = ['main'];
  public static $fields = ['onglet1'=>['label'=>'Contenu texte', 'sections'=>
    ['section1'=>['label'=>'Titre et sous-titre', 'fields'=>
      ['title' => ['label' => 'Titre', 'class' => 'text', 'multilingual'=>true, 'attributes' => ['mandatory' => true, 'max_size' => 30]],
        'subtitle' => ['label' => 'Sous-titre', 'class' => 'text', 'attributes' => ['mandatory' => true, 'max_size' => 80]]
        ]],
      'section2'=>['label'=>'Corps de l\'article', 'fields'=>['corps' => ['label' => 'Texte', 'class' => 'textarea', 'multilingual'=>true]]]]],
    'onglet2'=>['label'=>'Images', 'sections'=>
      ['section1'=>['label'=>'', 'hide'=>true, 'fields'=>['image'=>['hide'=>true, 'label' => 'Image', 'class' => 'file', 'multilingual'=>true, 'attributes' => ['mandatory' => true, 'type' => 'image']]]]]]];
  public static $tag = 'article';
}

