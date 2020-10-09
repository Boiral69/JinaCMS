<?php
$dir = array('core', 'containers', 'components');
foreach ($dir as $d) {
  foreach (glob(__DIR__ . '/' . $d . '/*.php') as $f) {
    include_once $f;
  }
}

