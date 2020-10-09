<?php

class Jina_Form extends Jina_Component
{
  public $id_parent = 1;
  public static $containers = array('Jina_Form_Container', 'Jina_Section_Container', 'Jina_Onglet_Container', 'Jina_Fieldset_Container');
  public static $class_label = 'Formulaire';
  public static $category = ['form'];
}

