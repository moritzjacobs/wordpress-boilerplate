# wordpress-boilerplate

This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff. 

### Running the installer will…:

- download and unpack the current stable Wordpress core in your language of choice

- rename the core and `wp-content` folders

- set up three runtime configurations (_local_, _staging_ and _live_)

- change the db prefix

- download fresh security keys.

- migrate the language files from the core


## Instructions

1. Surf to <http://<your_host>/_install.php>
2. Follow the instructions
3. Delete `_install.php`, `wp-config-runtime-sample.php` and `wp-config-sample.php`
4. Double check `wp-config.php`
5. Edit your runtime configurations