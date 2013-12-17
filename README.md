Cynode asset-manager [![Build Status](https://travis-ci.org/cynode/asset-manager.png)](https://travis-ci.org/cynode/asset-manager)
=============

Set up your asset manager in configuration file and let [assetic](https://github.com/kriswallsmith/assetic) compile it for you.

Installation
---
- This library require [composer](http://getcomposer.org) to install.
require `"cynode/asset-manager": "dev-master"` and install it via composer.

- Create configuration files for example : /path/to/your/configDirectory/asset.php

```php
<?php


$ui = __DIR__ . '/../ui';

return array(
//This is config key used for cache directory,this key is required and the value is your cache directory that must be exist and writeable by webserver
    'cacheDir' => __DIR__ . '/../cache/asset',
//This is config key used for published assets directory,this key is required and the value is your published assets directory that must be exist and writeable, and must be a public path (accessible by web browser)
    'assetDir' => dirname(__DIR__) . '/public/assets',
//This is config key used for your base UI directory *   
    'baseDir' => $ui,
//register your filters here
    'filters' => array(
        'js_min' => array(
            'class' => 'Assetic\Filter\JSMinFilter',
        ),
        'less' => array(
            'class' => 'Assetic\Filter\LessphpFilter',
        ),
        'css_min' => array(
            'class' => 'Assetic\Filter\CssMinFilter',
        ),
        'coffee' => array(
            'class' => 'Cynode\AssetManager\CoffeephpFilter',
            //you can add arguments for your constructor filter here
            '__construct'=>array(array('bare'=>true)),
        )
    ),
  //register your assets here.
    'assets' => array(
    //this key will used as published asset name, the end prefix _js will replaced to .js, used for asset name extension.
        'jquery_js' => array(
            'files' => array(
                "$ui/vendor/jquery/jquery.js"
            ),
        ),
        'twitter_js' => array(
            'files' => array(
                "$ui/vendor/twitter/dist/js/bootstrap.js"
            ),
        ),
        'main_js' => array(
            //this will concat your last registered asset name for this case this will use jquery_js assets and twitter_js assets
            '@jquery_js',
            '@twitter_js',
            
            ////this key will register globs asset
            'globs' => array(
            //this key will imported as globs assets and the value are filter for this glob asset.
                "$ui/default/coffee/main.coffee/*" => array(
                    'coffee'
                )
            ),
            //this is global filters for this asset.
            'filters' => array('js_min')
        )
    )
);
```

See [assetic](https://github.com/kriswallsmith/assetic) for more documentation to configure your assets, and filters.

- Initialize in your bootstrap file, for example /path/to/your/public/index.php

```php
<?php
use Cynode\AssetManager\AssetManager;
require '../vendor/autoload.php';
//your bootstrap script ....
//..
AssetManager::init(dirname(__DIR__) . '/config/asset.php');


```
