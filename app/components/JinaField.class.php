<?php

class Jina_Field extends Jina_Component
{
  public static $class_label = 'Champ';
  public static $fields = [
  'code' => ['label' => 'Code', 'class' => 'text', 'attributes' => ['mandatory' => true, 'controls' => ['isFieldName'], 'max_size' => 30]],
  'label' => ['label' => 'Libellé', 'class' => 'text', 'attributes' => ['mandatory' => true, 'max_size' => 80]],
  'mandatory' => ['label' => 'Obligatoire', 'class' => 'checkbox'],
  'controls' => ['label' => 'Contrôles', 'class' => 'select', 'attributes' => ['list_code' => 'controls', 'multiple' => true]]
  ];
  public static $category = ['form'];

  public function isFieldName($s)
  {
    return preg_match("/^[A-z]{1}[A-z0-9_]*$/", $s);
  }

  public function isDate($s, $as = false)
  {
    if (!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/")) return false;
    return checkdate(substr($s, ($as ? 0 : 3), 2), substr($s, ($as ? 3 : 0), 2), substr($s, 6, 4));
  }

  public function isMail($s)
  {
    return filter_var($s, FILTER_VALIDATE_EMAIL);
  }
}

class Jina_Field_Text extends Jina_Field
{
  public static $class_label = 'Champ texte';
  public static $fields = ['onglet1'=>['label'=>'Onglet 1', 'sections'=>
    ['section1'=>['label'=>'Section 1', 'fields'=>
    ['code' => ['label' => 'Code', 'class' => 'text', 'attributes' => ['mandatory' => true, 'controls' => ['isFieldName'], 'max_size' => 30]],
    'label' => ['label' => 'Libellé', 'class' => 'text', 'attributes' => ['mandatory' => true, 'max_size' => 80]],
    'mandatory' => ['label' => 'Obligatoire', 'class' => 'checkbox'],
    'controls' => ['label' => 'Contrôles', 'class' => 'select', 'attributes' => ['list_code' => 'controls', 'multiple' => true]]]],
    'section2'=>['label'=>'Section 2']]],
    'onglet2'=>['label'=>'Onglet 2']];
}

class Jina_Field_Textarea extends Jina_Field
{
  public static $class_label = 'Champ paragraphe';
}

class Jina_Field_RichTextarea extends Jina_Field
{
  public static $class_label = 'Champ paragraphe enrichi';
}

class Jina_Field_Document extends Jina_Field
{
  public static $class_label = 'Champ document';
}

class Jina_Field_Image extends Jina_Field_Document
{
  public static $class_label = 'Champ image';
}

class Jina_Field_Select extends Jina_Field
{
  public $values = [];
  public static $class_label = 'Champ liste déroulante';

  public function getValues($code)
  {
    $filename = __DIR__ . '/../list_values/' . $code . '.json';
    if (!is_file($filename)) return $this->values;
    $flow = file_get_contents($filename);
    $this->values = json_decode($flow, true);
    return $this->values;
  }

  public function getValue($key)
  {
    if (!isset($this->values[$key])) return false;
    return Jina_Context::translate($this->values[$key], 'Field_Select');
  }
}

class Jina_Field_Radio extends Jina_Field
{
  public static $class_label = 'Champ bouton radio';
}

class Jina_Field_Checkbox extends Jina_Field
{
  public static $class_label = 'Champ case à cocher';
}

class Jina_Field_Hidden extends Jina_Field
{
  public static $class_label = 'Champ caché';
}

class Jina_Field_Link extends Jina_Field
{
  public static $class_label = 'Champ lien vers une base';
}
