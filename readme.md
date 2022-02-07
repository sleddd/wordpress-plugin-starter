# WordPress Starter or Boilerplate Plugin
Contributors: Claudette Raynor \
License: GPLv2 or later \
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin starter code for easier development.

![Composer](https://img.shields.io/badge/Composer-brightgreen)
![Node 14.16.0](https://img.shields.io/badge/Node-14.16.0-brightgreen)
![WebPack 5.12.3](https://img.shields.io/badge/WebPack-5.12.3-brightgreen)
![Babel 7.12.10](https://img.shields.io/badge/Babel-7.12.10-brightgreen)
![BrowserSync 2.26.13](https://img.shields.io/badge/BrowserSync-2.26.13-brightgreen)
![PostCSS 8.2.4](https://img.shields.io/badge/PostCSS-8.2.4-brightgreen)
![PurgeCSS 3.1.3](https://img.shields.io/badge/PurgeCSS-3.1.3-brightgreen)

This plugin uses webpack code from [wp-strap/wordpress-webpack-workflow](https://github.com/wp-strap/wordpress-webpack-workflow) which has been modified to support the development of JavaScript registered Gutenberg blocks. Visit [wp-strap/wordpress-webpack-workflow](https://github.com/wp-strap/wordpress-webpack-workflow) for more details.

---
### Basic Installation
1. Download the plugin zip file and upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

With Composer and Node installed, from the plugin directory, at command line run: 
- composer install
- npm install
- npm run dev:watch or npm run dev 

\
NPM Scripts include: 
- npm run prod
- npm run prod:watch 
- npm run dev
- npm run dev:watch

---
### Additional Notes

To see how to add a custom post type, review the example: src/classes/PostTypes/ExamplePostType.php

This plugin does support ACF through composer. To use ACF, you will need to update the composer.json to include the following lines: 

```
files: [
	"vendor/advanced-custom-fields/advanced-custom-fields-pro/acf.php",
	"vendor/advanced-custom-fields/advanced-custom-fields-pro/pro/acf-pro.php"
]

"require": {
	"advanced-custom-fields/advanced-custom-fields-pro": "*"
}
```

You will also need to add a .env file and fields for: 
```
PLUGIN_ACF_KEY=YOURKEY
VERSION=5.11.4
```

To add custom settings or options pages see examples:
```
/src/classes/Settings/ExampleACFSettingsPage.php
/src/classes/Settings/ExampleSettingsPage.php
/src/classes/Settings/ExampleSettingsSubPage.php
```

------------
API REQUESTS

API requests make use of the base API class. Depending on how you set it up, it also lets you reuse endpoints throughout the plugin or theme quickly and easily. There is an example built into the main plugin init file where the test API is added as a static property to hold the API instance and then initialized in the init_api method with the API Base class.

API Base class instance

```
new API(
    'yourendpoint_name',
    'https://yourendpoint',
    false, // Auth token for Basic Auth 
    false, // args 
    false, // show errors
    true, // cache - a transient
    0 // timeout in minutes
);
```

Below shows how to use it after it has been instanced via base class. The example uses the main plugin instance of the test api: 

To access directly via namespace in longform:\
WpStarterPlugin\WpStarterPlugin::$instance::$TEST_API->remote_get();

Stored in a variable:\
$wp_starter_plugin = \WpStarterPlugin\WpStarterPlugin::$instance;

Access transient directly:\
get_transient($wp_starter_plugin::$TEST_API->name);

Bypass transient to request a different endpoint or variation:\
$wp_starter_plugin::$TEST_API->cache = false;
$wp_starter_plugin::$TEST_API->url = $wp_starter_plugin::$TEST_API->url . '/1';
$wp_starter_plugin::$TEST_API->remote_get();

----------------
GUTENBERG BLOCKS 

Gutenberg blocks can be registered in JavaScript. See hello-world block as an example: src/blocks/hello-world

To rename this plugin with your plugin name, you will want to find and replace the following: 
- WP_STARTER_PLUGIN
- WpStarterPlugin
- wpstarterplugin

Run composer dump-autoload -o to rebuild the autoload files when done.

!! This plugin is not currently maintained except when I get time. I do plan to add additional support for REST API, customizer, tables, and additional ACF integration, but do not know when that will be.
