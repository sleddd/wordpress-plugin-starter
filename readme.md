# WordPress Starter or Boilerplate Plugin
Contributors: Claudette Raynor \
License: GPLv2 or later \
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin starter code for easier development.

![Composer](https://img.shields.io/badge/Composer-brightgreen)
![Node 14.17.0](https://img.shields.io/badge/Node-14.17.0-brightgreen)
![WebPack 5.74.0](https://img.shields.io/badge/WebPack-5.74.0-brightgreen)
![Babel 7.18.3](https://img.shields.io/badge/Babel-7.18.3-brightgreen)
![PostCSS](https://img.shields.io/badge/PostCSS-brightgreen)
![PurgeCSS](https://img.shields.io/badge/PurgeCSS-brightgreen)

This plugin uses Laravel Mix to help transpile code for CSS and Javascript. Laravel Mix can be extended to cover almost all of your transpiling needs. You can find full documentation online at: 

[Laravel Mix - What is mix? - https://laravel-mix.com/docs/6.0/what-is-mix](https://laravel-mix.com/docs/6.0/what-is-mix) \
[Laravel Mix - React/Vue/Typescript Support - https://laravel-mix.com/docs/4.0/mixjs](https://laravel-mix.com/docs/4.0/mixjs) \
[Laravel Mix - TailWind Support - https://laravel-mix.com/extensions/tailwind](https://laravel-mix.com/extensions/tailwind) \
[Laravel Mix - Postcss Support - https://laravel-mix.com/docs/6.0/postcss](https://laravel-mix.com/docs/6.0/postcss) 

---
### Basic Installation
1. Download the plugin zip file and upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

With Composer and Node installed, from the plugin directory, at command line run: 
- composer install
- npm install
- npm run dev 

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
$wp_starter_plugin::$TEST_API->cache = false; \
$wp_starter_plugin::$TEST_API->url = $wp_starter_plugin::$TEST_API->url . '/1'; \
$wp_starter_plugin::$TEST_API->remote_get();

----------------
GUTENBERG BLOCKS 

Gutenberg blocks can be registered in JavaScript. See hello-world block as an example: src/blocks/hello-world

------------------
MAKING IT YOUR OWN

To rename this plugin with your plugin name, you will want to find and replace the following: 
- WP_STARTER_PLUGIN
- WpStarterPlugin
- wpstarterplugin

Run composer dump-autoload -o to rebuild the autoload files when done.

!! This plugin is not currently maintained except when I get time.
