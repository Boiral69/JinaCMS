<?php
/**
 * Created by PhpStorm.
 * User: boiral
 * Date: 18/10/19
 * Time: 13:18
 */
if (!$_POST['id']) die();
$id = $_POST['id'];
$data = $_POST['data'];
$f = fopen('../data/'.$id.'.json', 'w');
fwrite($f, $_POST['data']);
fclose($f);