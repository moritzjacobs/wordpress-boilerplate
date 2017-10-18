# wordpress-boilerplate

This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff. 


### Assumptions
- You want to have multiple runtime environments for development and production.
- You want a better folder structure for your Wordpress installation
- You want to automate some setup steps
- you want to use https on your site


### Running the installer will…
- Download and unpack the current stable Wordpress core in your language of choice
- Migrate the language files from core
- Rename the `core` and `wp-content` folders
- Create an `uploads` folder outside of wp-content
- Set up at least two runtime environment configurations (local and live). Those will be detected based on your host name. eg. `dev.domain.com`, `stage.domain.com` (change the resulting `wp-config.php` if necessary)
- Create a random DB prefix
- Create fresh secret keys for each runtime environment.
- Install a couple of MU-Plugins


## Instructions
1. Extract the distribution zip in your web root as *wordpress-boilerplate*
2. Surf to [http://your_host.dev/wordpress-boilerplate/](http://your_host.dev/wordpress-boilerplate/)
3. Follow the instructions
5. Double check `wp-config.php` and edit your environment configs `wp-config-*.php`
6. Set your environment (see below), default is 'dev'
6. Continue with the usual Wordpress install in [http://your_host.dev](http://your_host.dev)

## Setting the environment

### Via config file
Create or edit a file `/wp-config-environment.php` with a content like
```
<?php

define('WP_SERVER_ENVIRONMENT', 'production');
```

### Nginx
Add to your server setting:
```
location / {
   fastcgi_param   WP_SERVER_ENVIRONMENT  env;
}
```

### Apache
Add to your to your `VirtualHost` or `Location` section:
``` 
SetEnv WP_SERVER_ENVIRONMENT env
```

## changelog

### 1.3.0
- https is a default now
- changed defaults to my liking. Yes, I do that.
- improved and cleaned up .htaccess template
- improved wp-config-creation
- fixed string replacement in template compilation
- refactored the core class
- better comments

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