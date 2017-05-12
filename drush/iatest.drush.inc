<?php

function iatest_drush_command() {
  $items = array();

  $items['iatest'] = array(
    'description' => "Test.",
    'arguments' => array(
      'filling' => 'The type of the sandwich (turkey, cheese, etc.). Defaults to ascii.',
    ),
    'options' => array(
      'spreads' => array(
        'description' => 'Comma delimited list of spreads.',
        'example-value' => 'mayonnaise,mustard',
      ),
    ),
    'examples' => array(
      'drush iatest' => 'Does not make a sandwich.',
    ),
    'aliases' => array('iat'),
    // No bootstrap at all.
    'bootstrap' => DRUSH_BOOTSTRAP_NONE,
  );

  return $items;
}

function drush_iatest() {
  for ($i = 1; $i < 10; ++$i) {
    drush_print("$i...");
    sleep(1);
  }
}
