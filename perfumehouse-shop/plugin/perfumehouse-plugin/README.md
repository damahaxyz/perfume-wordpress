# PerfumeHouse Shop Plugin

This plugin owns site-specific functionality that should remain active if the
site theme changes. Add product fields, checkout rules, REST endpoints, scheduled
tasks, integrations, and admin settings here.

The starter plugin provides:

- the `PerfumeHouse\Plugin` namespace;
- the `perfumehouse_plugin_loaded` extension hook;
- the `[perfumehouse_year]` example shortcode;
- activation version tracking and uninstall cleanup.

Split larger features into dedicated classes under `includes/`, require them from
`perfumehouse-plugin.php`, and register them from the main `Plugin` service.
