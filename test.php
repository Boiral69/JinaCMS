<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
  <title>JinaCMS</title>
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
  <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-theme.css"/>
  <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="assets/js/jQuery/jquery-ui.min.css"/>
  <link rel="stylesheet" type="text/css" href="assets/js/jQuery/jquery.fancybox.min.css"/>
  <link rel="stylesheet" type="text/css" href="assets/css/jina.css"/>
  <script type="text/javascript" src="assets/js/jQuery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="assets/js/jQuery/jquery-ui.min.js"></script>
  <script type="text/javascript" src="assets/js/jQuery/jquery.fancybox.min.js"></script>
  <script type="text/javascript" src="assets/js/Vue/vue.js"></script>
  <!--<script type="text/javascript" src="assets/js/vuex.js"></script>-->
  <script type="text/javascript" src="assets/js/JinaCMS/main.js"></script>
  <script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
  <link rel="stylesheet" type="text/css" href="app/components/assets/css/Jina_Field.css" />
</head>
<body>
<?php
session_start();
include 'app/bootstrap.php';
JinaCMS::init('admin');
//$db = new PDO('mysql:host=' . NINA_HOST . ';dbname=' . NINA_DATABASE . ';charset=' . NINA_CHARSET, NINA_USER, NINA_PASSWORD);
?>
  <?php
  JinaCMS::display('1');
  ?>
<div style="padding: 50px; width: 1000px; margin: auto; max-width: 100%; box-sizing: border-box;">
  <?php
  JinaCMS::display('2');
  ?>
</div>
</body>
</html>
