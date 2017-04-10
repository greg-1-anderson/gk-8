<?php

namespace Drupal\migrate_process_inline_images\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'MigrateProcessInlineImages' migrate process plugin.
 *
 * @MigrateProcess(
 *  id = "inline_images"
 * )
 */
class MigrateProcessInlineImages extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Plugin logic goes here.
  }

}
