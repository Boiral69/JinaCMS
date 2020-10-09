<?php
/**
 * Created by PhpStorm.
 * User: boiral
 * Date: 18/10/19
 * Time: 15:28
 */
class JinaCMS {
  public static $containers;
  public static $classes;
  public static function init($mode='') {
    new Jina_Context(null, $_SESSION);
    Jina_Context::$context->mode = $mode;
    self::$classes = Jina_Root::getAllComponentClasses();
    foreach (self::$classes as $class) {
      self::$containers[$class] = $class::getChildClasses();
      $class::getTemplate();
    }
    echo "      <script type=\"text/javascript\">
    JinaCMS.init({
      path: './',
      admin: ".(Jina_Context::$context->mode == 'admin' ? 'true' : 'false').",
      ".(Jina_Context::$context->mode == 'admin' ? "
      collection: ".json_encode(Jina_Root::getLibrary()).",
      containers: ".json_encode(self::$containers).",
      components: ".json_encode(self::$classes)."," : '')."
      maxHistory: 100,
      defaultLng: 'fr'
    })
   </script>";
  }
  public static function display($id) {
    $file = __DIR__ .'/../../data/'.$id.'.json';
    if (file_exists($file)) {
      $flow = file_get_contents($file);
    } else {
      $flow = "{}";
    }
    echo "<div class=\"JinaComponent Jina_Root".(Jina_Context::$context->mode == 'admin' ? ' Jina_Admin' : '')."\" id=\"Jina-".$id."\">
  <jinaroot :component=\"component\" :values=\"values\" :manager=\"manager\" :cindex=\"cindex\"></jinaroot>
 </div>
      <script type=\"text/javascript\">
    JinaCMS.display({
      componentId: 'Jina-".$id."',
      component: ".$flow.",
      values: {code: 'test', type: '2'}
    })
  </script>";
  }
}