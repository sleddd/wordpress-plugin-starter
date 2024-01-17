# WordPress Starter or Boilerplate Plugin
Contributors: Claudette Raynor \
License: GPLv2 or later \
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin starter code for easier development.

This plugin uses WordPress wp-scripts to transpile blocks with a custom webpack config override to compile additional js and css. 

---
### Basic Installation
1. Download the plugin zip file and upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

With Composer and Node installed, from the plugin directory, at command line run: 
- composer install
- npm install
- npm run start

---
### Additional Notes

To see how to add a custom post type, review the example: src/classes/PostTypes/ExamplePostType.php

To add custom settings or options pages see examples:
```
/src/classes/Settings/ExampleACFSettingsPage.php
/src/classes/Settings/ExampleSettingsPage.php
/src/classes/Settings/ExampleSettingsSubPage.php
```

Note: You will need the ACF plugin if you implement the ACF settings page.

----------------
GUTENBERG BLOCKS 

See hello-world block as an example: src/blocks/hello-world

------------------
MAKING IT YOUR OWN

To rename this plugin with your plugin name, you will want to find and replace the following: 
- WP_STARTER_PLUGIN
- WpStarterPlugin
- wpstarterplugin

Run composer dump-autoload -o to rebuild the autoload files when done.

!! This plugin is not currently maintained except when I get time.
