<?php

$ui = __DIR__ . '/../storage/ui';
return array(
    'cacheDir' => __DIR__ . '/../storage/cache/asset-manager',
    'assetDir' => dirname(__DIR__) . '/storage/public/assets',
    'baseDir' => $ui,
    'filters' => array(
        'js_min' => array(
            'class' => 'Assetic\Filter\JSMinFilter',
        ),
        'less' => array(
            'class' => 'Assetic\Filter\LessFilter',
            '__construct' => array('/usr/bin/node'),
        ),
        'css_min' => array(
            'class' => 'Assetic\Filter\CssMinFilter',
        ),
        'coffee' => array(
            'class' => 'Cynode\AssetManager\CoffeephpFilter',
        ),
    ),
    'assets' => array(
        'coffee_js' => array(
            'globs' => array(
                "$ui/coffee/*" => array(
                    'coffee'
                ),
            )
        ),
        'concat_js' => array(
            '@coffee_js',
            'files' => array(
                "$ui/js/main.js"
            ),
            'filters' => array(
                'js_min'
            )
        ),
        'stylesheet_css' => array(
            'files' => array(
                "$ui/css/style.css",
                "$ui/less/main.less" => array(
                    'less'
                )
            ),
            'filters' => array(
                'css_min'
            )
        )
    )
);