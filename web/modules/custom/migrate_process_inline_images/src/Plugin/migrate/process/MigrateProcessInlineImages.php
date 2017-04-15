<?php

namespace Drupal\migrate_process_inline_images\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'MigrateProcessInlineImages' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "inline_images"
 * )
 */
class MigrateProcessInlineImages extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Search for all <img> tag in the value (usually the body).
    if (preg_match_all('#<img[^>]*>#', $value, $matches)) {
      foreach ($matches[0] as $orig) {
        // Clean up the attributes in the img tag.
        $new = $this->cleanUpImageAttributes($orig);

        // Replace the original image path with the new image path
        // TODO: This is not a great way to recompose the value string.
        $value = preg_replace("|$orig|", $new, $value);
      }
    }

    return $value;
  }

  /**
   * Given the contents of an <img> tag, examine the attributes, clean
   * them up, and return the final text to use.
   */
  protected function cleanUpImageAttributes($imgElement)
  {
    $file_storage = \Drupal::entityTypeManager()->getStorage('file');

    // Pull the image path out of the image element.
    if (!preg_match('#src="([^"]*)"#', $imgElement, $matches)) {
        return $imgElement;
    }
    $imagePath = $matches[1];

    // If the image is stored in 'public://image', then an img src of
    // 'files/image/subdir/pict.jpg' will correspond to an entity uri
    // of 'subdir/pict.jpg'. The 'base' in this example is 'files/image';
    // strip the base off the img src so that we can use it to search for
    // the file by its entity uri.
    $imageBase = isset($this->configuration['base']) ? $this->configuration['base'] : 'sites/default/files';
    $imageBase = rtrim($imageBase, '/');
    $imagePath = str_replace('/' . $imageBase . '/', '', $imagePath);

    $fids = \Drupal::entityQuery('file')
      ->condition('uri', '%' . $imagePath . '%', 'LIKE')
      ->range(0, 2)
      ->execute();

    if (!$fids) {
        print "No image found for path: $imagePath (was " . $matches[1] . ")\n";
        return $imgElement;
    }
    $files = $file_storage->loadMultiple($fids);
    $firstFile = reset($files);
    $uuid = $firstFile->uuid();

    $align = $this->determinAlign($imgElement);
    $imgElement = str_replace('<img ', "<img$align data-entity-type=\"file\" data-entity-uuid=\"$uuid\" ", $imgElement);

    return $imgElement;
  }

  /**
   * Examine the entire contents of the <img> tag to see if there
   * is an `align="left"` or similar attribute to indicate a floating
   * image. If so, return an equivalent `data-align` attribute.
   */
  protected function determinAlign($imgElement)
  {
    $alignments = [ 'right', 'left' ];
    $alignmentPatterns = [
      'imgupl_floating_%s',
      'align="%s"',
      'align=\'%s\'',
    ];
    foreach ($alignments as $align) {
      foreach ($alignmentPatterns as $pattern) {
        $pattern = sprintf($pattern, $align);
        if (strpos($imgElement, $pattern) !== false) {
          return " data-align=\"$align\"";
        }
      }
    }
    return '';
  }
}
