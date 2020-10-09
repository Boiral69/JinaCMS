<?php

class Jina_Context
{
  public static $context;

  public function __construct($db, $session = null)
  {
    self::$context = new StdClass();
    self::$context->db = $db;
    self::$context->session = $session;
    self::$context->mode = 'admin';
  }

  public static function getContext()
  {
    return self::$context;
  }

  public static function setSession($s)
  {
    self::$context->session = $s;
  }

  public static function saveSession()
  {
    $_SESSION = self::$context->session;
  }

  public static function setLng($lng)
  {
    self::$context->lng = $lng;
  }

  public static function getLng()
  {
    return self::$context->lng;
  }

  public static function getSessionValue($k)
  {
    return isset(self::$context->session[$k]) ? self::$context->session[$k] : null;
  }

  public static function setSessionValue($k, $v)
  {
    self::$context->session[$k] = $v;
    self::saveSession();
  }

  public static function date2db($d)
  {
    return substr($d, 6, 4) . '-' . substr($d, 3, 2) . '-' . substr($d, 0, 2);
  }

  public static function translate($s, $scope = null)
  {
    return $s;
  }
}

