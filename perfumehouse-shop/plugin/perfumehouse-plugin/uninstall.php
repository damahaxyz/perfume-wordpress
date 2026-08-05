<?php
/**
 * Plugin uninstall cleanup.
 *
 * @package PerfumeHousePlugin
 */

declare(strict_types=1);

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('perfumehouse_plugin_version');
