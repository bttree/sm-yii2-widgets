<?php

namespace bttree\smywidgets\assets;

use yii\web\AssetBundle;

class TreeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower';

    /**
     * @var array
     */
    public $css = [
        'fancytree/dist/skin-lion/ui.fancytree.min.css'
    ];

    /**
     * @var array
     */
    public $js = [
        'jquery-ui/jquery-ui.js',
        'jquery-cookie/jquery.cookie.js',
        'fancytree/dist/jquery.fancytree-all.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'backend\assets\AppAsset',
    ];
}