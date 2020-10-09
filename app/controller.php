<?php
if (!isset($_GET['request'])) die();
$action = preg_replace("/\/(.*)$/", '', $_GET['request']);
$arguments = preg_replace("/^(.*?)\//", '', $_GET['request']);
$routes = array('upload'=>'upload.php', 'save'=>'save.php');
if (!isset($routes[$action])) die('No route');
include $routes[$action];
