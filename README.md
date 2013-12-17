Cynode asset-manager [![Build Status](https://travis-ci.org/cynode/asset-manager.png)](https://travis-ci.org/cynode/asset-manager)
=============

Set up your assets manager in configuration file and let Cynode AssetManager compile it for you.

Configuration files:
---

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
            '__construct' => array(),
        ),
        'less' => array(
            'class' => 'Assetic\Filter\LessphpFilter',
            '__construct' => array()
        ),
        'css_min' => array(
            'class' => 'Assetic\Filter\CssMinFilter',
        ),
        'coffee' => array(
            'class' => 'Cynode\AssetManager\CoffeephpFilter'
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
            //this will concat your last registered asset name for this case this will use jquery_js assets
            '@jquery_js',
            '@twitter_js',
            ////this key will register globs asset
            'globs' => array(
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
