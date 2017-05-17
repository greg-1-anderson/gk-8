<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Ensure that Twig files are correctly located.
 */
if (isset($_ENV['PANTHEON_ROLLING_TMP']) && isset($_ENV['PANTHEON_DEPLOYMENT_IDENTIFIER'])) {
var_export($_ENV);
  // Relocate the compiled twig files to <binding-dir>/tmp/ROLLING/twig.
  // The location of ROLLING will change with every deploy.
  $settings['php_storage']['twig']['directory'] = $_ENV['PANTHEON_ROLLING_TMP'];
  // Ensure that the compiled twig templates will be rebuilt whenever the
  // deployment identifier changes.  Note that a cache rebuild is also necessary.
  $settings['deployment_identifier'] = $_ENV['PANTHEON_DEPLOYMENT_IDENTIFIER'];
  $settings['php_storage']['twig']['secret'] = $settings['hash_salt'] . $settings['deployment_identifier'];
}

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Place the config directory outside of the Drupal root.
 */
$config_directories = array(
  CONFIG_SYNC_DIRECTORY => dirname(DRUPAL_ROOT) . '/config',
);

/**
 * Disable outgoing email on Pantheon, except on test and live environments.
 */
if (
  isset($_ENV['PANTHEON_ENVIRONMENT']) &&
  ( ($_ENV['PANTHEON_ENVIRONMENT'] != 'live') && ($_ENV['PANTHEON_ENVIRONMENT'] != 'test') )
) {
  $conf['mail_system'] = array(
    'default-system' => 'DevelMailLog',
  );
}

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Always install the 'standard' profile to stop the installer from
 * modifying settings.php.
 *
 * See: tests/installer-features/installer.feature
 */
$settings['install_profile'] = 'standard';


# $settings['file_public_base_url'] = 'http://downloads.example.com/files';
$settings['file_public_path'] = 'files';
# $settings['file_private_path'] = '';

