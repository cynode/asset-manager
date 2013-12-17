<?php

$ui = __DIR__ . '/../ui';
return array(
    'cacheDir' => __DIR__ . '/../cache/asset',
    'assetDir' => dirname(__DIR__) . '/public/assets',
    'baseDir' => $ui,
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
    'assets' => array(
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
            '@jquery_js',
            '@twitter_js',
            'globs' => array(
                "$ui/default/coffee/main.coffee/*" => array(
                    'coffee'
                )
            ),
            'filters' => array('js_min')
        )
    )
);