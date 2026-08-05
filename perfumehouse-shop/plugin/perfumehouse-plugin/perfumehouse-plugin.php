<?php
/**
 * Plugin Name: PerfumeHouse Shop Plugin
 * Description: Site-specific business functionality for the PerfumeHouse shop.
 * Version: 1.0.0
 * Requires at least: 6.8
 * Requires PHP: 8.3
 * Text Domain: perfumehouse-plugin
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

define('PERFUMEHOUSE_PLUGIN_VERSION', '1.0.0');
define('PERFUMEHOUSE_PLUGIN_FILE', __FILE__);
define('PERFUMEHOUSE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PERFUMEHOUSE_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PERFUMEHOUSE_PLUGIN_DIR . 'includes/class-plugin.php';

register_activation_hook(
    PERFUMEHOUSE_PLUGIN_FILE,
    [PerfumeHouse\Plugin\Plugin::class, 'activate']
);

PerfumeHouse\Plugin\Plugin::instance()->boot();
