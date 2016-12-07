# wordpress-boilerplate

This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff. 


### Assumptions
- You want to have different runtime environments for development and production.
- You want a better folder structure for your Wordpress installs.
- You want to automate those tedious setup steps


### Running the installer will…
- download and unpack the current stable Wordpress core in your language of choice
- rename the `core` and `wp-content` folders
- create an `uploads` folder outside of wp-content
- set up at least two runtime environment configurations (local and live). Those will be detected based on your host name. eg. `your_host.dev`, `stage.domain.com`
- create a random DB prefix
- create fresh secret keys for each runtime environment.
- migrate the language files from core
- install a couple of MU-Plugins


## Instructions
1. Rename `gitigore` and `htaccess` to dotfiles
2. Surf to `http://your_host.dev/_install.php`
3. Follow the instructions
4. Delete `_install.php`, `_wp-config-ENV_SAMPLE.php` and `wp-config-SAMPLE.php`
5. Double check `wp-config.php` and edit your runtime configs
6. Continue with the usual wordpress install in `http://your-host/`


## changelog

### 1.2.1
- little stuff and tests
- added security to htaccess

### 1.2.0
- added wpnp-sanitize-upload as mu-plugin
- added automatic SSL protocol detection
- added db repair constant to wp-config
- bugfix corrected upload folder after environment change
- bugfix wp debugging mode

### 1.1.0
- added version option
- added install from domain root in htaccess
- renamed htaccess and gitignore
- bugfix wpnp-server-env

### 1.0.0
- added upload folder settings
- added mu-plugin for wp init and sanitize file uploads
- added a lot of constants to wp-config
- added a lot to .htaccess
- streamlined runtime environments and debug settings