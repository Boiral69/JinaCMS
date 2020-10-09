<?php
/**
 * Created by PhpStorm.
 * User: boiral
 * Date: 11/06/18
 * Time: 10:38
 */

class Jina_Component
{
  public static $containers = [];
  public static $class_label = 'Composant';
  public static $category = ['main'];
  public static $fields;// = ['label'=>['class'=>'text', 'label'=>'Libellé']];
  public static $isBloc = false;
  public static $tag = 'div';

  public static function getLabel()
  {
    $class = get_called_class();
    return $class::$class_label;
  }

  public static function isBloc() {
    $class = get_called_class();
    return $class::$isBloc;
  }

  public static function getChildClasses()
  {
    $class = get_called_class();
    $childs = [];
    foreach ($class::$containers as $container_class) {
      //$container_class = $name . '_Container';
      foreach ($container_class::$childClasses as $child_class) {
        $child_class = $child_class;
        $childs[$child_class] = $child_class::getLabel();
      }
    }
    return $childs;
  }

  public static function getAllComponentClasses()
  {
    $containers = [];
    $classes = [get_called_class()];
    self::getAllContainerClasses($containers);
    foreach ($containers as $container => $children) {
      foreach ($children as $child) {
        //$child = 'Jina_' . $child;
        if (!in_array($child, $classes)) $classes[] = $child;
      }
    }
    return $classes;
  }

  public static function getAllContainerCLasses(&$containers = [])
  {
    $class = get_called_class();
    foreach ($class::$containers as $container_class) {
      //$container_class = $name . '_Container';
      if (!isset($containers[$container_class])) {
        $containers[$container_class] = $container_class::$childClasses;
        foreach ($containers[$container_class] as $child_class) {
          //$child_class = 'Jina_' . $child_class;
          $child_class::getAllContainerClasses($containers);
        }
      }
    }
    return $containers;
  }

  public static function getLibrary()
  {
    $classes = self::getAllComponentClasses();
    $library = ['type'=>'folder', 'label'=>'Librairie', 'root'=>true, 'children'=>[]];
    foreach ($classes as $class) {
      if ($class == 'Jina_Root') continue;
      $l = &$library;
      foreach ($class::$category as $category) {
        if (!isset($l['children'][$category])) {
          $l['children'][$category] = ['type' => 'folder', 'label' => Jina_Category::getLabel($category), 'children' => []];
        }
        $l = &$l['children'][$category];
      }
      $l['children'][$class] = ['type' => 'component', 'className'=>$class, 'label' => $class::getLabel(), 'containers' => $class::$containers, 'fields'=>$class::$fields];
    }
    return $library;
  }

  public static function getTemplate()
  {
    $file = __DIR__ . '/../components/templates/ComponentManager.php';
    if (is_file($file) && is_file(__DIR__ . '/../components/templates/' . get_called_class() . '.php')) include $file;
  }

  public static function vueChildren($filter_components = [])
  {
    $childs = self::getChildClasses();
    $class = get_called_class();
    $containers = implode(' ', $class::$containers);
    $flow = '<div class="JinaContainer '.$containers.'" :id="\'JinaContainer-\'+manager.root.id+\'_\'+component.id"><template v-for="(child_component, cindex) in component.children">';
    foreach ($childs as $component => $label) {
      if (sizeof($filter_components) && !in_array($component, $filter_components)) continue;
      $tag = str_replace('_', '', strtolower($component));
      $flow .= '<' . $tag . ' v-if="child_component.className == \'' . $component . '\'" :component="child_component" :values="values" :manager="manager" :cindex="cindex"></' . $tag . '>' . "\n";
    }
    $flow .= '</template>';
    if (Jina_Context::$context->mode == 'admin') $flow .= '<div class="JinaNew" v-if="component.children.length == 0"><button title="Ajouter du contenu" class="fa fa-plus" @click.prevent="manager.addComponent(component, $event)"></button></div>' . "\n";
    $flow .= '</div>';
    return $flow;
  }

  public static function afterTemplate() {
  }
  public static function vueComponent()
  {
    $tag = str_replace('_', '', strtolower(get_called_class()));
    $flow = '<script type="text/javascript">Vue.component(\'' . $tag . '\', {template: \'#' . get_called_class() . '-template\', props: {component: Object, values: Object, manager: Object, cindex: Number}})</script>';
    return $flow;
  }
  public static function getAttributes() {
  }
}