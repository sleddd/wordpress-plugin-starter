# WordPress Plugin Starter Code

Contributors: Claudette Raynor \
License: GPLv2 or later \
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin starter code for easier development.

![WordPress 6.4.2](https://img.shields.io/badge/WordPress-blue)
![Composer 2.0](https://img.shields.io/badge/Composer-teal)
![WP Scripts-26.19.0](https://img.shields.io/badge/WPScripts-purple)

---
### Basic Installation
1. Download the plugin zip file and upload to the `/wp-content/plugins/` directory
2. With Composer and Node installed, from the plugin directory, at command line run: 
  - composer install
  - npm install
  - npm run start
3. Activate the plugin through the 'Plugins' menu in WordPress
---
### Additional Notes

To see how to add a custom post type, review the example: 
```
src/classes/PostTypes/ExamplePostType.php
```

To add custom settings or options pages see examples:
```
/src/classes/Settings/ExampleACFSettingsPage.php
/src/classes/Settings/ExampleSettingsPage.php
/src/classes/Settings/ExampleSettingsSubPage.php
```

Note: You will need the ACF plugin if you implement the ACF settings page.

---
### Gutenberg Blocks

See hello-world block as an example: 
```
src/blocks/hello-world
```
---
### Make It Your Own

To rename this plugin with your plugin name, you will want to find and replace the following: 
- WP_STARTER_PLUGIN
- WpStarterPlugin
- wpstarterplugin

Run composer dump-autoload -o to rebuild the autoload files when done.

---
!! This plugin is not currently maintained except when I get time.
